<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EventVerifikasiNotification;
use App\Models\EventSchedule;
use App\Models\EventVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EventVerificationController extends Controller
{
    public function index(Request $r)
    {
        $per = (int) ($r->integer('per') ?? $r->integer('per_page') ?? 10);

        $q  = trim((string) $r->input('q', ''));
        $st = strtolower((string) $r->input('status', '')); // '', pending|approved|rejected

        $query = EventSchedule::query()
            ->with('verifikasiTerakhir')
            ->latest();

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $like = "%{$q}%";
                $w->where('nama', 'like', $like)
                    ->orWhere('institusi_pemohon', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('jenis_event', 'like', $like)
                    ->orWhere('lokasi_lengkap', 'like', $like);
            });
        }

        if (in_array($st, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $st);
        }

        $items = $query->paginate($per)->appends($r->query());

        return view('admin.event-verifikasi.index', [
            'items'    => $items,
            'filters'  => [
                'q'   => $q,
                'status' => $st,
                'per' => $per,
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
                abort(409, 'Event sudah berstatus ' . ucfirst($fresh->status) . ' dan tidak dapat diubah.');
            }

            EventVerification::create([
                'event_schedule_id' => $fresh->id,
                'status'            => $data['status'],
                'catatan'           => $data['catatan'] ?? null,
                'decided_by'        => auth('admin')->id(),
                'decided_at'        => now(),
            ]);

            $fresh->forceFill(['status' => $data['status']])->save();

            // Kirim email notifikasi ke pengaju event
            if ($fresh->email) {
                try {
                    Mail::to($fresh->email)->send(
                        new EventVerifikasiNotification(
                            $fresh,
                            $data['status'],
                            $data['catatan'] ?? null
                        )
                    );
                } catch (\Exception $e) {
                    // Log error tapi tidak menggagalkan transaksi
                    Log::error('Gagal mengirim email verifikasi event', [
                        'event_id' => $fresh->id,
                        'email'    => $fresh->email,
                        'error'    => $e->getMessage(),
                    ]);
                }
            }
        });

        // Pakai id supaya re-binding dari DB (fresh)
        return redirect()
            ->route('admin.event-verifikasi.show', $event->id)
            ->with('success', 'Keputusan verifikasi berhasil disimpan dan notifikasi email telah dikirim.');
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
