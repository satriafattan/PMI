<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\EventSchedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventScheduleController extends Controller
{
    private array $eventTypes = [
        'Donor Darah',
        'Penyuluhan Donor',
        'Mobil Unit Kunjungan',
        'Bakti Sosial',
        'Lainnya',
    ];

    private array $targetOptions = [
        'Mahasiswa',
        'Karyawan',
        'Masyarakat Umum',
        'Komunitas',
        'Lainnya'
    ];

    private array $facilityHints = [
        'Ruang ber-AC/kipas',
        'Meja & kursi',
        'Area parkir',
        'Listrik/stop kontak',
        'Tenda'
    ];

    public function create()
    {
        $eventTypes = $this->eventTypes;
        $targetOptions = $this->targetOptions;
        $facilityHints = $this->facilityHints;
        return view('public.event-form', compact('eventTypes', 'targetOptions', 'facilityHints'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // A. Data Pemohon
            'nama'              => 'required|string|max:150',
            'institusi_pemohon' => 'required|string|max:150',
            'nomor_telefon'     => 'required|string|max:30',
            'email'             => 'required|email',

            // B. Detail Event
            'tanggal_event'     => 'required|date|after_or_equal:today',
            'jam_mulai'         => 'nullable|date_format:H:i',
            'jam_selesai'       => 'nullable|date_format:H:i|after:jam_mulai',
            'jenis_event'       => ['required', 'string', Rule::in($this->eventTypes)],
            'lokasi_lengkap'    => 'nullable|string|max:1000',

            // C. Estimasi & Kebutuhan
            'jumlah_peserta'    => 'nullable|integer|min:1|max:10000',
            'target_peserta'    => ['nullable', 'string'],
            'butuh_mobil_unit'  => 'nullable|boolean',
            'fasilitas_tersedia' => 'nullable|string|max:1000',

            // D. Lainnya
            'catatan_tambahan'  => 'nullable|string|max:1500',
            'izin_publikasi'    => 'nullable|boolean',
        ], [
            'tanggal_event.after_or_equal' => 'Tanggal event tidak boleh di masa lalu.',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        // normalisasi checkbox (karena unchecked tidak ikut terkirim)
        $data['butuh_mobil_unit'] = (bool) ($request->boolean('butuh_mobil_unit'));
        $data['izin_publikasi']   = (bool) ($request->boolean('izin_publikasi'));

        $schedule = EventSchedule::create($data + ['status' => 'pending']);

        return view('public.event-success', compact('schedule'));
    }
}
