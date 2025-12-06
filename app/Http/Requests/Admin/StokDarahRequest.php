<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class StokDarahRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Atur sesuai kebutuhan auth admin
        return true;
    }

    public function rules(): array
    {
        return [
            'produk'          => ['required', 'string', 'in:WB,PRC,TC,FFP,CRYO,LP,TCA,CP'],
            'gol_darah'       => ['required', 'in:A,B,AB,O'],
            'rhesus'          => ['required', 'in:Rh+,Rh-'],
            'jumlah'          => ['required', 'integer', 'min:1'],
            'tgl_masuk'       => ['required', 'date'],
            'tgl_kadaluarsa'  => [
                'required',
                'date',
                'after_or_equal:tgl_masuk',
                function ($attribute, $value, $fail) {
                    $this->validateShelfLife($attribute, $value, $fail);
                }
            ],
            // tambahkan field lain kalau ada (lokasi, catatan, dsb.)
        ];
    }

    public function messages(): array
    {
        return [
            'produk.in' => 'Produk harus salah satu dari: WB, PRC, TC, FFP, CRYO, LP, TCA, CP.',
        ];
    }

    /**
     * Validasi masa simpan berdasarkan jenis produk
     */
    protected function validateShelfLife($attribute, $value, $fail)
    {
        // Masa simpan per produk (dalam hari)
        $shelfLife = [
            'WB' => 35,     // Whole Blood
            'PRC' => 42,    // Packed Red Cells
            'TC' => 5,      // Thrombocyte Concentrate
            'FFP' => 365,   // Fresh Frozen Plasma
            'CRYO' => 365,  // Cryoprecipitated Anti-Hemophilic Factor
            'LP' => 365,    // Liquid Plasma
            'TCA' => 5,     // Thrombocyte Apheresis
            'CP' => 365,    // Convalescent Plasma
        ];

        $produk = $this->input('produk');
        $tglMasuk = $this->input('tgl_masuk');

        if (!$produk || !$tglMasuk) {
            return; // Skip jika produk atau tgl_masuk belum diisi
        }

        $maxDays = $shelfLife[$produk] ?? 30;

        $masuk = Carbon::parse($tglMasuk);
        $kadaluwarsa = Carbon::parse($value);
        $diffDays = $masuk->diffInDays($kadaluwarsa);

        if ($diffDays > $maxDays) {
            $produkName = $this->getProductName($produk);
            $fail("Masa simpan {$produkName} maksimal {$maxDays} hari dari tanggal masuk.");
        }
    }

    /**
     * Dapatkan nama produk untuk pesan error
     */
    protected function getProductName($code)
    {
        $names = [
            'WB' => 'Whole Blood',
            'PRC' => 'Packed Red Cells',
            'TC' => 'Thrombocyte Concentrate',
            'FFP' => 'Fresh Frozen Plasma',
            'CRYO' => 'Cryoprecipitated Anti-Hemophilic Factor',
            'LP' => 'Liquid Plasma',
            'TCA' => 'Thrombocyte Apheresis',
            'CP' => 'Convalescent Plasma',
        ];

        return $names[$code] ?? $code;
    }
}
