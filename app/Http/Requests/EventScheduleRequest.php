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
            'nomor_telefon'      => ['required', 'numeric', 'digits_between:10,15'],
            'email'              => ['required', 'email', 'max:150'],
            'surat_instansi'     => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],

            // B. Detail Event
            'tanggal_event'      => ['required', 'date', 'after_or_equal:today'],
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
            'nama'               => 'nama',
            'institusi_pemohon'  => 'institusi pemohon',
            'nomor_telefon'      => 'nomor telepon',
            'email'              => 'email',
            'surat_instansi'     => 'surat instansi',
            'tanggal_event'      => 'tanggal event',
            'jam_mulai'          => 'jam mulai',
            'jam_selesai'        => 'jam selesai',
            'jenis_event'        => 'jenis event',
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_telefon.required'  => 'Nomor telepon wajib diisi.',
            'nomor_telefon.numeric'   => 'Nomor telepon hanya boleh berisi angka.',
            'nomor_telefon.digits_between' => 'Nomor telepon harus terdiri dari 10-15 digit.',
            'surat_instansi.required' => 'File surat instansi wajib diunggah.',
            'surat_instansi.mimes'    => 'Format file surat instansi harus berupa PDF, JPG, JPEG, atau PNG.',
            'surat_instansi.max'      => 'Ukuran file surat instansi tidak boleh lebih dari 2 MB.',
            'tanggal_event.required'  => 'Tanggal event wajib diisi.',
            'tanggal_event.after_or_equal' => 'Tanggal event tidak boleh menggunakan tanggal yang sudah lewat.',
            'jam_selesai.after'       => 'Jam selesai harus lebih dari jam mulai.',
        ];
    }
}
