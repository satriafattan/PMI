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
            'produk'         => ['required','string','max:150'],
            'gol'            => ['required','in:A,AB,B,O'],
            'tgl_masuk'      => ['required','date'],
            'tgl_kadaluarsa' => ['required','date','after:tgl_masuk'],
            'jumlah'         => ['required','integer','min:1','max:100000'],
        ];
    }
}
