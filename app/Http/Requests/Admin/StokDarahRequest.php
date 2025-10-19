<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StokDarahRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // pastikan middleware/auth kamu sudah membatasi admin;
        return true;
    }

    public function rules(): array
    {
        return [
            'produk'         => ['required', 'string', 'max:255'],
            'gol_darah'      => ['required', 'in:A,AB,B,O'],
            'jumlah'         => ['required', 'integer', 'min:1'],
            'tgl_masuk'      => ['required', 'date'],
            'tgl_kadaluarsa' => ['required', 'date', 'after_or_equal:tgl_masuk'],
        ];
    }

    public function attributes(): array
    {
        return [
            'gol_darah'      => 'Golongan darah',
            'tgl_masuk'      => 'Tanggal masuk',
            'tgl_kadaluarsa' => 'Tanggal kadaluwarsa',
        ];
    }
}
