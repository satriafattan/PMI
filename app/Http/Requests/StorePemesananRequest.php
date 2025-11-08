<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePemesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalisasi input SEBELUM validasi:
     * - Boolean cek_transfusi
     * - Uppercase produk/gol_darah
     * - Mapping tanggal_diperlukan -> tanggal_permintaan
     * - Normalisasi tanggal ke format Y-m-d (atau null)
     */
    protected function prepareForValidation(): void
    {
        $fixDate = function ($v) {
            if ($v === null || $v === '') return null;
            $ts = strtotime($v);
            return $ts ? date('Y-m-d', $ts) : null;
        };

        // mapping tanggal_diperlukan -> tanggal_permintaan (jika diisi)
        $tanggal_permintaan = $this->input('tanggal_permintaan') ?: $this->input('tanggal_diperlukan');

        $this->merge([
            // booleans / enum casing
            'cek_transfusi'      => filter_var($this->cek_transfusi, FILTER_VALIDATE_BOOLEAN),
            'produk'             => $this->produk ? strtoupper(trim($this->produk)) : null,
            'gol_darah'          => $this->gol_darah ? strtoupper(trim($this->gol_darah)) : null,
            'rhesus'             => $this->rhesus ? trim($this->rhesus) : null,

            // normalized dates
            'tanggal_pemesanan'  => $fixDate($this->input('tanggal_pemesanan')),
            'tanggal_permintaan' => $fixDate($tanggal_permintaan),
            'tanggal_serologi'   => $fixDate($this->input('tanggal_serologi')),
            'tanggal_transfusi'  => $fixDate($this->input('tanggal_transfusi')),
        ]);
    }

    public function rules(): array
    {
        return [
            // STEP 1 – pasien & RS
            'rs_pemesan'        => ['required', 'string', 'max:150'],
            'email'             => ['required', 'email', 'max:150'],
            'nomor_telepon'     => ['required', 'string', 'max:30'],
            'jenis_kelamin'     => ['required', 'in:L,P'],
            'no_regis_rs'       => ['required', 'string', 'max:100'],
            'nama_suami_istri'  => ['nullable', 'string', 'max:150'],
            'nama_dokter'       => ['required', 'string', 'max:150'],
            'nama_pasien'       => ['required', 'string', 'max:150'],

            // STEP 2 – data pemesanan
            // form mengirim 'tanggal_diperlukan' (sudah dipetakan ke tanggal_permintaan di prepareForValidation)
            'tanggal_diperlukan' => ['nullable', 'date'],     // tidak disimpan, hanya agar aman bila ikut terkirim
            'tanggal_permintaan' => ['required', 'date'],
            'pernah_serologi'   => ['required', 'in:Ya,Tidak'],
            'diagnosa_klinik'   => ['required', 'string', 'max:255'],
            'lokasi_serologi'   => ['nullable', 'string', 'max:120'],
            'tanggal_serologi'  => ['nullable', 'date'],     // <— penting: harus ada di rules
            'hasil_serologi'    => ['required', 'string'],
            'tanggal_transfusi' => ['required', 'date'],

            // STEP 3 – khusus wanita
            'jumlah_kehamilan'  => ['nullable', 'integer', 'min:0', 'max:99'],
            'abortus'           => ['nullable', 'in:Ya,Tidak'],
            'riwayat_hemolitik' => ['nullable', 'in:Ya,Tidak'],

            // STEP 4 – ringkasan pemesanan
            'produk'            => ['required', 'in:WB,PRC,TC,FFP,CRYO,LP,TCA,CP'],
            'jumlah_kantong'    => ['required', 'integer', 'min:1', 'max:99'],
            'alasan_transfusi'  => ['required', 'string'],

            'alasan_tambahan'      => ['nullable', 'string', 'max:255'],

            'gol_darah'         => ['required', 'in:A,B,AB,O'],
            'rhesus'            => ['required', 'in:Rh+,Rh-'],

            'cek_transfusi'     => ['nullable', 'boolean'],

            // tanggal_pemesanan boleh dikirim/nullable (di-set default di controller bila kosong)
            'tanggal_pemesanan' => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'produk'        => 'Jenis produk darah',
            'gol_darah'     => 'Golongan darah',
            'rhesus'        => 'Rhesus',
            'no_regis_rs'   => 'Nomor registrasi RS',
            'no_rekap_rs'   => 'Nomor rekam medis RS',
            'tanggal_permintaan' => 'Tanggal diperlukan',
            'tanggal_serologi'   => 'Tanggal serologi',
        ];
    }
}
