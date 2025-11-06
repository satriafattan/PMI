@extends('layouts.admin')
@section('title', 'Verifikasi Event')

@section('content')
  <div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold md:text-3xl">Verifikasi Event</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola pengajuan jadwal event dari publik</p>
      </div>
    </div>

    {{-- Toolbar ala "Riwayat Pemesanan" --}}
    <form id="toolbar"
          method="GET"
          class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center">
      {{-- Search --}}
      <div class="relative flex-1">
        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
          <svg class="size-5 text-neutral-400"
               viewBox="0 0 24 24"
               fill="none"
               stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="1.6"
                  d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" />
          </svg>
        </span>
        <input type="text"
               name="q"
               value="{{ $filters['q'] ?? '' }}"
               placeholder="Cari nama, institusi, email, jenis, lokasi..."
               class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-900/10" />
      </div>

      <div class="flex items-center gap-3">
        {{-- Tombol Filter (popover) --}}
        <div class="relative">
          <button type="button"
                  id="filterBtn"
                  class="inline-flex items-center justify-center rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50">
            <svg class="size-5 text-neutral-600"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.6"
                    d="M3 6h18M6 12h12M10 18h4" />
            </svg>
          </button>

          {{-- Menu Filter --}}
          <div id="filterMenu"
               class="absolute right-0 z-20 mt-2 hidden w-64 rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
            <div class="space-y-3">
              <div>
                <label class="text-xs font-medium text-neutral-500">Status</label>
                <select name="status"
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                  @php $st = $filters['status'] ?? ''; @endphp
                  <option value="">Semua</option>
                  <option value="approved"
                          @selected($st === 'approved')>Approved</option>
                  <option value="pending"
                          @selected($st === 'pending')>Pending</option>
                  <option value="rejected"
                          @selected($st === 'rejected')>Rejected</option>
                </select>
              </div>

              <div class="flex items-center justify-between">
                <a href="{{ route('admin.event-verifikasi.index') }}"
                   class="text-sm text-neutral-600 hover:underline">Reset</a>
                <button type="submit"
                        class="rounded-lg bg-neutral-900 px-3 py-1.5 text-sm text-white hover:bg-neutral-800">
                  Terapkan
                </button>
              </div>
            </div>
          </div>
        </div>

        {{-- Baris (per page) --}}
        <div class="flex items-center gap-2">
          <label for="per"
                 class="text-sm text-neutral-600">Baris:</label>
          <select id="per"
                  name="per"
                  class="rounded-xl border border-neutral-200 bg-white px-2 py-2 text-sm">
            @foreach ([5, 10, 12, 15, 20, 30] as $n)
              <option value="{{ $n }}"
                      @selected(($filters['per'] ?? 10) == $n)>{{ $n }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-2xl border border-neutral-200 bg-white shadow-sm">
      <table class="min-w-full">
        <thead class="text-left text-xs uppercase tracking-wider text-neutral-500">
          <tr class="border-b border-neutral-100">
            <th class="px-4 py-3">Pemohon</th>
            <th class="px-4 py-3">Institusi</th>
            <th class="px-4 py-3">Jenis & Tanggal</th>
            <th class="px-4 py-3">Lokasi</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 text-sm">
          @forelse ($items as $ev)
            <tr class="hover:bg-neutral-50/50">
              <td class="px-4 py-3">
                <div class="font-medium text-neutral-800">{{ $ev->nama }}</div>
                <div class="text-xs text-neutral-500">{{ $ev->email }} • {{ $ev->nomor_telefon }}</div>
              </td>
              <td class="px-4 py-3">{{ $ev->institusi_pemohon }}</td>
              <td class="px-4 py-3">
                <div class="font-medium">{{ $ev->jenis_event }}</div>
                <div class="text-xs text-neutral-500">
                  {{ \Illuminate\Support\Carbon::parse($ev->tanggal_event)->format('d M Y') }}
                  @if ($ev->jam_mulai)
                    • {{ \Illuminate\Support\Str::of($ev->jam_mulai)->substr(0, 5) }}
                  @endif
                </div>
              </td>
              <td class="px-4 py-3">
                <div class="line-clamp-2 max-w-[320px]">{{ $ev->lokasi_lengkap }}</div>
              </td>
              <td class="px-4 py-3">
                @php
                  $badge =
                      [
                          'pending' => 'bg-amber-100 text-amber-700',
                          'approved' => 'bg-emerald-100 text-emerald-700',
                          'rejected' => 'bg-rose-100 text-rose-700',
                      ][$ev->status] ?? 'bg-neutral-100 text-neutral-700';
                @endphp
                <span class="{{ $badge }} inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium">
                  {{ ucfirst($ev->status) }}
                </span>
              </td>
              <td class="px-4 py-3 text-right">
                <a href="{{ route('admin.event-verifikasi.show', $ev) }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 px-3 py-1.5 text-sm hover:bg-neutral-50">
                  Detail
                  <svg class="size-4"
                       viewBox="0 0 24 24"
                       fill="none"
                       stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M9 5l7 7-7 7" />
                  </svg>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6"
                  class="px-4 py-6 text-center text-neutral-500">Belum ada pengajuan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div>
      {{ $items->links() }}
    </div>
  </div>
  {{-- Script kecil untuk popover & autosubmit Baris --}}
  <script>
    (function() {
      const btn = document.getElementById('filterBtn');
      const menu = document.getElementById('filterMenu');
      const per = document.getElementById('per');
      const form = document.getElementById('toolbar');

      btn.addEventListener('click', () => menu.classList.toggle('hidden'));
      document.addEventListener('click', (e) => {
        if (!btn.contains(e.target) && !menu.contains(e.target)) menu.classList.add('hidden');
      });

      per.addEventListener('change', () => form.submit());
    })();
  </script>
@endsection
