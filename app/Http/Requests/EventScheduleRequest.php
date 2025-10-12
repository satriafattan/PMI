<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // izinkan publik submit
        return true;
    }

    public function rules(): array
    {
        return [
            // A. Data Pemohon
            'nama'               => ['required', 'string', 'max:150'],
            'institusi_pemohon'  => ['required', 'string', 'max:150'],
            'nomor_telefon'      => ['required', 'regex:/^[0-9+\s()-]{8,20}$/'],
            'email'              => ['required', 'email', 'max:150'],
            'surat_instansi'     => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],

            // B. Detail Event
            'tanggal_event'      => ['required', 'date'],
            'jam_mulai'          => ['nullable', 'date_format:H:i'],
            'jam_selesai'        => ['nullable', 'date_format:H:i', 'after:jam_mulai'],
            'jenis_event'        => ['required', 'string', 'max:100'],
            'lokasi_lengkap'     => ['nullable', 'string'],

            // C. Estimasi & Kebutuhan
            'jumlah_peserta'     => ['nullable', 'integer', 'min:1'],
            'target_peserta'     => ['nullable', 'string', 'max:100'],
            'butuh_mobil_unit'   => ['nullable', 'boolean'],
            'fasilitas_tersedia' => ['nullable', 'string'],

            // D. Lainnya
            'catatan_tambahan'   => ['nullable', 'string'],
            'izin_publikasi'     => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        // label ramah untuk pesan error
        return [
            'surat_instansi' => 'surat instansi',
            'nomor_telefon'  => 'nomor telepon',
        ];
    }
}
