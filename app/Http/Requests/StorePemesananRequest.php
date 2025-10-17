<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePemesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Pindahkan normalisasi ke sini agar PASTI jalan
    protected function prepareForValidation(): void
    {
        $this->merge([
            'cek_transfusi' => filter_var($this->cek_transfusi, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function rules(): array
    {
        return [
            // STEP 1 – pasien & RS
            'rs_pemesan'        => ['required', 'string', 'max:150'],
            'email'             => ['required', 'email', 'max:150'],      // <- ganti string -> email
            'nomor_telepon'     => ['required', 'string', 'max:30'],      // <- WAJIB agar tak hilang saat insert
            'jenis_kelamin'     => ['required', 'in:L,P'],
            'no_regis_rs'       => ['required', 'string', 'max:100'],
            'nama_suami_istri'  => ['nullable', 'string', 'max:150'],
            'nama_dokter'       => ['required', 'string', 'max:150'],
            'nama_pasien'       => ['required', 'string', 'max:150'],     // (boleh tetap required meski bukan kolom DB)

            // STEP 2 – data pemesanan
            'tanggal_diperlukan' => ['required', 'date'],                  // akan dipakai sbg tanggal_permintaan
            'pernah_serologi'   => ['required', 'in:Ya,Tidak'],
            'diagnosa_klinik'   => ['required', 'string', 'max:255'],
            'lokasi_serologi'   => ['nullable', 'string', 'max:120'],      // <- nullable
            'tanggal_serologi'  => ['nullable', 'date'],                  // <- nullable
            'hasil_serologi'    => ['required', 'string'],
            'tanggal_transfusi' => ['required', 'date'],

            // STEP 3 – wanita (opsional)
            'jumlah_kehamilan'  => ['nullable', 'integer', 'min:0', 'max:99'],
            'abortus'           => ['nullable', 'in:Ya,Tidak'],
            'riwayat_hemolitik' => ['nullable', 'in:Ya,Tidak'],

            // STEP 4 – ringkasan pemesanan
            'produk_multi'      => ['required', 'array', 'min:1'],
            'produk_multi.*'    => ['in:Segar,Biasa,Cuci'],
            'jumlah_kantong'    => ['required', 'integer', 'min:1', 'max:99'],
            'alasan_transfusi'  => ['required', 'string'],
            'alasan_multi'      => ['nullable', 'array'],
            'alasan_multi.*'    => ['in:Plasma Biasa,FFP (Fresh Frozen Plasma)'],

            // kolom lama – jika masih dipakai
            'gol_darah'         => ['nullable', 'in:A,B,AB,O'],
            'rhesus'            => ['nullable', 'in:+,-'],
            'cek_transfusi'     => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'no_regis_rs' => 'Nomor registrasi RS',
            'no_rekap_rs' => 'Nomor rekam medis RS',
        ];
    }
}
