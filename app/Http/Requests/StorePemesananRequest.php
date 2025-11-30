<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StorePemesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $fixDate = function ($v) {
            if ($v === null || $v === '') return null;
            $ts = strtotime($v);
            return $ts ? date('Y-m-d', $ts) : null;
        };

        $tanggal_permintaan = $this->input('tanggal_permintaan') ?: $this->input('tanggal_diperlukan');

        // Normalisasi rhesus: konversi '+' atau '-' menjadi 'Rh+' atau 'Rh-'
        $rhesus = $this->rhesus ? trim($this->rhesus) : null;
        if ($rhesus === '+') {
            $rhesus = 'Rh+';
        } elseif ($rhesus === '-') {
            $rhesus = 'Rh-';
        }

        $this->merge([
            'cek_transfusi'      => filter_var($this->cek_transfusi, FILTER_VALIDATE_BOOLEAN),
            'produk'             => $this->produk ? strtoupper(trim($this->produk)) : null,
            'gol_darah'          => $this->gol_darah ? strtoupper(trim($this->gol_darah)) : null,
            'rhesus'             => $rhesus,
            'tanggal_pemesanan'  => $fixDate($this->input('tanggal_pemesanan')),
            'tanggal_permintaan' => $fixDate($tanggal_permintaan),
            'tanggal_serologi'   => $fixDate($this->input('tanggal_serologi')),
            'tanggal_transfusi'  => $fixDate($this->input('tanggal_transfusi')),
        ]);
    }

    public function rules(): array
    {
        return [
            'website'           => ['nullable', 'max:0'],
            'form_token'        => ['nullable', 'string'],
            'rs_pemesan'        => ['required', 'string', 'max:150'],
            'email'             => ['required', 'email', 'max:150'],
            'nomor_telepon'     => ['required', 'string', 'max:30'],
            'jenis_kelamin'     => ['required', 'in:L,P'],
            'no_regis_rs'       => ['required', 'string', 'max:100'],
            'nama_suami_istri'  => ['nullable', 'string', 'max:150'],
            'nama_dokter'       => ['required', 'string', 'max:150'],
            'nama_pasien'       => ['required', 'string', 'max:150'],
            'tanggal_diperlukan' => ['required', 'date', 'after_or_equal:today'],
            'pernah_serologi'   => ['required', 'in:Ya,Tidak'],
            'diagnosa_klinik'   => ['required', 'string', 'max:255'],
            'lokasi_serologi'   => ['nullable', 'string', 'max:120'],
            'tanggal_serologi'  => ['nullable', 'date'],
            'hasil_serologi'    => ['required', 'string'],
            'tanggal_transfusi' => ['required', 'date', 'after_or_equal:today'],
            'jumlah_kehamilan'  => ['nullable', 'integer', 'min:0', 'max:99'],
            'abortus'           => ['nullable', 'in:Ya,Tidak'],
            'riwayat_hemolitik' => ['nullable', 'in:Ya,Tidak'],
            'produk'            => ['required', 'in:WB,PRC,TC,FFP,CRYO,LP,TCA,CP'],
            'jumlah_kantong'    => ['required', 'integer', 'min:1', 'max:99'],
            'alasan_transfusi'  => ['required', 'string'],
            'alasan_tambahan'      => ['nullable', 'string', 'max:255'],
            'gol_darah'         => ['required', 'in:A,B,AB,O'],
            'rhesus'            => ['required', 'in:Rh+,Rh-'],
            'cek_transfusi'     => ['nullable', 'boolean'],
            'tanggal_pemesanan' => ['nullable', 'date'],
            'tanggal_permintaan' => ['nullable', 'date'], // mapped dari tanggal_diperlukan
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
            'tanggal_diperlukan' => 'Tanggal diperlukan',
            'tanggal_transfusi'  => 'Tanggal transfusi',
            'tanggal_serologi'   => 'Tanggal serologi',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_diperlukan.after_or_equal' => 'Tanggal diperlukan tidak boleh kurang dari hari ini.',
            'tanggal_transfusi.after_or_equal' => 'Tanggal transfusi tidak boleh kurang dari hari ini.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // 1. Honeypot check - field 'website' harus kosong
            if (!empty($this->input('website'))) {
                $validator->errors()->add('spam', 'Terdeteksi aktivitas mencurigakan. Silakan coba lagi.');
                return;
            }

            // 2. Time-based protection - minimal 3 detik untuk isi form
            $formToken = $this->input('form_token');
            if ($formToken) {
                try {
                    $timestamp = base64_decode($formToken);

                    // Validasi timestamp adalah angka valid
                    if (is_numeric($timestamp)) {
                        $elapsed = time() - (int)$timestamp;

                        // Minimal 2 detik (lebih toleran)
                        if ($elapsed < 2) {
                            $validator->errors()->add('spam', 'Mohon tunggu sebentar sebelum mengirim formulir.');
                            return;
                        }

                        // Maksimal 2 jam (7200 detik) - lebih toleran untuk user yang berpikir lama
                        if ($elapsed > 7200) {
                            $validator->errors()->add('spam', 'Sesi formulir telah berakhir. Silakan refresh halaman.');
                            return;
                        }
                    }
                } catch (\Exception $e) {
                    // Jika decode gagal, skip validasi time-based
                    // Log error tapi tidak block user
                    Log::warning('Invalid form token: ' . $e->getMessage());
                }
            }
        });
    }
}
