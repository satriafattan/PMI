@extends('layouts.admin')
@section('title','Laporan Pemesanan Darah')

@section('content')
<div class="space-y-6">

  {{-- ===================== Header & Toolbar ===================== --}}
  <div class="flex items-center justify-between">
    <h1 class="text-2xl md:text-3xl font-semibold">Laporan Pemesanan Darah</h1>
  </div>

  <form method="GET" class="flex flex-col gap-3 md:flex-row md:items-stretch">
    {{-- Search --}}
    <div class="relative flex-1">
      <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
        <svg class="size-5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/>
        </svg>
      </span>
      <input name="q" value="{{ $filters['q'] ?? '' }}"
             class="w-full rounded-xl border border-neutral-200 bg-white pl-11 pr-3 py-2.5 text-sm placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/10 focus:border-neutral-300"
             placeholder="Cari nama pasien atau rumah sakit..." />
    </div>

    {{-- Filter dropdown --}}
    <div class="relative">
      <button type="button" id="filterBtn"
              class="inline-flex items-center justify-center rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50">
        <svg class="size-5 text-neutral-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 6h18M6 12h12M10 18h4"/>
        </svg>
      </button>
      <div id="filterMenu"
           class="hidden absolute right-0 z-20 mt-2 w-[320px] rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-xs font-medium text-neutral-500">Mulai</label>
            <input type="date" name="start" value="{{ $filters['start'] ?? '' }}"
                   class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm" />
          </div>
          <div>
            <label class="text-xs font-medium text-neutral-500">Selesai</label>
            <input type="date" name="end" value="{{ $filters['end'] ?? '' }}"
                   class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm" />
          </div>

          <div>
            <label class="text-xs font-medium text-neutral-500">Status</label>
            <select name="status" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              <option value="">Semua</option>
              @foreach(['pending','approved','rejected'] as $s)
                <option value="{{ $s }}" @selected(($filters['status'] ?? '') === $s)>{{ ucfirst($s) }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="text-xs font-medium text-neutral-500">Produk</label>
            <input type="text" name="produk" value="{{ $filters['produk'] ?? '' }}"
                   class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm" />
          </div>

          <div>
            <label class="text-xs font-medium text-neutral-500">Golongan</label>
            <select name="gol" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              <option value="">Semua</option>
              @foreach(['A','B','AB','O'] as $g)
                <option value="{{ $g }}" @selected(($filters['gol'] ?? '') === $g)>{{ $g }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="text-xs font-medium text-neutral-500">Rhesus</label>
            <select name="rhesus" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              <option value="">Semua</option>
              @foreach(['+','-'] as $r)
                <option value="{{ $r }}" @selected(($filters['rhesus'] ?? '') === $r)>{{ $r }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="mt-3 flex items-center justify-between">
          <button type="button" id="resetBtn" class="text-sm text-neutral-600 hover:underline">Reset</button>
          <button type="submit" class="rounded-lg bg-neutral-900 text-white text-sm px-3 py-1.5 hover:bg-neutral-800">
            Terapkan
          </button>
        </div>
      </div>
    </div>

    {{-- Page size --}}
    <div class="flex items-center gap-2">
      <span class="text-sm text-neutral-600">Baris:</span>
      <select name="per_page" onchange="this.form.submit()"
              class="rounded-xl border border-neutral-200 bg-white px-2 py-2 text-sm">
        @foreach([10,20,50] as $pp)
          <option value="{{ $pp }}" @selected(($filters['per_page'] ?? 10) == $pp)>{{ $pp }}</option>
        @endforeach
      </select>
    </div>

    {{-- Export --}}
    <div class="flex items-center gap-2 md:ml-auto">
      <a href="{{ route('admin.laporan.exportExcel', request()->all()) }}"
         class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700 hover:bg-emerald-100">
        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                d="m4 4 6 6m0 0L4 16m6-6h10"/>
        </svg>
        Excel
      </a>
    </div>
  </form>

  {{-- ===================== Hero Cards (ringkasan) ===================== --}}
  <div class="grid gap-4 md:grid-cols-3">
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5">
      <div class="text-sm text-emerald-700">Total Pemesanan</div>
      <div class="mt-2 text-3xl font-semibold text-emerald-900">{{ $summary['total_pemesanan'] }}</div>
    </div>
    <div class="rounded-2xl border border-sky-200 bg-sky-50/50 p-5">
      <div class="text-sm text-sky-700">Total Kantong</div>
      <div class="mt-2 text-3xl font-semibold text-sky-900">{{ $summary['total_kantong'] }}</div>
    </div>
    <div class="rounded-2xl border border-amber-200 bg-amber-50/50 p-5">
      <div class="text-sm text-amber-700">Status</div>
      <div class="mt-2 flex flex-wrap gap-2 text-sm">
        @foreach($summary['per_status'] as $k=>$v)
          <span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-1
                       {{ $k==='approved' ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                                          : ($k==='rejected' ? 'border-rose-200 bg-rose-50 text-rose-700'
                                                             : 'border-amber-200 bg-amber-50 text-amber-700') }}">
            {{ ucfirst($k) }}: <b>{{ $v }}</b>
          </span>
        @endforeach
      </div>
    </div>
  </div>

  {{-- ===================== Tabel ===================== --}}
  <div class="overflow-x-auto rounded-2xl border border-neutral-200 bg-white">
    <table class="min-w-full text-sm">
      <thead class="bg-neutral-50 text-neutral-600">
        <tr class="text-left">
          <th class="px-4 py-3 font-medium">#</th>
          <th class="px-4 py-3 font-medium">Tanggal</th>
          <th class="px-4 py-3 font-medium">RS Pemesan</th>
          <th class="px-4 py-3 font-medium">Pasien</th>
          <th class="px-4 py-3 font-medium">Produk</th>
          <th class="px-4 py-3 font-medium">Gol</th>
          <th class="px-4 py-3 font-medium">Rhesus</th>
          <th class="px-4 py-3 font-medium">Kantong</th>
          <th class="px-4 py-3 font-medium">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $i => $row)
          <tr class="border-t border-neutral-100">
            <td class="px-4 py-3">{{ $items->firstItem() + $i }}</td>
            <td class="px-4 py-3">{{ optional($row->created_at)->format('Y-m-d H:i') }}</td>
            <td class="px-4 py-3">{{ $row->rs_pemesan }}</td>
            <td class="px-4 py-3">{{ $row->nama_pasien }}</td>
            <td class="px-4 py-3">{{ $row->produk }}</td>
            <td class="px-4 py-3">{{ $row->gol_darah }}</td>
            <td class="px-4 py-3">{{ $row->rhesus }}</td>
            <td class="px-4 py-3">{{ (int) $row->jumlah_kantong }}</td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                  {{ $row->status==='approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' :
                     ($row->status==='pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' :
                                                 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                {{ ucfirst($row->status) }}
              </span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- ===================== Pagination (simetris) ===================== --}}
  @php
    $current     = $items->currentPage();
    $totalPages  = $items->lastPage();
    $hasPrev     = !$items->onFirstPage();
    $hasNext     = $items->hasMorePages();
    $url = fn (int $p) => $items->url($p);

    $max   = 5;                 // jumlah tombol nomor maksimum
    $half  = intdiv($max, 2);
    $start = max(1, $current - $half);
    $end   = min($totalPages, $start + $max - 1);
    if (($end - $start + 1) < $max) $start = max(1, $end - $max + 1);
  @endphp

  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="text-sm text-neutral-600">
      Menampilkan <b>{{ $items->firstItem() }}</b>–<b>{{ $items->lastItem() }}</b> dari
      <b>{{ $items->total() }}</b> data
    </div>

    <div class="flex items-center gap-2">
      {{-- Prev --}}
      <a href="{{ $hasPrev ? $url($current-1) : '#' }}"
         class="w-9 h-9 flex items-center justify-center rounded-lg border text-sm
                {{ $hasPrev ? 'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'
                            : 'bg-white border-neutral-200 text-neutral-400 cursor-not-allowed' }}">«</a>

      {{-- First + Ellipsis --}}
      @if($start > 1)
        <a href="{{ $url(1) }}" class="w-9 h-9 flex items-center justify-center rounded-lg border text-sm
                 bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50">1</a>
        @if($start > 2)
          <span class="px-1 text-neutral-400">…</span>
        @endif
      @endif

      {{-- Page numbers --}}
      @for($i=$start; $i<=$end; $i++)
        @if($i === $current)
          <span class="w-9 h-9 flex items-center justify-center rounded-lg border text-sm
                       bg-neutral-900 text-white border-neutral-900">{{ $i }}</span>
        @else
          <a href="{{ $url($i) }}" class="w-9 h-9 flex items-center justify-center rounded-lg border text-sm
                   bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50">{{ $i }}</a>
        @endif
      @endfor

      {{-- Ellipsis + Last --}}
      @if($end < $totalPages)
        @if($end < $totalPages - 1)
          <span class="px-1 text-neutral-400">…</span>
        @endif
        <a href="{{ $url($totalPages) }}" class="w-9 h-9 flex items-center justify-center rounded-lg border text-sm
                 bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50">{{ $totalPages }}</a>
      @endif

      {{-- Next --}}
      <a href="{{ $hasNext ? $url($current+1) : '#' }}"
         class="w-9 h-9 flex items-center justify-center rounded-lg border text-sm
                {{ $hasNext ? 'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'
                            : 'bg-white border-neutral-200 text-neutral-400 cursor-not-allowed' }}">»</a>
    </div>
  </div>
</div>

{{-- ===== Small JS for dropdown ===== --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('filterBtn');
    const menu = document.getElementById('filterMenu');
    const reset = document.getElementById('resetBtn');

    btn.addEventListener('click', (e) => { e.stopPropagation(); menu.classList.toggle('hidden'); });
    document.addEventListener('click', (e) => { if (!menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden'); });

    reset.addEventListener('click', () => {
      // kosongkan semua field filter (kecuali q & per_page)
      menu.querySelectorAll('input[type="date"]').forEach(i => i.value = '');
      menu.querySelectorAll('select').forEach(s => s.value = '');
    });
  });
</script>
@endsection
