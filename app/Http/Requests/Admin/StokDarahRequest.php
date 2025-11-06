<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

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
            'produk'          => ['required', 'string', 'in:WB,PRC,TC,TRC,FFP,AHF,LP,TCA,PK'],
            'gol_darah'       => ['required', 'in:A,B,AB,O'],
            'rhesus'          => ['required', 'in:Rh+,Rh-'],
            'jumlah'          => ['required', 'integer', 'min:1'],
            'tgl_masuk'       => ['required', 'date'],
            'tgl_kadaluarsa'  => ['required', 'date', 'after_or_equal:tgl_masuk'],
            // tambahkan field lain kalau ada (lokasi, catatan, dsb.)
        ];
    }

    public function messages(): array
    {
        return [
            'produk.in' => 'Produk harus salah satu dari: WB, PRC, TC, TRC, FFP, AHF, LP, TCA, PK.',
        ];
    }
}
