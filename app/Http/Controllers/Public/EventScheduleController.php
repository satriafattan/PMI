<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventScheduleRequest;
use App\Models\EventSchedule;
use Illuminate\Support\Facades\Log;

class EventScheduleController extends Controller
{
    public function create()
    {
        $eventTypes     = ['Donor Darah', 'Penyuluhan', 'Mobil Unit Kunjungan', 'Bakti Sosial', 'Lainnya'];
        $targetOptions  = ['Mahasiswa', 'Pelajar', 'Karyawan', 'Komunitas', 'Umum'];
        $facilityHints  = ['Ruang tunggu', 'Meja & kursi', 'Akses listrik', 'Area parkir'];

        return view('public.event-form', compact('eventTypes', 'targetOptions', 'facilityHints'));
    }

    public function store(EventScheduleRequest $r)
    {
        // sudah tervalidasi
        $data = $r->validated();

        // normalisasi checkbox
        $data['butuh_mobil_unit'] = (bool)($data['butuh_mobil_unit'] ?? false);
        $data['izin_publikasi']   = (bool)($data['izin_publikasi'] ?? false);

        // PERBAIKAN: Error handling untuk file upload
        try {
            if ($r->hasFile('surat_instansi')) {
                $file = $r->file('surat_instansi');

                // Validasi ulang untuk keamanan
                if ($file->isValid()) {
                    // Generate nama file unik untuk hindari collision
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $data['surat_instansi_path'] = $file->storeAs('surat_instansi', $filename, 'public');
                } else {
                    return back()
                        ->withInput()
                        ->with('error', 'File surat instansi tidak valid. Silakan upload ulang.');
                }
            }

            EventSchedule::create($data);

            return redirect()
                ->route('public.event.create')
                ->with('success', 'Pengajuan penjadwalan berhasil dikirim.');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Event schedule upload error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }
}
