@extends('layouts.admin')
@section('title','Verifikasi Event')

@section('content')
<div class="space-y-6">

  {{-- Header --}}
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl md:text-3xl font-semibold">Verifikasi Event</h1>
      <p class="text-sm text-slate-500 mt-1">Kelola pengajuan jadwal event dari publik</p>
    </div>
  </div>

  {{-- Filter Bar (mirip verifikasi pemesanan) --}}
  <form method="GET" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
    <div class="relative">
      <input type="text" name="q" value="{{ $filters['q'] ?? '' }}"
             placeholder="Cari nama, institusi, email, jenis, lokasi..."
             class="w-full rounded-xl border border-neutral-200 bg-white px-10 py-2.5 text-sm shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-500/20"/>
      <span class="absolute left-3 top-2.5 text-neutral-400">
        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/>
        </svg>
      </span>
    </div>

    <div>
      <select name="status"
              class="w-full rounded-xl border border-neutral-200 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
        @php $st = $filters['status'] ?? 'all'; @endphp
        <option value="all" {{ $st==='all' ? 'selected' : '' }}>Semua Status</option>
        <option value="pending" {{ $st==='pending' ? 'selected' : '' }}>Pending</option>
        <option value="approved" {{ $st==='approved' ? 'selected' : '' }}>Approved</option>
        <option value="rejected" {{ $st==='rejected' ? 'selected' : '' }}>Rejected</option>
      </select>
    </div>

    <div>
      <input type="date" name="tanggal" value="{{ $filters['tanggal'] ?? '' }}"
             class="w-full rounded-xl border border-neutral-200 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-500/20"/>
    </div>

    <div class="flex gap-2">
      <select name="per_page"
              class="w-full rounded-xl border border-neutral-200 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
        @php $per = (int)($filters['per_page'] ?? 12); @endphp
        @foreach([10,12,15,20,30] as $x)
          <option value="{{ $x }}" @selected($per===$x)>{{ $x }} / halaman</option>
        @endforeach
      </select>
      <button class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700 shadow-sm">Terapkan</button>
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
              <div class="text-xs text-neutral-500">{{ \Illuminate\Support\Carbon::parse($ev->tanggal_event)->format('d M Y') }}
                @if($ev->jam_mulai) • {{ \Illuminate\Support\Str::of($ev->jam_mulai)->substr(0,5) }} @endif
              </div>
            </td>
            <td class="px-4 py-3">
              <div class="line-clamp-2 max-w-[320px]">{{ $ev->lokasi_lengkap }}</div>
            </td>
            <td class="px-4 py-3">
              @php
                $badge = [
                  'pending' => 'bg-amber-100 text-amber-700',
                  'approved' => 'bg-emerald-100 text-emerald-700',
                  'rejected' => 'bg-rose-100 text-rose-700',
                ][$ev->status] ?? 'bg-neutral-100 text-neutral-700';
              @endphp
              <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $badge }}">
                {{ ucfirst($ev->status) }}
              </span>
            </td>
            <td class="px-4 py-3 text-right">
              <a href="{{ route('admin.event-verifikasi.show', $ev) }}"
                 class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 px-3 py-1.5 text-sm hover:bg-neutral-50">
                Detail
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7"/>
                </svg>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-4 py-6 text-center text-neutral-500">Belum ada pengajuan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div>
    {{ $items->links() }}
  </div>
</div>
@endsection
