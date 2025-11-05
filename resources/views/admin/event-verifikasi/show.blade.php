@extends('layouts.admin')
@section('title', 'Detail Verifikasi Event')

@section('content')
  @if (session('success'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
      {{ session('success') }}
    </div>
  @endif

  <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
    {{-- ================== KIRI: DETAIL ================== --}}
    <div class="space-y-6">

      {{-- Header --}}
      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-xl font-semibold text-slate-900">
              {{ $event->jenis_event }} — {{ \Illuminate\Support\Carbon::parse($event->tanggal_event)->format('d M Y') }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
              Pengaju: <span class="font-medium text-slate-700">{{ $event->nama }}</span>
              ({{ $event->institusi_pemohon }})
            </p>
          </div>
          @php
            $badge = [
              'pending'  => 'bg-amber-100 text-amber-700',
              'approved' => 'bg-emerald-100 text-emerald-700',
              'rejected' => 'bg-rose-100 text-rose-700',
            ][$event->status] ?? 'bg-neutral-100 text-neutral-700';
          @endphp
          <span class="{{ $badge }} inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">
            {{ ucfirst($event->status) }}
          </span>
        </div>
      </div>

      {{-- A. Data Pemohon --}}
      <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center gap-3">
          <div class="grid h-9 w-9 place-items-center rounded-xl bg-slate-200 text-slate-800 shadow ring-4 ring-white">A</div>
          <h2 class="text-lg font-semibold text-slate-900">Data Pemohon</h2>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <div class="text-xs text-slate-500">Nama</div>
            <div class="font-medium text-slate-800">{{ $event->nama }}</div>
          </div>
          <div>
            <div class="text-xs text-slate-500">Institusi</div>
            <div class="font-medium text-slate-800">{{ $event->institusi_pemohon }}</div>
          </div>
          <div>
            <div class="text-xs text-slate-500">Nomor Telepon</div>
            <div class="font-medium text-slate-800">{{ $event->nomor_telefon }}</div>
          </div>
          <div>
            <div class="text-xs text-slate-500">Email</div>
            <div class="font-medium text-slate-800">{{ $event->email }}</div>
          </div>
          <div class="sm:col-span-2">
            <div class="text-xs text-slate-500">Surat Instansi</div>
            @if ($event->surat_instansi_path)
              <a href="{{ route('admin.event-verifikasi.surat', $event) }}"
                 class="inline-flex items-center gap-2 text-sm text-red-600 hover:underline">
                Download surat (lampiran)
              </a>
            @else
              <div class="text-sm text-slate-500">Tidak ada lampiran</div>
            @endif
          </div>
        </div>
      </section>

      {{-- B. Detail Event --}}
      <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center gap-3">
          <div class="grid h-9 w-9 place-items-center rounded-xl bg-slate-200 text-slate-800 shadow ring-4 ring-white">B</div>
          <h2 class="text-lg font-semibold text-slate-900">Detail Event</h2>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <div class="text-xs text-slate-500">Tanggal</div>
            <div class="font-medium text-slate-800">
              {{ \Illuminate\Support\Carbon::parse($event->tanggal_event)->format('d M Y') }}
            </div>
          </div>
          <div>
            <div class="text-xs text-slate-500">Jenis</div>
            <div class="font-medium text-slate-800">{{ $event->jenis_event }}</div>
          </div>
          <div>
            <div class="text-xs text-slate-500">Jam Mulai</div>
            <div class="font-medium text-slate-800">
              {{ $event->jam_mulai ? \Illuminate\Support\Str::of($event->jam_mulai)->substr(0,5) : '-' }}
            </div>
          </div>
          <div>
            <div class="text-xs text-slate-500">Jam Selesai</div>
            <div class="font-medium text-slate-800">
              {{ $event->jam_selesai ? \Illuminate\Support\Str::of($event->jam_selesai)->substr(0,5) : '-' }}
            </div>
          </div>
          <div class="sm:col-span-2">
            <div class="text-xs text-slate-500">Lokasi</div>
            <div class="font-medium text-slate-800">{{ $event->lokasi_lengkap ?: '-' }}</div>
          </div>
        </div>
      </section>

      {{-- C. Estimasi & Kebutuhan --}}
      <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center gap-3">
          <div class="grid h-9 w-9 place-items-center rounded-xl bg-slate-200 text-slate-800 shadow ring-4 ring-white">C</div>
          <h2 class="text-lg font-semibold text-slate-900">Estimasi & Kebutuhan</h2>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <div class="text-xs text-slate-500">Jumlah Peserta</div>
            <div class="font-medium text-slate-800">{{ $event->jumlah_peserta ?: '-' }}</div>
          </div>
          <div>
            <div class="text-xs text-slate-500">Target Peserta</div>
            <div class="font-medium text-slate-800">{{ $event->target_peserta ?: '-' }}</div>
          </div>
          <div>
            <div class="text-xs text-slate-500">Butuh Mobil Unit</div>
            <div class="font-medium text-slate-800">{{ $event->butuh_mobil_unit ? 'Ya' : 'Tidak' }}</div>
          </div>
          <div class="sm:col-span-2">
            <div class="text-xs text-slate-500">Fasilitas Tersedia</div>
            <div class="whitespace-pre-line font-medium text-slate-800">{{ $event->fasilitas_tersedia ?: '-' }}</div>
          </div>
        </div>
      </section>

      {{-- D. Lainnya --}}
      <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center gap-3">
          <div class="grid h-9 w-9 place-items-center rounded-xl bg-slate-200 text-slate-800 shadow ring-4 ring-white">D</div>
          <h2 class="text-lg font-semibold text-slate-900">Lainnya</h2>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div class="sm:col-span-2">
            <div class="text-xs text-slate-500">Catatan Tambahan</div>
            <div class="whitespace-pre-line font-medium text-slate-800">{{ $event->catatan_tambahan ?: '-' }}</div>
          </div>
          <div>
            <div class="text-xs text-slate-500">Izin Publikasi</div>
            <div class="font-medium text-slate-800">{{ $event->izin_publikasi ? 'Diizinkan' : 'Tidak diizinkan' }}</div>
          </div>
        </div>
      </section>

      {{-- Riwayat Verifikasi --}}
      <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
          <h2 class="text-lg font-semibold text-slate-900">Riwayat Verifikasi</h2>
        </div>
        <div class="space-y-3">
          @forelse($event->verifikasi as $v)
            <div class="rounded-xl border border-slate-200 p-4">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  @php
                    $b = [
                      'pending'  => 'bg-amber-100 text-amber-700',
                      'approved' => 'bg-emerald-100 text-emerald-700',
                      'rejected' => 'bg-rose-100 text-rose-700',
                    ][$v->status] ?? 'bg-neutral-100 text-neutral-700';
                  @endphp
                  <span class="{{ $b }} inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium">
                    {{ ucfirst($v->status) }}
                  </span>
                  <span class="text-xs text-slate-500">
                    {{ $v->decided_at ? $v->decided_at->format('d M Y H:i') : $v->created_at->format('d M Y H:i') }}
                  </span>
                </div>
                @if ($v->petugas)
                  <div class="text-xs text-slate-500">Oleh: {{ $v->petugas->name }}</div>
                @endif
              </div>
              @if ($v->catatan)
                <div class="mt-2 whitespace-pre-line text-sm text-slate-700">{{ $v->catatan }}</div>
              @endif
            </div>
          @empty
            <div class="text-sm text-slate-500">Belum ada riwayat.</div>
          @endforelse
        </div>
      </section>

    </div>

    {{-- ... konten detail di kiri tetap ... --}}

{{-- ================== KANAN: KEPUTUSAN & RINGKAS ================== --}}
<aside class="space-y-4">

  {{-- Keputusan --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <h3 class="text-base font-semibold text-slate-900">Ambil Keputusan</h3>
    <p class="mt-1 text-xs text-slate-500">Set status menjadi Approved/Rejected beserta catatan.</p>

    @if ($event->status !== 'pending')
      <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm text-slate-600">
        Event sudah berstatus <span class="font-medium">{{ ucfirst($event->status) }}</span>. Keputusan tidak dapat diubah.
      </div>
      <div class="mt-3 grid grid-cols-2 gap-2">
        <button class="rounded-xl border border-slate-200 bg-slate-100 px-4 py-2 text-sm text-slate-500 cursor-not-allowed" disabled>Tolak</button>
        <button class="rounded-xl border border-slate-200 bg-slate-100 px-4 py-2 text-sm text-slate-500 cursor-not-allowed" disabled>Setujui</button>
      </div>
    @else
      <form id="decideForm"
            method="POST"
            action="{{ route('admin.event-verifikasi.decide', $event->id) }}"
            class="mt-4 space-y-3">
        @csrf

        {{-- status akan diisi via JS saat tombol diklik --}}
        <input type="hidden" name="status" id="statusField" value="">

        <div>
          <label class="text-xs font-medium text-slate-600">Catatan</label>
          <textarea name="catatan" rows="4"
                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-inner focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                    placeholder="Alasan/ketentuan/arah tindak lanjut (opsional)">{{ old('catatan') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-2">
          <button type="button"
                  class="decide-btn rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 hover:bg-rose-100"
                  data-status="rejected">
            Tolak
          </button>
          <button type="button"
                  class="decide-btn rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-100"
                  data-status="approved">
            Setujui
          </button>
        </div>
      </form>
    @endif
  </div>
  
  {{-- Ringkas --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <h3 class="text-base font-semibold text-slate-900">Ringkas</h3>
    <ul class="mt-2 space-y-1 text-sm">
      <li><span class="text-slate-500">Pemohon:</span> <span class="font-medium">{{ $event->nama }}</span></li>
      <li><span class="text-slate-500">Institusi:</span> <span class="font-medium">{{ $event->institusi_pemohon }}</span></li>
      <li><span class="text-slate-500">Jenis:</span> <span class="font-medium">{{ $event->jenis_event }}</span></li>
      <li><span class="text-slate-500">Tanggal:</span> <span class="font-medium">{{ \Illuminate\Support\Carbon::parse($event->tanggal_event)->format('d M Y') }}</span></li>
      <li><span class="text-slate-500">Status:</span> <span class="font-medium">{{ ucfirst($event->status) }}</span></li>
    </ul>
  </div>

</aside>
  {{-- anti double-submit --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
  const form   = document.getElementById('decideForm');
  if (!form) return; // ketika status sudah bukan pending, form tidak ada

  const field  = document.getElementById('statusField');
  const btns   = form.querySelectorAll('.decide-btn');

  btns.forEach(btn => {
    btn.addEventListener('click', () => {
      // set status dari data-status
      field.value = btn.dataset.status;

      // disable kedua tombol (anti double click)
      btns.forEach(b => {
        b.disabled = true;
        b.classList.add('opacity-60', 'cursor-not-allowed');
      });

      // submitkan form
      form.submit();
    }, { once: true });
  });
});
  </script>
@endsection
