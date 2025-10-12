<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventScheduleRequest;
use App\Models\EventSchedule;

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

        // simpan file
        if ($r->hasFile('surat_instansi')) {
            $data['surat_instansi_path'] = $r->file('surat_instansi')->store('surat_instansi', 'public');
        }

        EventSchedule::create($data);

        return redirect()
            ->route('public.event.create')
            ->with('success', 'Pengajuan penjadwalan berhasil dikirim.');
    }
}
