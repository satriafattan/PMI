<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePemesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Normalisasi supaya konsisten saat divalidasi & disimpan
    protected function prepareForValidation(): void
    {
        $this->merge([
            'cek_transfusi' => filter_var($this->cek_transfusi, FILTER_VALIDATE_BOOLEAN),
            'produk'        => $this->produk ? strtoupper(trim($this->produk)) : null,
            'gol_darah'     => $this->gol_darah ? strtoupper(trim($this->gol_darah)) : null,
            'rhesus'        => $this->rhesus ? trim($this->rhesus) : null,
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
            'tanggal_diperlukan'=> ['required', 'date'],
            'pernah_serologi'   => ['required', 'in:Ya,Tidak'],
            'diagnosa_klinik'   => ['required', 'string', 'max:255'],
            'lokasi_serologi'   => ['nullable', 'string', 'max:120'],
            'tanggal_serologi'  => ['nullable', 'date'],
            'hasil_serologi'    => ['required', 'string'],
            'tanggal_transfusi' => ['required', 'date'],

            // STEP 3 – khusus wanita
            'jumlah_kehamilan'  => ['nullable', 'integer', 'min:0', 'max:99'],
            'abortus'           => ['nullable', 'in:Ya,Tidak'],
            'riwayat_hemolitik' => ['nullable', 'in:Ya,Tidak'],

            // STEP 4 – ringkasan pemesanan
            'produk'            => ['required', 'in:WB,PRC,TC,FFP,AHF,LP,TCA,PK'],
            'jumlah_kantong'    => ['required', 'integer', 'min:1', 'max:99'],
            'alasan_transfusi'  => ['required', 'string'],

            'alasan_multi'      => ['nullable', 'array'],
            'alasan_multi.*'    => ['in:Plasma Biasa,FFP (Fresh Frozen Plasma)'],

            'gol_darah'         => ['required', 'in:A,B,AB,O'],
            'rhesus'            => ['required', 'in:Rh+,Rh-'],

            'cek_transfusi'     => ['nullable', 'boolean'],
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
        ];
    }
}
