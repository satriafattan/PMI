{{-- resources/views/admin/rekap/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Rekapitulasi Stok Darah')

@section('content')
@php
  // Nilai default filter agar tidak undefined
  $q        = request('q', '');
  $golQ     = request('gol', '');
  $rhQ      = request('rhesus', '');
  $kompQ    = request('komponen', '');
  $statusQ  = request('status', ''); // ''|aktif|keluar
  $fromIn   = request('masuk_from', '');
  $toIn     = request('masuk_to', '');
  $fromOut  = request('keluar_from', '');
  $toOut    = request('keluar_to', '');
  $perPage  = (int) request('per_page', 10);

  $kompOpts = [
    'WB: Whole Blood','PRC: Packed Red Cell','TC: Trombocyte Concentrate',
    'FFP: Fresh Frozen Plasma','AHF: Cryoprecipitated AHF','LP: Liquid Plasma',
    'TC Afereris','Plasma Konvalesen'
  ];
@endphp

<div class="px-6 pb-10 pt-6 space-y-8">
  {{-- Header --}}
  <div class="space-y-1">
    <h1 class="text-2xl md:text-3xl font-semibold">Rekapitulasi Stok Darah</h1>
    <p class="text-sm text-neutral-500">Daftar semua unit darah beserta statusnya</p>
  </div>

  {{-- Flash --}}
  @if (session('success'))
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
      {{ session('success') }}
    </div>
  @endif

  {{-- Ringkasan --}}
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5">
      <div class="text-sm text-emerald-700">Unit Aktif (belum keluar)</div>
      <div class="mt-2 text-2xl font-bold text-emerald-800">
        {{ $activeUnits ?? 0 }} <span class="text-base font-medium">unit</span>
      </div>
    </div>
    <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
      <div class="text-sm text-slate-600">Total Unit</div>
      <div class="mt-2 text-2xl font-bold text-slate-800">
        {{ $totalUnits ?? 0 }} <span class="text-base font-medium">unit</span>
      </div>
    </div>
    <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">
      <div class="text-sm text-amber-700">Keluar (bulan ini)</div>
      <div class="mt-2 text-2xl font-bold text-amber-800">
        {{ $outThisMonth ?? 0 }} <span class="text-base font-medium">unit</span>
      </div>
    </div>
    <div class="rounded-xl border border-rose-200 bg-rose-50 p-5">
      <div class="text-sm text-rose-700">Golongan Terdata</div>
      <div class="mt-2 text-2xl font-bold text-rose-800">
        {{ $bloodGroupsCount ?? 0 }} <span class="text-base font-medium">tipe</span>
      </div>
    </div>
  </div>

  {{-- Toolbar: Search full-width + Filter + Page size --}}
  <form method="GET" action="{{ route('admin.rekap-stok.index') }}"
        class="flex w-full items-center gap-2">
    {{-- Search FULL WIDTH --}}
    <div class="relative flex-1">
      <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
        <svg class="size-5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/>
        </svg>
      </span>
      <input id="searchInput" name="q" value="{{ $q }}" type="text"
             class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-900/10"
             placeholder="Cari ID darah, komponen, atau keterangan…"/>
    </div>

    {{-- Filter button + menu --}}
    <div class="relative">
      <button type="button" id="filterBtn"
              class="inline-flex items-center justify-center rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50">
        <svg class="size-5 text-neutral-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 6h18M6 12h12M10 18h4"/>
        </svg>
      </button>

      <div id="filterMenu"
           class="absolute right-0 z-20 mt-2 hidden w-[24rem] rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
        <div class="grid gap-3 sm:grid-cols-2">
          <div>
            <label class="text-xs font-medium text-neutral-500">Golongan</label>
            <select id="golSelect" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              <option value="">Semua</option>
              @foreach (['A','B','AB','O'] as $g)
                <option value="{{ $g }}" {{ $golQ===$g ? 'selected':'' }}>{{ $g }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="text-xs font-medium text-neutral-500">Rhesus</label>
            <select id="rhesusSelect" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              <option value="">Semua</option>
              <option value="+" {{ $rhQ==='+'?'selected':'' }}>+</option>
              <option value="-" {{ $rhQ==='-'?'selected':'' }}>-</option>
            </select>
          </div>

          <div class="sm:col-span-2">
            <label class="text-xs font-medium text-neutral-500">Komponen</label>
            <select id="kompSelect" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              <option value="">Semua</option>
              @foreach ($kompOpts as $opt)
                <option value="{{ $opt }}" {{ $kompQ===$opt ? 'selected':'' }}>{{ $opt }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="text-xs font-medium text-neutral-500">Status</label>
            <select id="statusSelect" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              <option value="" {{ $statusQ===''?'selected':'' }}>Semua</option>
              <option value="aktif"  {{ $statusQ==='aktif' ?'selected':'' }}>Aktif</option>
              <option value="keluar" {{ $statusQ==='keluar'?'selected':'' }}>Keluar</option>
            </select>
          </div>

          <div>
            <label class="text-xs font-medium text-neutral-500">Masuk (dari)</label>
            <input type="date" id="masukFrom" value="{{ $fromIn }}"
                   class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
          </div>
          <div>
            <label class="text-xs font-medium text-neutral-500">Masuk (hingga)</label>
            <input type="date" id="masukTo" value="{{ $toIn }}"
                   class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
          </div>

          <div>
            <label class="text-xs font-medium text-neutral-500">Keluar (dari)</label>
            <input type="date" id="keluarFrom" value="{{ $fromOut }}"
                   class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
          </div>
          <div>
            <label class="text-xs font-medium text-neutral-500">Keluar (hingga)</label>
            <input type="date" id="keluarTo" value="{{ $toOut }}"
                   class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
          </div>

          <div class="sm:col-span-2 flex items-center justify-between">
            <button type="button" id="resetBtn" class="text-sm text-neutral-600 hover:underline">Reset</button>
            <button type="submit" id="applyBtn"
                    class="rounded-lg bg-neutral-900 px-3 py-1.5 text-sm text-white hover:bg-neutral-800">
              Terapkan
            </button>
          </div>
        </div>

        {{-- Hidden inputs untuk request GET --}}
        <input type="hidden" name="gol"         id="golInput"         value="{{ $golQ }}">
        <input type="hidden" name="rhesus"      id="rhesusInput"      value="{{ $rhQ }}">
        <input type="hidden" name="komponen"    id="kompInput"        value="{{ $kompQ }}">
        <input type="hidden" name="status"      id="statusInput"      value="{{ $statusQ }}">
        <input type="hidden" name="masuk_from"  id="masukFromInput"   value="{{ $fromIn }}">
        <input type="hidden" name="masuk_to"    id="masukToInput"     value="{{ $toIn }}">
        <input type="hidden" name="keluar_from" id="keluarFromInput"  value="{{ $fromOut }}">
        <input type="hidden" name="keluar_to"   id="keluarToInput"    value="{{ $toOut }}">
        <input type="hidden" name="per_page"    id="perPageInput"     value="{{ $perPage }}">
      </div>
    </div>

    {{-- Page size (kecil di kanan, tidak mengurangi lebar search) --}}
    <div class="flex items-center gap-2">
      <label for="pageSize" class="text-sm text-neutral-600">Baris:</label>
      <select id="pageSize" class="rounded-xl border border-neutral-200 bg-white px-2 py-2 text-sm">
        @foreach([5,10,20] as $opt)
          <option value="{{ $opt }}" {{ $perPage===$opt ? 'selected' : '' }}>{{ $opt }}</option>
        @endforeach
      </select>
    </div>
  </form>

  {{-- Info jumlah --}}
  <div class="text-sm text-slate-500">
    Menampilkan {{ $rekap->firstItem() ?? 0 }}–{{ $rekap->lastItem() ?? 0 }} dari {{ $rekap->total() }} data
  </div>

  {{-- Tabel --}}
  <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-neutral-200 text-sm">
        <thead class="bg-neutral-50 text-neutral-600">
          <tr class="text-left">
            <th class="px-4 py-3 font-semibold">ID Darah</th>
            <th class="px-4 py-3 font-semibold">Komponen</th>
            <th class="px-4 py-3 text-center font-semibold">Gol.</th>
            <th class="px-4 py-3 text-center font-semibold">Rhesus</th>
            <th class="px-4 py-3 text-center font-semibold">Tgl Masuk</th>
            <th class="px-4 py-3 text-center font-semibold">Tgl Keluar</th>
            <th class="px-4 py-3 font-semibold">Keterangan</th>
            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
          @forelse ($rekap as $row)
            <tr class="odd:bg-white even:bg-neutral-50/50">
              <td class="px-4 py-3 font-medium text-neutral-800">{{ $row->id_darah }}</td>
              <td class="px-4 py-3 text-neutral-800">{{ $row->komponen }}</td>
              <td class="px-4 py-3 text-center">{{ $row->gol_darah }}</td>
              <td class="px-4 py-3 text-center">{{ $row->rhesus }}</td>
              <td class="px-4 py-3 text-center">
                @if($row->tanggal_masuk)
                  {{ \Illuminate\Support\Carbon::parse($row->tanggal_masuk)->format('d M Y') }}
                @else - @endif
              </td>
              <td class="px-4 py-3 text-center">
                @if($row->tanggal_keluar)
                  {{ \Illuminate\Support\Carbon::parse($row->tanggal_keluar)->format('d M Y') }}
                @else
                  <span class="rounded bg-emerald-50 px-2 py-1 text-xs text-emerald-700">Aktif</span>
                @endif
              </td>
              <td class="px-4 py-3">{{ $row->keterangan ?? '-' }}</td>
              <td class="px-4 py-3">
                <div class="flex items-center justify-center gap-2">
                  <a href="{{ route('admin.rekap-stok.edit', $row->id) }}"
                     class="rounded border border-neutral-300 px-2 py-1 text-neutral-700 hover:bg-neutral-100"
                     title="Edit">✏️</a>
                  <form method="POST" action="{{ route('admin.rekap-stok.destroy', $row->id) }}"
                        onsubmit="return confirm('Hapus data ini?')">
                    @csrf @method('DELETE')
                    <button class="rounded border border-neutral-300 px-2 py-1 text-rose-600 hover:bg-rose-50"
                            title="Hapus">🗑️</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-4 py-6 text-center text-neutral-500">Belum ada data stok darah.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Pagination --}}
  <div>
    {{ $rekap->withQueryString()->links() }}
  </div>
</div>

{{-- JS Filter --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const filterBtn  = document.getElementById('filterBtn');
    const filterMenu = document.getElementById('filterMenu');
    const applyBtn   = document.getElementById('applyBtn');
    const resetBtn   = document.getElementById('resetBtn');

    filterBtn?.addEventListener('click', (e) => {
      e.stopPropagation();
      filterMenu?.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
      if (filterMenu && filterBtn &&
          !filterMenu.contains(e.target) && !filterBtn.contains(e.target)) {
        filterMenu.classList.add('hidden');
      }
    });

    applyBtn?.addEventListener('click', () => {
      const setVal = (src, dst) => { const s=document.getElementById(src), d=document.getElementById(dst); if(s&&d) d.value=s.value||''; };
      setVal('golSelect', 'golInput');
      setVal('rhesusSelect', 'rhesusInput');
      setVal('kompSelect', 'kompInput');
      setVal('statusSelect', 'statusInput');
      setVal('masukFrom', 'masukFromInput');
      setVal('masukTo', 'masukToInput');
      setVal('keluarFrom', 'keluarFromInput');
      setVal('keluarTo', 'keluarToInput');
    });

    resetBtn?.addEventListener('click', () => {
      ['golSelect','rhesusSelect','kompSelect','statusSelect','masukFrom','masukTo','keluarFrom','keluarTo']
        .forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
    });

    const pageSizeSel = document.getElementById('pageSize');
    const perPageInput = document.getElementById('perPageInput');
    pageSizeSel?.addEventListener('change', () => {
      if (perPageInput) perPageInput.value = pageSizeSel.value;
      pageSizeSel.closest('form')?.submit();
    });
  });
</script>
@endsection
