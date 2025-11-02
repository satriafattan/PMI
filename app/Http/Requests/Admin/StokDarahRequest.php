<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StokDarahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'produk'         => ['required', 'string', 'max:191'],
            'gol_darah'      => ['required', 'in:A,AB,B,O'],
            'rhesus'         => ['required', 'in:+,-'],         
            'jumlah'         => ['required', 'integer', 'min:1'],
            'tgl_masuk'      => ['required', 'date'],
            'tgl_kadaluarsa' => ['required', 'date', 'after_or_equal:tgl_masuk'],
        ];
    }

    public function attributes(): array
    {
        return [
            'gol_darah' => 'golongan darah',
            'tgl_kadaluarsa' => 'tanggal kadaluwarsa',
        ];
    }
}
