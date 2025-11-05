<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventSchedule;
use App\Models\EventVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventVerificationController extends Controller
{
    public function index(Request $r)
    {
        $per = (int) $r->input('per_page', 12);
        $q   = $r->input('q');
        $st  = $r->input('status');  // pending|approved|rejected|all
        $tgl = $r->input('tanggal'); // YYYY-MM-DD

        $query = EventSchedule::query()
            ->with('verifikasiTerakhir')
            ->latest();

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('institusi_pemohon', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('jenis_event', 'like', "%{$q}%")
                  ->orWhere('lokasi_lengkap', 'like', "%{$q}%");
            });
        }

        if ($st && $st !== 'all') {
            $query->where('status', $st);
        }

        if ($tgl) {
            $query->whereDate('tanggal_event', $tgl);
        }

        $items = $query->paginate($per)->appends($r->query());

        return view('admin.event-verifikasi.index', [
            'items'   => $items,
            'filters' => [
                'q'        => $q,
                'status'   => $st,
                'tanggal'  => $tgl,
                'per_page' => $per,
            ],
        ]);
    }

    public function show(EventSchedule $event)
    {
        $event->load([
            'verifikasi' => fn($q) => $q->latest(),
            'verifikasiTerakhir',
        ]);

        return view('admin.event-verifikasi.show', compact('event'));
    }

    public function decide(Request $r, EventSchedule $event)
    {
        $data = $r->validate([
            'status'  => ['required', 'in:approved,rejected'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($event, $data) {
            // Ambil & kunci baris untuk cegah race condition
            $fresh = EventSchedule::whereKey($event->id)->lockForUpdate()->first();

            if ($fresh->status !== 'pending') {
                abort(409, 'Event sudah berstatus '.ucfirst($fresh->status).' dan tidak dapat diubah.');
            }

            EventVerification::create([
                'event_schedule_id' => $fresh->id,
                'status'            => $data['status'],
                'catatan'           => $data['catatan'] ?? null,
                'decided_by'        => auth('admin')->id(),
                'decided_at'        => now(),
            ]);

            $fresh->forceFill(['status' => $data['status']])->save();
        });

        // Pakai id supaya re-binding dari DB (fresh)
        return redirect()
            ->route('admin.event-verifikasi.show', $event->id)
            ->with('success', 'Keputusan verifikasi berhasil disimpan.');
    }

    public function downloadSurat(EventSchedule $event)
    {
        $path = $event->surat_instansi_path;
        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->download(
            Storage::disk('public')->path($path),
            basename($path)
        );
    }
}