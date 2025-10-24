{{-- resources/views/admin/detail/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Informasi Detail Darah')

@section('content')
@php
    /* =========================
     * TABEL 1: Stok Tersedia
     * ========================= */
    $rows = [
        ['id_darah'=>'BD001','gol_darah'=>'A',  'rhesus'=>'Rh+','komponen'=>'PRC','tgl_masuk'=>'2025-02-12','tgl_kadaluarsa'=>'2025-02-12'],
        ['id_darah'=>'BD002','gol_darah'=>'A',  'rhesus'=>'Rh+','komponen'=>'WB', 'tgl_masuk'=>'2025-02-08','tgl_kadaluarsa'=>'2025-02-15'],
        ['id_darah'=>'BD003','gol_darah'=>'B',  'rhesus'=>'Rh-','komponen'=>'TRC','tgl_masuk'=>'2025-02-05','tgl_kadaluarsa'=>'2025-02-20'],
        ['id_darah'=>'BD004','gol_darah'=>'O',  'rhesus'=>'Rh+','komponen'=>'FFP','tgl_masuk'=>'2025-02-03','tgl_kadaluarsa'=>'2025-02-18'],
        ['id_darah'=>'BD005','gol_darah'=>'AB', 'rhesus'=>'Rh+','komponen'=>'PRC','tgl_masuk'=>'2025-02-09','tgl_kadaluarsa'=>'2025-02-17'],
        ['id_darah'=>'BD006','gol_darah'=>'A',  'rhesus'=>'Rh-','komponen'=>'TC', 'tgl_masuk'=>'2025-02-04','tgl_kadaluarsa'=>'2025-02-25'],
        ['id_darah'=>'BD007','gol_darah'=>'O',  'rhesus'=>'Rh+','komponen'=>'WB', 'tgl_masuk'=>'2025-02-10','tgl_kadaluarsa'=>'2025-02-20'],
        ['id_darah'=>'BD008','gol_darah'=>'B',  'rhesus'=>'Rh+','komponen'=>'FFP','tgl_masuk'=>'2025-02-06','tgl_kadaluarsa'=>'2025-02-15'],
        ['id_darah'=>'BD009','gol_darah'=>'A',  'rhesus'=>'Rh+','komponen'=>'PRC','tgl_masuk'=>'2025-02-02','tgl_kadaluarsa'=>'2025-02-12'],
        ['id_darah'=>'BD010','gol_darah'=>'AB', 'rhesus'=>'Rh-','komponen'=>'PRC','tgl_masuk'=>'2025-02-11','tgl_kadaluarsa'=>'2025-02-28'],
    ];

    $kompOpts = ['PRC','WB','TRC','FFP','TC','AHF','LP','TCA','PK'];

    /* =========================
     * Sumber untuk Tabel 2 & 3
     * ========================= */
    $historyRows = [
        ['id' => 'BD001','gol' => 'A','rh' => 'Rh+','produk' => 'PRC','masuk' => '2025-02-12','exp' => '2025-02-12','penerima' => 'RS Umum Jakarta', 'status' => 'Approved'],
        ['id' => 'BD002','gol' => 'A','rh' => 'Rh+','produk' => 'WB', 'masuk' => '2025-02-08','exp' => '2025-02-15','penerima' => 'RS Umum Jakarta', 'status' => 'Pending'],
        ['id' => 'BD003','gol' => 'B','rh' => 'Rh-','produk' => 'TRC','masuk' => '2025-02-05','exp' => '2025-02-20','penerima' => 'RS Pelita Sehat', 'status' => 'Approved'],
        ['id' => 'BD004','gol' => 'O','rh' => 'Rh+','produk' => 'FFP','masuk' => '2025-02-03','exp' => '2025-02-18','penerima' => 'RS Sinar Abadi', 'status' => 'Approved'],
        ['id' => 'BD005','gol' => 'AB','rh' => 'Rh+','produk' => 'PRC','masuk' => '2025-02-09','exp' => '2025-02-17','penerima' => 'RS Umum Jakarta', 'status' => 'Pending'],
        ['id' => 'BD006','gol' => 'A','rh' => 'Rh-','produk' => 'TC', 'masuk' => '2025-02-04','exp' => '2025-02-25','penerima' => 'RS Persada', 'status' => 'Approved'],
        ['id' => 'BD007','gol' => 'O','rh' => 'Rh+','produk' => 'WB', 'masuk' => '2025-02-10','exp' => '2025-02-20','penerima' => 'RS Persada', 'status' => 'Approved'],
        ['id' => 'BD008','gol' => 'B','rh' => 'Rh+','produk' => 'FFP','masuk' => '2025-02-06','exp' => '2025-02-15','penerima' => 'RS Maju Jaya', 'status' => 'Pending'],
        ['id' => 'BD009','gol' => 'A','rh' => 'Rh+','produk' => 'PRC','masuk' => '2025-02-02','exp' => '2025-02-12','penerima' => 'RS Umum Jakarta', 'status' => 'Rejected'],
        ['id' => 'BD010','gol' => 'AB','rh' => 'Rh-','produk' => 'PRC','masuk' => '2025-02-11','exp' => '2025-02-28','penerima' => 'RS Kasih Ibu', 'status' => 'Approved'],
    ];
@endphp

<div class="space-y-4">
  {{-- Header tetap --}}
  <div class="space-y-1">
    <h1 class="text-2xl md:text-3xl font-semibold">Informasi Detail Darah</h1>
    <p class="text-sm text-neutral-500">Data stok unit darah yang tersedia, keluar, dan kadaluwarsa</p>
  </div>

  {{-- Toggle 3 tombol di bawah header --}}
  <div class="inline-flex rounded-2xl border border-neutral-200 bg-white p-1">
    <button id="btnAvail"   type="button" class="tabbtn is-active">Tersedia</button>
    <button id="btnUnavail" type="button" class="tabbtn">Keluar</button>
    <button id="btnExpired" type="button" class="tabbtn">Kadaluwarsa</button>
  </div>
</div>

{{-- ========================= --}}
{{-- SECTION: TABEL 1 (Tersedia) --}}
{{-- ========================= --}}
<section id="secAvail" class="space-y-6 mt-6">
  {{-- Toolbar Tabel 1 --}}
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex w-full items-center gap-2 sm:flex-1">
      <div class="relative flex-1">
        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
          <svg class="size-5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/></svg>
        </span>
        <input id="searchInput" type="text" class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-900/10" placeholder="Cari ID darah atau komponen…">
      </div>

      <div class="relative">
        <button id="filterBtn" type="button" class="inline-flex items-center justify-center rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50">
          <svg class="size-5 text-neutral-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 6h18M6 12h12M10 18h4"/></svg>
        </button>
        <div id="filterMenu" class="absolute right-0 z-20 mt-2 hidden w-[22rem] rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
          <div class="grid gap-3 sm:grid-cols-2">
            <div>
              <label class="text-xs font-medium text-neutral-500">Golongan</label>
              <select id="golSelect" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach (['A','B','AB','O'] as $g) <option value="{{ $g }}">{{ $g }}</option> @endforeach
              </select>
            </div>
            <div>
              <label class="text-xs font-medium text-neutral-500">Rhesus</label>
              <select id="rhesusSelect" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                <option value="">Semua</option>
                <option value="Rh+">Rh+</option>
                <option value="Rh-">Rh-</option>
              </select>
            </div>
            <div class="sm:col-span-2">
              <label class="text-xs font-medium text-neutral-500">Komponen</label>
              <select id="kompSelect" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach ($kompOpts as $opt) <option value="{{ $opt }}">{{ $opt }}</option> @endforeach
              </select>
            </div>
            <div>
              <label class="text-xs font-medium text-neutral-500">Tgl Masuk (dari)</label>
              <input type="date" id="masukFrom" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
            </div>
            <div>
              <label class="text-xs font-medium text-neutral-500">Tgl Masuk (hingga)</label>
              <input type="date" id="masukTo" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
            </div>
            <div>
              <label class="text-xs font-medium text-neutral-500">Kadaluarsa (dari)</label>
              <input type="date" id="expFrom" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
            </div>
            <div>
              <label class="text-xs font-medium text-neutral-500">Kadaluarsa (hingga)</label>
              <input type="date" id="expTo" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-2 flex items-center justify-between">
              <button type="button" id="resetBtn" class="text-sm text-neutral-600 hover:underline">Reset</button>
              <button type="button" id="applyBtn" class="rounded-lg bg-neutral-900 px-3 py-1.5 text-sm text-white hover:bg-neutral-800">Terapkan</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <label for="pageSize" class="text-sm text-neutral-600">Baris:</label>
      <select id="pageSize" class="rounded-xl border border-neutral-200 bg-white px-2 py-2 text-sm">
        <option>5</option><option selected>10</option><option>20</option>
      </select>
    </div>
  </div>

  <div class="rounded-2xl border border-neutral-200 bg-white overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 text-neutral-600">
          <tr class="text-left">
            <th data-key="id_darah" class="sortable px-4 py-3 font-medium cursor-pointer select-none">ID Darah</th>
            <th data-key="gol_darah" class="sortable px-4 py-3 font-medium cursor-pointer select-none">Golongan Darah</th>
            <th data-key="rhesus" class="sortable px-4 py-3 font-medium cursor-pointer select-none">Rhesus</th>
            <th data-key="komponen" class="sortable px-4 py-3 font-medium cursor-pointer select-none">Produk Darah</th>
            <th data-key="tgl_masuk" class="sortable px-4 py-3 font-medium cursor-pointer select-none">Tanggal Masuk</th>
            <th data-key="tgl_kadaluarsa" class="sortable px-4 py-3 font-medium cursor-pointer select-none">Tanggal Kadaluwarsa</th>
          </tr>
        </thead>
        <tbody id="tableBody"></tbody>
      </table>
    </div>
  </div>

  <div class="flex flex-col sm:flex-row gap-3 sm:items-center justify-between">
    <div id="pageInfo" class="text-sm text-neutral-600"></div>
    <div id="pagination" class="flex items-center gap-2"></div>
  </div>
</section>

{{-- ========================= --}}
{{-- SECTION: TABEL 2 (Keluar / Tidak Tersedia) --}}
{{-- ========================= --}}
<section id="secUnavail" class="space-y-6 mt-6 hidden">

  {{-- Toolbar Tabel 2 --}}
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex w-full items-center gap-2 sm:flex-1">
      <div class="relative flex-1">
        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
          <svg class="size-5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/></svg>
        </span>
        <input id="hkSearchInput" type="text" class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-900/10" placeholder="Cari nama pemesan atau rumah sakit…">
      </div>

      <div class="relative">
        <button id="hkFilterBtn" type="button" class="inline-flex items-center justify-center rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50">
          <svg class="size-5 text-neutral-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 6h18M6 12h12M10 18h4"/></svg>
        </button>
        <div id="hkFilterMenu" class="absolute right-0 z-20 mt-2 hidden w-[22rem] rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
          <div class="grid gap-3 sm:grid-cols-2">
            <div>
              <label class="text-xs font-medium text-neutral-500">Golongan</label>
              <select id="hkGolSelect" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach (['A','B','AB','O'] as $g) <option value="{{ $g }}">{{ $g }}</option> @endforeach
              </select>
            </div>
            <div>
              <label class="text-xs font-medium text-neutral-500">Rhesus</label>
              <select id="hkRhesusSelect" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                <option value="">Semua</option>
                <option value="Rh+">Rh+</option><option value="Rh-">Rh-</option>
              </select>
            </div>
            <div>
              <label class="text-xs font-medium text-neutral-500">Produk</label>
              <select id="hkProdukSelect" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach (['PRC','WB','TRC','FFP','TC'] as $p) <option value="{{ $p }}">{{ $p }}</option> @endforeach
              </select>
            </div>
            <div>
              <label class="text-xs font-medium text-neutral-500">Status</label>
              <select id="hkStatusSelect" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach (['Approved','Pending','Rejected'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
              </select>
            </div>
            <div>
              <label class="text-xs font-medium text-neutral-500">Tgl Masuk (dari)</label>
              <input type="date" id="hkMasukFrom" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
            </div>
            <div>
              <label class="text-xs font-medium text-neutral-500">Tgl Masuk (hingga)</label>
              <input type="date" id="hkMasukTo" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-2 flex items-center justify-between">
              <button type="button" id="hkResetBtn" class="text-sm text-neutral-600 hover:underline">Reset</button>
              <button type="button" id="hkApplyBtn" class="rounded-lg bg-neutral-900 px-3 py-1.5 text-sm text-white hover:bg-neutral-800">Terapkan</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <label for="hkPageSize" class="text-sm text-neutral-600">Baris:</label>
      <select id="hkPageSize" class="rounded-xl border border-neutral-200 bg-white px-2 py-2 text-sm">
        <option>5</option><option selected>10</option><option>20</option>
      </select>
    </div>
  </div>

  <div class="rounded-2xl border border-neutral-200 bg-white overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 text-neutral-600">
          <tr class="text-left">
            <th data-hk="id" class="hk-sortable px-4 py-3 font-medium cursor-pointer select-none">ID Darah</th>
            <th data-hk="gol" class="hk-sortable px-4 py-3 font-medium cursor-pointer select-none">Golongan Darah</th>
            <th data-hk="rh" class="hk-sortable px-4 py-3 font-medium cursor-pointer select-none">Rhesus</th>
            <th data-hk="produk" class="hk-sortable px-4 py-3 font-medium cursor-pointer select-none">Produk Darah</th>
            <th data-hk="masuk" class="hk-sortable px-4 py-3 font-medium cursor-pointer select-none">Tanggal Masuk</th>
            <th data-hk="exp" class="hk-sortable px-4 py-3 font-medium cursor-pointer select-none">Tanggal Kadaluwarsa</th>
            <th data-hk="penerima" class="hk-sortable px-4 py-3 font-medium cursor-pointer select-none">Penerima</th>
            <th data-hk="status" class="hk-sortable px-4 py-3 font-medium cursor-pointer select-none">Status</th>
          </tr>
        </thead>
        <tbody id="hkTableBody"></tbody>
      </table>
    </div>
  </div>

  <div class="flex flex-col sm:flex-row gap-3 sm:items-center justify-between">
    <div id="hkPageInfo" class="text-sm text-neutral-600"></div>
    <div id="hkPagination" class="flex items-center gap-2"></div>
  </div>
</section>

{{-- ========================= --}}
{{-- SECTION: TABEL 3 (Kadaluwarsa) --}}
{{-- ========================= --}}
<section id="secExpired" class="space-y-6 mt-6 hidden">

  {{-- Toolbar Tabel 3 (tanpa filter Status) --}}
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex w-full items-center gap-2 sm:flex-1">
      <div class="relative flex-1">
        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
          <svg class="size-5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/></svg>
        </span>
        <input id="exSearchInput" type="text" class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-900/10" placeholder="Cari nama pemesan atau rumah sakit…">
      </div>

      <div class="relative">
        <button id="exFilterBtn" type="button" class="inline-flex items-center justify-center rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50">
          <svg class="size-5 text-neutral-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 6h18M6 12h12M10 18h4"/></svg>
        </button>
        <div id="exFilterMenu" class="absolute right-0 z-20 mt-2 hidden w-[22rem] rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
          <div class="grid gap-3 sm:grid-cols-2">
            <div>
              <label class="text-xs font-medium text-neutral-500">Golongan</label>
              <select id="exGolSelect" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach (['A','B','AB','O'] as $g) <option value="{{ $g }}">{{ $g }}</option> @endforeach
              </select>
            </div>
            <div>
              <label class="text-xs font-medium text-neutral-500">Rhesus</label>
              <select id="exRhesusSelect" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                <option value="">Semua</option>
                <option value="Rh+">Rh+</option><option value="Rh-">Rh-</option>
              </select>
            </div>
            <div class="sm:col-span-2">
              <label class="text-xs font-medium text-neutral-500">Produk</label>
              <select id="exProdukSelect" class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach (['PRC','WB','TRC','FFP','TC'] as $p) <option value="{{ $p }}">{{ $p }}</option> @endforeach
              </select>
            </div>

            <div class="sm:col-span-2 flex items-center justify-between">
              <button type="button" id="exResetBtn" class="text-sm text-neutral-600 hover:underline">Reset</button>
              <button type="button" id="exApplyBtn" class="rounded-lg bg-neutral-900 px-3 py-1.5 text-sm text-white hover:bg-neutral-800">Terapkan</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <label for="exPageSize" class="text-sm text-neutral-600">Baris:</label>
      <select id="exPageSize" class="rounded-xl border border-neutral-200 bg-white px-2 py-2 text-sm">
        <option>5</option><option selected>10</option><option>20</option>
      </select>
    </div>
  </div>

  <div class="rounded-2xl border border-neutral-200 bg-white overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 text-neutral-600">
          <tr class="text-left">
            <th data-ex="id" class="ex-sortable px-4 py-3 font-medium cursor-pointer select-none">ID Darah</th>
            <th data-ex="gol" class="ex-sortable px-4 py-3 font-medium cursor-pointer select-none">Golongan Darah</th>
            <th data-ex="rh" class="ex-sortable px-4 py-3 font-medium cursor-pointer select-none">Rhesus</th>
            <th data-ex="produk" class="ex-sortable px-4 py-3 font-medium cursor-pointer select-none">Produk Darah</th>
            <th data-ex="masuk" class="ex-sortable px-4 py-3 font-medium cursor-pointer select-none">Tanggal Masuk</th>
            <th data-ex="exp" class="ex-sortable px-4 py-3 font-medium cursor-pointer select-none">Tanggal Kadaluwarsa</th>
          </tr>
        </thead>
        <tbody id="exTableBody"></tbody>
      </table>
    </div>
  </div>

  <div class="flex flex-col sm:flex-row gap-3 sm:items-center justify-between">
    <div id="exPageInfo" class="text-sm text-neutral-600"></div>
    <div id="exPagination" class="flex items-center gap-2"></div>
  </div>
</section>

{{-- ===================== --}}
{{-- SCRIPTS --}}
{{-- ===================== --}}
<script>
/* ===== Utilities badge ===== */
const dotGol   = g => `<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-rose-50 text-rose-500 text-xs font-semibold">${g}</span>`;
const badgeProduk = p => `<span class="inline-block rounded-full border border-sky-200 bg-sky-50 px-2 py-0.5 text-xs text-sky-700">${p}</span>`;
function badgeStatus(s){
  const map={Approved:'bg-emerald-50 text-emerald-700 border-emerald-200', Pending:'bg-amber-50 text-amber-700 border-amber-200', Rejected:'bg-rose-50 text-rose-700 border-rose-200'};
  return `<span class="inline-block rounded-full px-3 py-0.5 text-xs border ${map[s]||'bg-neutral-50 text-neutral-600 border-neutral-200'}">${s}</span>`;
}

/* ===== Toggle 3 section ===== */
const secAvail   = document.getElementById('secAvail');
const secUnavail = document.getElementById('secUnavail');
const secExpired = document.getElementById('secExpired');
const btnAvail   = document.getElementById('btnAvail');
const btnUnavail = document.getElementById('btnUnavail');
const btnExpired = document.getElementById('btnExpired');

function setTab(active){ // 'avail' | 'unavail' | 'expired'
  secAvail.classList.toggle('hidden',   active!=='avail');
  secUnavail.classList.toggle('hidden', active!=='unavail');
  secExpired.classList.toggle('hidden', active!=='expired');
  [btnAvail, btnUnavail, btnExpired].forEach(b=>b.classList.remove('is-active'));
  (active==='avail'?btnAvail:active==='unavail'?btnUnavail:btnExpired).classList.add('is-active');
  // tutup menu filter yang mungkin terbuka
  ['filterMenu','hkFilterMenu','exFilterMenu'].forEach(id=>document.getElementById(id)?.classList.add('hidden'));
}
btnAvail.addEventListener('click',   ()=> setTab('avail'));
btnUnavail.addEventListener('click', ()=> setTab('unavail'));
btnExpired.addEventListener('click', ()=> setTab('expired'));

/* ====== TABEL 1 (tersedia) ====== */
const rows = @json($rows);
let sortKey='id_darah', sortDir='asc', currentPage=1, pageSize=10;
const toYmd = s => String(s||'');
const inRange = (d,f,t)=>{ if(!d) return true; const dd=toYmd(d); if(f&&dd<toYmd(f))return false; if(t&&dd>toYmd(t))return false; return true; };

function getFiltered(){
  const q=(document.getElementById('searchInput').value||'').toLowerCase().trim();
  const gol=document.getElementById('golSelect')?.value||'';
  const rh=document.getElementById('rhesusSelect')?.value||'';
  const kp=document.getElementById('kompSelect')?.value||'';
  const mf=document.getElementById('masukFrom')?.value||'';
  const mt=document.getElementById('masukTo')?.value||'';
  const ef=document.getElementById('expFrom')?.value||'';
  const et=document.getElementById('expTo')?.value||'';
  return rows.filter(o=>{
    const hitQ=!q||String(o.id_darah).toLowerCase().includes(q)||String(o.komponen).toLowerCase().includes(q);
    const hitG=!gol||o.gol_darah===gol;
    const hitR=!rh||o.rhesus===rh;
    const hitK=!kp||o.komponen===kp;
    const hitM=inRange(o.tgl_masuk,mf,mt);
    const hitE=inRange(o.tgl_kadaluarsa,ef,et);
    return hitQ&&hitG&&hitR&&hitK&&hitM&&hitE;
  });
}
function getSorted(data){
  const cp=[...data];
  cp.sort((a,b)=>{ let va=a[sortKey], vb=b[sortKey]; va=String(va??'').toLowerCase(); vb=String(vb??'').toLowerCase();
    if(va<vb) return sortDir==='asc'?-1:1; if(va>vb) return sortDir==='asc'?1:-1; return 0; });
  return cp;
}
function getPaged(data){
  const total=data.length, pages=Math.max(1,Math.ceil(total/pageSize));
  currentPage=Math.min(currentPage,pages);
  const start=(currentPage-1)*pageSize;
  return {slice:data.slice(start,start+pageSize), total, pages};
}
function renderTable(data){
  const tb=document.getElementById('tableBody');
  if(!data.length){ tb.innerHTML=`<tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>`; return; }
  tb.innerHTML = data.map(o=>`
    <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
      <td class="px-4 py-3 font-medium text-neutral-800">${o.id_darah}</td>
      <td class="px-4 py-3">${dotGol(o.gol_darah)}</td>
      <td class="px-4 py-3">${o.rhesus}</td>
      <td class="px-4 py-3">${badgeProduk(o.komponen)}</td>
      <td class="px-4 py-3">${o.tgl_masuk}</td>
      <td class="px-4 py-3">${o.tgl_kadaluarsa}</td>
    </tr>
  `).join('');
}
function getPageRange(totalPages,current,max=5){
  const pages=[]; const half=Math.floor(max/2);
  let start=Math.max(1,current-half), end=Math.min(totalPages,start+max-1);
  if(end-start+1<max) start=Math.max(1,end-max+1);
  if(start>1){ pages.push(1); if(start>2) pages.push('…'); }
  for(let i=start;i<=end;i++) pages.push(i);
  if(end<totalPages){ if(end<totalPages-1) pages.push('…'); pages.push(totalPages); }
  return pages;
}
function renderPagination(total,pages){
  const cont=document.getElementById('pagination');
  const info=document.getElementById('pageInfo');
  const start= total===0?0:(currentPage-1)*pageSize+1;
  const end=Math.min(currentPage*pageSize,total);
  info.textContent=`Menampilkan ${start}-${end} dari ${total} data`;
  if(pages<=1){ cont.innerHTML=''; return; }
  const btn=(label,page,disabled=false,active=false)=>`
    <button class="min-w-9 h-9 px-3 rounded-lg border text-sm
                   ${active?'bg-neutral-900 text-white border-neutral-900':'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'}
                   ${disabled?'opacity-50 cursor-not-allowed':''}"
            ${disabled?'disabled':''} data-page="${page}">${label}</button>`;
  let html=''; html+=btn('«', currentPage-1, currentPage===1);
  getPageRange(pages,currentPage,5).forEach(p=>{
    if(p==='…') html+=`<span class="px-2 text-neutral-400">…</span>`;
    else html+=btn(p,p,false,p===currentPage);
  });
  html+=btn('»', currentPage+1, currentPage===pages);
  cont.innerHTML=html;
  cont.querySelectorAll('button[data-page]').forEach(b=>{
    b.addEventListener('click', ()=>{ const p=Number(b.dataset.page); if(!Number.isNaN(p)){ currentPage=p; renderAll(); }});
  });
}
function markSortHeaders(){
  document.querySelectorAll('th.sortable').forEach(th=>{
    th.querySelector('.sort-ind')?.remove();
    if(th.dataset.key===sortKey){
      const s=document.createElement('span'); s.className='sort-ind inline-block ml-1 text-neutral-400';
      s.innerHTML = sortDir==='asc' ? '▲' : '▼'; th.appendChild(s);
    }
  });
}
function renderAll(){
  const filtered=getFiltered();
  const sorted=getSorted(filtered);
  const {slice,total,pages}=getPaged(sorted);
  renderTable(slice); renderPagination(total,pages); markSortHeaders();
}

/* ====== TABEL 2 (Tidak Tersedia) ====== */
const hkRows = @json($historyRows);
let hkSortKey='id', hkSortDir='asc', hkCurrentPage=1, hkPageSize=10;
const hkToYmd = s=>String(s||'');
const hkInRange=(d,f,t)=>{ if(!d) return true; const dd=hkToYmd(d); if(f&&dd<hkToYmd(f))return false; if(t&&dd>hkToYmd(t))return false; return true; };

function hkGetFiltered(){
  const q=(document.getElementById('hkSearchInput').value||'').toLowerCase().trim();
  const g=document.getElementById('hkGolSelect')?.value||'';
  const rh=document.getElementById('hkRhesusSelect')?.value||'';
  const pr=document.getElementById('hkProdukSelect')?.value||'';
  const st=document.getElementById('hkStatusSelect')?.value||'';
  const mf=document.getElementById('hkMasukFrom')?.value||'';
  const mt=document.getElementById('hkMasukTo')?.value||'';
  return hkRows.filter(o=>{
    const hitQ=!q||String(o.id).toLowerCase().includes(q)||String(o.penerima).toLowerCase().includes(q);
    const hitG=!g||o.gol===g, hitR=!rh||o.rh===rh, hitP=!pr||o.produk===pr, hitS=!st||o.status===st;
    const hitM=hkInRange(o.masuk,mf,mt);
    return hitQ&&hitG&&hitR&&hitP&&hitS&&hitM;
  });
}
function hkGetSorted(data){
  const cp=[...data];
  cp.sort((a,b)=>{ let va=a[hkSortKey], vb=b[hkSortKey]; va=String(va??'').toLowerCase(); vb=String(vb??'').toLowerCase();
    if(va<vb) return hkSortDir==='asc'?-1:1; if(va>vb) return hkSortDir==='asc'?1:-1; return 0; });
  return cp;
}
function hkGetPaged(data){
  const total=data.length, pages=Math.max(1,Math.ceil(total/hkPageSize));
  hkCurrentPage=Math.min(hkCurrentPage,pages);
  const start=(hkCurrentPage-1)*hkPageSize;
  return {slice:data.slice(start,start+hkPageSize), total, pages};
}
function hkRenderTable(data){
  const tb=document.getElementById('hkTableBody');
  if(!data.length){ tb.innerHTML=`<tr><td colspan="8" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>`; return; }
  tb.innerHTML = data.map(o=>`
    <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
      <td class="px-4 py-3 font-medium text-neutral-800">${o.id}</td>
      <td class="px-4 py-3">${dotGol(o.gol)}</td>
      <td class="px-4 py-3">${o.rh}</td>
      <td class="px-4 py-3">${badgeProduk(o.produk)}</td>
      <td class="px-4 py-3">${o.masuk}</td>
      <td class="px-4 py-3">${o.exp}</td>
      <td class="px-4 py-3">${o.penerima}</td>
      <td class="px-4 py-3">${badgeStatus(o.status)}</td>
    </tr>
  `).join('');
}
function hkRange(totalPages,current,max=5){
  const pages=[]; const half=Math.floor(max/2);
  let start=Math.max(1,current-half), end=Math.min(totalPages,start+max-1);
  if(end-start+1<max) start=Math.max(1,end-max+1);
  if(start>1){ pages.push(1); if(start>2) pages.push('…'); }
  for(let i=start;i<=end;i++) pages.push(i);
  if(end<totalPages){ if(end<totalPages-1) pages.push('…'); pages.push(totalPages); }
  return pages;
}
function hkRenderPagination(total,pages){
  const cont=document.getElementById('hkPagination');
  const info=document.getElementById('hkPageInfo');
  const start= total===0?0:(hkCurrentPage-1)*hkPageSize+1;
  const end=Math.min(hkCurrentPage*hkPageSize,total);
  info.textContent=`Menampilkan ${start}-${end} dari ${total} data`;
  if(pages<=1){ cont.innerHTML=''; return; }
  const btn=(label,page,disabled=false,active=false)=>`
    <button class="min-w-9 h-9 px-3 rounded-lg border text-sm
                   ${active?'bg-neutral-900 text-white border-neutral-900':'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'}
                   ${disabled?'opacity-50 cursor-not-allowed':''}"
            ${disabled?'disabled':''} data-hkpage="${page}">${label}</button>`;
  let html=''; html+=btn('«', hkCurrentPage-1, hkCurrentPage===1);
  hkRange(pages,hkCurrentPage,5).forEach(p=>{
    if(p==='…') html+=`<span class="px-2 text-neutral-400">…</span>`;
    else html+=btn(p,p,false,p===hkCurrentPage);
  });
  html+=btn('»', hkCurrentPage+1, hkCurrentPage===pages);
  cont.innerHTML=html;
  cont.querySelectorAll('button[data-hkpage]').forEach(b=>{
    b.addEventListener('click', ()=>{ const p=Number(b.dataset.hkpage); if(!Number.isNaN(p)){ hkCurrentPage=p; hkRenderAll(); }});
  });
}
function hkMarkSortHeaders(){
  document.querySelectorAll('th.hk-sortable').forEach(th=>{
    th.querySelector('.hk-ind')?.remove();
    if(th.dataset.hk===hkSortKey){
      const s=document.createElement('span'); s.className='hk-ind inline-block ml-1 text-neutral-400';
      s.innerHTML = hkSortDir==='asc' ? '▲' : '▼'; th.appendChild(s);
    }
  });
}
function hkRenderAll(){
  const filtered=hkGetFiltered(); const sorted=hkGetSorted(filtered);
  const {slice,total,pages}=hkGetPaged(sorted);
  hkRenderTable(slice); hkRenderPagination(total,pages); hkMarkSortHeaders();
}

/* ====== TABEL 3 (Kadaluwarsa) ====== */
/* Data = historyRows yang exp < hari ini; tanpa kolom & filter Penerima/Status */
const allHistory = @json($historyRows);
const todayYmd = new Date().toISOString().slice(0,10);
const exRows = allHistory.filter(r => String(r.exp) < todayYmd);

let exSortKey='id', exSortDir='asc', exCurrentPage=1, exPageSize=10;
const exToYmd = s=>String(s||'');
const exInRange=(d,f,t)=>{ if(!d) return true; const dd=exToYmd(d); if(f&&dd<exToYmd(f))return false; if(t&&dd>exToYmd(t))return false; return true; };

function exGetFiltered(){
  const q =(document.getElementById('exSearchInput').value||'').toLowerCase().trim();
  const g =document.getElementById('exGolSelect')?.value||'';
  const rh=document.getElementById('exRhesusSelect')?.value||'';
  const pr=document.getElementById('exProdukSelect')?.value||'';
  const mf=document.getElementById('exMasukFrom')?.value||'';
  const mt=document.getElementById('exMasukTo')?.value||'';

  return exRows.filter(o=>{
    const hitQ=!q||String(o.id).toLowerCase().includes(q)||String(o.penerima||'').toLowerCase().includes(q);
    const hitG=!g||o.gol===g;
    const hitR=!rh||o.rh===rh;
    const hitP=!pr||o.produk===pr;
    const hitM=exInRange(o.masuk,mf,mt);
    return hitQ&&hitG&&hitR&&hitP&&hitM;
  });
}
function exGetSorted(data){
  const cp=[...data];
  cp.sort((a,b)=>{ let va=a[exSortKey], vb=b[exSortKey];
    va=String(va??'').toLowerCase(); vb=String(vb??'').toLowerCase();
    if(va<vb) return exSortDir==='asc'?-1:1;
    if(va>vb) return exSortDir==='asc'?1:-1;
    return 0;
  });
  return cp;
}
function exGetPaged(data){
  const total=data.length, pages=Math.max(1,Math.ceil(total/exPageSize));
  exCurrentPage=Math.min(exCurrentPage,pages);
  const start=(exCurrentPage-1)*exPageSize;
  return {slice:data.slice(start,start+exPageSize), total, pages};
}
function exRenderTable(data){
  const tb=document.getElementById('exTableBody');
  if(!data.length){
    tb.innerHTML=`<tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>`;
    return;
  }
  tb.innerHTML = data.map(o=>`
    <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
      <td class="px-4 py-3 font-medium text-neutral-800">${o.id}</td>
      <td class="px-4 py-3">${dotGol(o.gol)}</td>
      <td class="px-4 py-3">${o.rh}</td>
      <td class="px-4 py-3">${badgeProduk(o.produk)}</td>
      <td class="px-4 py-3">${o.masuk}</td>
      <td class="px-4 py-3">${o.exp}</td>
    </tr>
  `).join('');
}
function exRange(totalPages,current,max=5){
  const pages=[]; const half=Math.floor(max/2);
  let start=Math.max(1,current-half), end=Math.min(totalPages,start+max-1);
  if(end-start+1<max) start=Math.max(1,end-max+1);
  if(start>1){ pages.push(1); if(start>2) pages.push('…'); }
  for(let i=start;i<=end;i++) pages.push(i);
  if(end<totalPages){ if(end<totalPages-1) pages.push('…'); pages.push(totalPages); }
  return pages;
}
function exRenderPagination(total,pages){
  const cont=document.getElementById('exPagination');
  const info=document.getElementById('exPageInfo');
  const start= total===0?0:(exCurrentPage-1)*exPageSize+1;
  const end=Math.min(exCurrentPage*exPageSize,total);
  info.textContent=`Menampilkan ${start}-${end} dari ${total} data`;
  if(pages<=1){ cont.innerHTML=''; return; }
  const btn=(label,page,disabled=false,active=false)=>`
    <button class="min-w-9 h-9 px-3 rounded-lg border text-sm
                   ${active?'bg-neutral-900 text-white border-neutral-900':'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'}
                   ${disabled?'opacity-50 cursor-not-allowed':''}"
            ${disabled?'disabled':''} data-expage="${page}">${label}</button>`;
  let html=''; html+=btn('«', exCurrentPage-1, exCurrentPage===1);
  exRange(pages,exCurrentPage,5).forEach(p=>{
    if(p==='…') html+=`<span class="px-2 text-neutral-400">…</span>`;
    else html+=btn(p,p,false,p===exCurrentPage);
  });
  html+=btn('»', exCurrentPage+1, exCurrentPage===pages);
  cont.innerHTML=html;
  cont.querySelectorAll('button[data-expage]').forEach(b=>{
    b.addEventListener('click', ()=>{ const p=Number(b.dataset.expage); if(!Number.isNaN(p)){ exCurrentPage=p; exRenderAll(); }});
  });
}
function exMarkSortHeaders(){
  document.querySelectorAll('th.ex-sortable').forEach(th=>{
    th.querySelector('.ex-ind')?.remove();
    if(th.dataset.ex===exSortKey){
      const s=document.createElement('span');
      s.className='ex-ind inline-block ml-1 text-neutral-400';
      s.innerHTML = exSortDir==='asc' ? '▲' : '▼';
      th.appendChild(s);
    }
  });
}
function exRenderAll(){
  const filtered=exGetFiltered();
  const sorted=exGetSorted(filtered);
  const {slice,total,pages}=exGetPaged(sorted);
  exRenderTable(slice); exRenderPagination(total,pages); exMarkSortHeaders();
}

/* ===== Mount semua tabel ===== */
document.addEventListener('DOMContentLoaded', ()=>{
  setTab('avail'); // default

  // Tabel 1
  document.getElementById('searchInput').addEventListener('input', ()=>{ currentPage=1; renderAll(); });
  const btn=document.getElementById('filterBtn'), menu=document.getElementById('filterMenu');
  const apply=document.getElementById('applyBtn'), reset=document.getElementById('resetBtn');
  btn.addEventListener('click',(e)=>{ e.stopPropagation(); menu.classList.toggle('hidden'); });
  document.addEventListener('click',(e)=>{ if(menu && btn && !menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden'); });
  apply.addEventListener('click', ()=>{ menu.classList.add('hidden'); currentPage=1; renderAll(); });
  reset.addEventListener('click', ()=>{ ['golSelect','rhesusSelect','kompSelect','masukFrom','masukTo','expFrom','expTo'].forEach(id=>{ const el=document.getElementById(id); if(el) el.value=''; }); currentPage=1; renderAll(); });
  document.getElementById('pageSize').addEventListener('change', (e)=>{ pageSize=Number(e.target.value)||10; currentPage=1; renderAll(); });
  document.querySelectorAll('th.sortable').forEach(th=>{
    th.addEventListener('click', ()=>{ const key=th.dataset.key; if(sortKey===key) sortDir=(sortDir==='asc'?'desc':'asc'); else { sortKey=key; sortDir='asc'; } renderAll(); });
  });
  renderAll();

  // Tabel 2
  document.getElementById('hkSearchInput').addEventListener('input', ()=>{ hkCurrentPage=1; hkRenderAll(); });
  const hkBtn=document.getElementById('hkFilterBtn'), hkMenu=document.getElementById('hkFilterMenu');
  const hkApply=document.getElementById('hkApplyBtn'), hkReset=document.getElementById('hkResetBtn');
  hkBtn.addEventListener('click',(e)=>{ e.stopPropagation(); hkMenu.classList.toggle('hidden'); });
  document.addEventListener('click',(e)=>{ if(hkMenu && hkBtn && !hkMenu.contains(e.target) && !hkBtn.contains(e.target)) hkMenu.classList.add('hidden'); });
  hkApply.addEventListener('click', ()=>{ hkMenu.classList.add('hidden'); hkCurrentPage=1; hkRenderAll(); });
  hkReset.addEventListener('click', ()=>{ ['hkGolSelect','hkRhesusSelect','hkProdukSelect','hkStatusSelect','hkMasukFrom','hkMasukTo'].forEach(id=>{ const el=document.getElementById(id); if(el) el.value=''; }); hkCurrentPage=1; hkRenderAll(); });
  document.getElementById('hkPageSize').addEventListener('change', (e)=>{ hkPageSize=Number(e.target.value)||10; hkCurrentPage=1; hkRenderAll(); });
  document.querySelectorAll('th.hk-sortable').forEach(th=>{
    th.addEventListener('click', ()=>{ const key=th.dataset.hk; if(hkSortKey===key) hkSortDir=(hkSortDir==='asc'?'desc':'asc'); else { hkSortKey=key; hkSortDir='asc'; } hkRenderAll(); });
  });
  hkRenderAll();

  // Tabel 3 (Kadaluwarsa)
  document.getElementById('exSearchInput').addEventListener('input', ()=>{ exCurrentPage=1; exRenderAll(); });
  const exBtn=document.getElementById('exFilterBtn'), exMenu=document.getElementById('exFilterMenu');
  const exApply=document.getElementById('exApplyBtn'), exReset=document.getElementById('exResetBtn');
  exBtn.addEventListener('click',(e)=>{ e.stopPropagation(); exMenu.classList.toggle('hidden'); });
  document.addEventListener('click',(e)=>{ if(exMenu && exBtn && !exMenu.contains(e.target) && !exBtn.contains(e.target)) exMenu.classList.add('hidden'); });
  exApply.addEventListener('click', ()=>{ exMenu.classList.add('hidden'); exCurrentPage=1; exRenderAll(); });
  exReset.addEventListener('click', ()=>{
    ['exGolSelect','exRhesusSelect','exProdukSelect','exMasukFrom','exMasukTo']
      .forEach(id=>{ const el=document.getElementById(id); if(el) el.value=''; });
    exCurrentPage=1; exRenderAll();
  });
  document.getElementById('exPageSize').addEventListener('change', (e)=>{ exPageSize=Number(e.target.value)||10; exCurrentPage=1; exRenderAll(); });
  document.querySelectorAll('th.ex-sortable').forEach(th=>{
    th.addEventListener('click', ()=>{ const key=th.dataset.ex; if(exSortKey===key) exSortDir=(exSortDir==='asc'?'desc':'asc'); else { exSortKey=key; exSortDir='asc'; } exRenderAll(); });
  });
  exRenderAll();
});
</script>

<style>
  th.sortable:hover,
  th.hk-sortable:hover,
  th.ex-sortable:hover { background-color: rgba(0,0,0,0.02); }

  /* Tombol toggle */
  .tabbtn{
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    border-radius: 0.75rem;
    border: 1px solid transparent;
    color: #525252;
    background: transparent;
    transition: background-color .15s ease, color .15s ease, border-color .15s ease;
  }
  .tabbtn.is-active{
    background: #171717;
    color: #fff;
    border-color: #171717;
  }
  .tabbtn:not(.is-active):hover{ background: #f5f5f5; }
</style>
@endsection
