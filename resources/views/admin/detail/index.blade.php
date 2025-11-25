{{-- resources/views/admin/detail/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Informasi Detail Darah')

@section('content')
    @php
        $rows = $rows ?? collect();
        $historyRows = $historyRows ?? collect();

        // Daftar lengkap kode komponen yang ingin selalu muncul di filter
        $kompAll = ['WB', 'PRC', 'TC', 'FFP', 'CRYO', 'LP', 'TCA', 'CP'];

        // Kumpulkan opsi komponen dari data nyata
        $kompOpts = collect($rows)->pluck('komponen')->filter()->unique()->values()->all();

        // Jika tidak ada data sama sekali → pakai daftar default
        if (empty($kompOpts)) {
            $kompOpts = $kompAll;
        } else {
            // Jika ada data, gabungkan dengan daftar default supaya semua kode muncul
            $kompOpts = collect($kompOpts)->merge($kompAll)->unique()->values()->all();
        }

        $gOpts = ['' => 'Semua', 'A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O'];
    @endphp

    <div class="space-y-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-semibold md:text-3xl">Informasi Detail Darah</h1>
            <p class="text-sm text-neutral-500">Data stok unit darah yang tersedia, keluar, dan kadaluwarsa</p>
        </div>

        <div class="inline-flex rounded-2xl border border-neutral-200 bg-neutral-50 p-1 shadow-sm text-sm">
            <button id="btnAvail" type="button" class="tabbtn is-active">
                Tersedia
            </button>
            <button id="btnUnavail" type="button" class="tabbtn">
                Keluar
            </button>
            <button id="btnExpired" type="button" class="tabbtn">
                Kadaluwarsa
            </button>
        </div>


        {{-- ========================= --}}
        {{-- SECTION: TABEL 1 (Tersedia) --}}
        {{-- ========================= --}}
        <section id="secAvail" class="mt-6 space-y-6">
            {{-- Toolbar ala Verifikasi --}}
            <div class="space-y-3">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    {{-- Search --}}
                    <div class="relative flex-1 min-w-0">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                            <svg class="size-5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" />
                            </svg>
                        </span>
                        <input id="searchInput" type="text"
                            class="w-full rounded-2xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:border-navy-300 focus:outline-none focus:ring-2 focus:ring-navy-200"
                            placeholder="Cari ID darah atau komponen…" />
                    </div>

                    {{-- Filter dropdown (Produk & Gol) --}}
                    <div class="relative">
                        <button type="button" id="filterBtn"
                            class="inline-flex min-h-10 items-center gap-2 rounded-2xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-navy-200"
                            aria-haspopup="menu" aria-expanded="false" aria-controls="filterMenu">
                            <svg class="size-5 text-neutral-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="M3 6h18M6 12h12M10 18h4" />
                            </svg>
                            <span>Filter</span>
                        </button>

                        <div id="filterMenu" role="menu" aria-labelledby="filterBtn"
                            class="absolute right-0 z-20 mt-2 hidden w-[22rem] rounded-2xl border border-neutral-200 bg-white p-3 shadow-xl">
                            <div class="space-y-3">
                                {{-- Produk --}}
                                <div class="relative">
                                    <label class="text-xs font-medium text-neutral-500">Produk Darah</label>
                                    <button type="button" id="produkBtn"
                                        class="mt-1 flex w-full items-center justify-between rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm transition hover:bg-blue-50/40 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                        aria-haspopup="menu" aria-expanded="false" aria-controls="produkMenu">
                                        <span id="produkLabel" class="truncate">Semua Produk</span>
                                        <svg class="size-4 shrink-0 text-neutral-500" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M6 9l6 6 6-6" />
                                        </svg>
                                    </button>

                                    <div id="produkMenu" role="menu" aria-labelledby="produkBtn"
                                        class="absolute left-0 z-30 mt-1 hidden w-full rounded-2xl border border-neutral-100 bg-white p-1 shadow-2xl ring-1 ring-black/5">
                                        {{-- Semua --}}
                                        <button type="button" role="menuitemradio"
                                            class="produk-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
                                            data-value="">
                                            <span class="truncate">Semua Produk</span>
                                            <svg class="check-icon size-4 opacity-0" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>

                                        {{-- Semua kode produk dari $kompOpts --}}
                                        @foreach ($kompOpts as $opt)
                                            <button type="button" role="menuitemradio"
                                                class="produk-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
                                                data-value="{{ $opt }}">
                                                <span class="truncate">{{ $opt }}</span>
                                                <svg class="check-icon size-4 opacity-0" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Golongan --}}
                                <div class="relative">
                                    <label class="text-xs font-medium text-neutral-500">Golongan Darah</label>
                                    <button type="button" id="golBtn"
                                        class="mt-1 flex w-full items-center justify-between rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm transition hover:bg-emerald-50/40 focus:outline-none focus:ring-2 focus:ring-navy-200"
                                        aria-haspopup="menu" aria-expanded="false" aria-controls="golMenu">
                                        <span id="golLabel" class="truncate">Semua</span>
                                        <svg class="size-4 shrink-0 text-neutral-500" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M6 9l6 6 6-6" />
                                        </svg>
                                    </button>

                                    <div id="golMenu" role="menu" aria-labelledby="golBtn"
                                        class="absolute left-0 z-30 mt-1 hidden w-full rounded-2xl border border-neutral-100 bg-white p-1 shadow-2xl ring-1 ring-black/5">
                                        @foreach ($gOpts as $val => $lab)
                                            <button type="button" role="menuitemradio"
                                                class="gol-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
                                                data-value="{{ $val }}">
                                                <span class="truncate">{{ $lab }}</span>
                                                <svg class="check-icon size-4 opacity-0" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-1">
                                    <button type="button" id="resetBtn"
                                        class="text-sm text-neutral-600 hover:underline">
                                        Reset
                                    </button>
                                    <button type="button" id="applyBtn"
                                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm">
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Page size --}}
                    <div class="relative sm:ml-auto">
                        <button type="button" id="pageSizeBtn"
                            class="inline-flex min-h-10 items-center gap-2 rounded-2xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-navy-200"
                            aria-haspopup="menu" aria-expanded="false" aria-controls="pageSizeMenu">
                            <svg class="size-5 text-neutral-600" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="M4 8h16M4 16h10" />
                            </svg>
                            <span>Baris: <strong id="pageSizeLabel"
                                    class="font-semibold text-neutral-800">10</strong></span>
                            <svg class="size-4 text-neutral-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 12a1 1 0 0 1-.7-.29l-4-4a1 1 0 0 1 1.4-1.42L10 9.59l3.3-3.3a1 1 0 1 1 1.4 1.42l-4 4A1 1 0 0 1 10 12Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div id="pageSizeMenu"
                            class="absolute right-0 z-20 mt-2 hidden w-40 rounded-2xl border border-neutral-100 bg-white p-1 shadow-xl">
                            <button type="button" role="menuitemradio"
                                class="page-size-item w-full rounded-xl px-3 py-2 text-left text-sm" data-size="5">
                                5 per halaman
                            </button>
                            <button type="button" role="menuitemradio"
                                class="page-size-item w-full rounded-xl px-3 py-2 text-left text-sm" data-size="10">
                                10 per halaman
                            </button>
                            <button type="button" role="menuitemradio"
                                class="page-size-item w-full rounded-xl px-3 py-2 text-left text-sm" data-size="20">
                                20 per halaman
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Tersedia --}}
            <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-neutral-50 text-neutral-600">
                            <tr class="text-left">
                                <th data-key="id_darah" class="sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    ID Darah
                                </th>
                                <th data-key="gol_darah"
                                    class="sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Golongan Darah
                                </th>
                                <th data-key="rhesus" class="sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Rhesus
                                </th>
                                <th data-key="komponen" class="sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Produk Darah
                                </th>
                                <th data-key="tgl_masuk"
                                    class="sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Tanggal Masuk
                                </th>
                                <th data-key="tgl_kadaluarsa"
                                    class="sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Tanggal Kadaluwarsa
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                <div id="pageInfo" class="text-sm text-neutral-600"></div>
                <div id="pagination" class="flex items-center gap-2"></div>
            </div>
        </section>

        {{-- ========================= --}}
        {{-- SECTION: TABEL 2 (Keluar / Tidak Tersedia) --}}
        {{-- ========================= --}}
        <section id="secUnavail" class="mt-6 hidden space-y-6">
            {{-- Toolbar ala Verifikasi --}}
            <div class="space-y-3">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    {{-- Search --}}
                    <div class="relative flex-1 min-w-0">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                            <svg class="size-5 text-neutral-400" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" />
                            </svg>
                        </span>
                        <input id="hkSearchInput" type="text"
                            class="w-full rounded-2xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:border-navy-300 focus:outline-none focus:ring-2 focus:ring-navy-200"
                            placeholder="Cari ID darah atau penerima…" />
                    </div>

<<<<<<< HEAD
  {{-- ========================= --}}
  {{-- SECTION: TABEL 2 (Keluar / Tidak Tersedia) --}}
  {{-- ========================= --}}
  <section id="secUnavail"
           class="mt-6 hidden space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div class="flex w-full items-center gap-2 sm:flex-1">
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
          <input id="hkSearchInput"
                 type="text"
                 class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-900/10"
                 placeholder="Cari ID darah atau penerima…">
        </div>

        <div class="relative">
          <button id="hkFilterBtn"
                  type="button"
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
          <div id="hkFilterMenu"
               class="absolute right-0 z-20 mt-2 hidden w-[22rem] rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
            <div class="grid gap-3 sm:grid-cols-2">
              <div>
                <label class="text-xs font-medium text-neutral-500">Golongan</label>
                <select id="hkGolSelect"
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                  <option value="">Semua</option>
                  @foreach (['A', 'B', 'AB', 'O'] as $g)
                    <option value="{{ $g }}">{{ $g }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="text-xs font-medium text-neutral-500">Rhesus</label>
                <select id="hkRhesusSelect"
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                  <option value="">Semua</option>
                  <option value="Rh+">Rh+</option>
                  <option value="Rh-">Rh-</option>
                </select>
              </div>
              <div>
                <label class="text-xs font-medium text-neutral-500">Produk</label>
                <select id="hkProdukSelect"
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                  <option value="">Semua</option>
                  @foreach ($kompOpts as $opt)
                    <option value="{{ $opt }}">{{ $opt }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="text-xs font-medium text-neutral-500">Status</label>
                <select id="hkStatusSelect"
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                  <option value="">Semua</option>
                  <option value="Approved">Approved</option>
                  <option value="Pending">Pending</option>
                  <option value="Rejected">Rejected</option>
                </select>
              </div>
              <div>
                <label class="text-xs font-medium text-neutral-500">Tgl Masuk (dari)</label>
                <input type="date"
                       id="hkMasukFrom"
                       class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              </div>
              <div>
                <label class="text-xs font-medium text-neutral-500">Tgl Masuk (hingga)</label>
                <input type="date"
                       id="hkMasukTo"
                       class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              </div>
              <div class="flex items-center justify-between sm:col-span-2">
                <button type="button"
                        id="hkResetBtn"
                        class="text-sm text-neutral-600 hover:underline">Reset</button>
                <button type="button"
                        id="hkApplyBtn"
                        class="rounded-lg bg-neutral-900 px-3 py-1.5 text-sm text-white hover:bg-neutral-800">Terapkan</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <label for="hkPageSize"
               class="text-sm text-neutral-600">Baris:</label>
        <select id="hkPageSize"
                class="rounded-xl border border-neutral-200 bg-white px-2 py-2 text-sm">
          <option>5</option>
          <option selected>10</option>
          <option>20</option>
        </select>
      </div>
    </div>
=======
                    {{-- Filter dropdown (Produk & Gol) --}}
                    <div class="relative">
                        <button type="button" id="hkFilterBtn"
                            class="inline-flex min-h-10 items-center gap-2 rounded-2xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-navy-200"
                            aria-haspopup="menu" aria-expanded="false" aria-controls="hkFilterMenu">
                            <svg class="size-5 text-neutral-600" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="M3 6h18M6 12h12M10 18h4" />
                            </svg>
                            <span>Filter</span>
                        </button>
>>>>>>> 26a6e90c2beefc016005be09f4a340fa8bfff97c

                        <div id="hkFilterMenu" role="menu" aria-labelledby="hkFilterBtn"
                            class="absolute right-0 z-20 mt-2 hidden w-[22rem] rounded-2xl border border-neutral-200 bg-white p-3 shadow-xl">
                            <div class="space-y-3">
                                {{-- Produk --}}
                                <div class="relative">
                                    <label class="text-xs font-medium text-neutral-500">Produk Darah</label>
                                    <button type="button" id="hkProdukBtn"
                                        class="mt-1 flex w-full items-center justify-between rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm transition hover:bg-blue-50/40 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                        aria-haspopup="menu" aria-expanded="false" aria-controls="hkProdukMenu">
                                        <span id="hkProdukLabel" class="truncate">Semua Produk</span>
                                        <svg class="size-4 shrink-0 text-neutral-500" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M6 9l6 6 6-6" />
                                        </svg>
                                    </button>

                                    <div id="hkProdukMenu" role="menu" aria-labelledby="hkProdukBtn"
                                        class="absolute left-0 z-30 mt-1 hidden w-full rounded-2xl border border-neutral-100 bg-white p-1 shadow-2xl ring-1 ring-black/5">
                                        <button type="button" role="menuitemradio"
                                            class="hk-produk-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
                                            data-value="">
                                            <span class="truncate">Semua Produk</span>
                                            <svg class="check-icon size-4 opacity-0" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>

<<<<<<< HEAD
  <!-- SECTION: TABEL 3 (Kadaluwarsa) -->
  <section id="secExpired"
           class="mt-6 hidden space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div class="flex w-full items-center gap-2 sm:flex-1">
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
          <input id="exSearchInput"
                 type="text"
                 class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-900/10"
                 placeholder="Cari ID darah atau produk…">
        </div>

        <div class="relative">
          <button id="exFilterBtn"
                  type="button"
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
          <div id="exFilterMenu"
               class="absolute right-0 z-20 mt-2 hidden w-[22rem] rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
            <div class="grid gap-3 sm:grid-cols-2">
              <div>
                <label class="text-xs font-medium text-neutral-500">Golongan</label>
                <select id="exGolSelect"
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                  <option value="">Semua</option>
                  @foreach (['A', 'B', 'AB', 'O'] as $g)
                    <option value="{{ $g }}">{{ $g }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="text-xs font-medium text-neutral-500">Rhesus</label>
                <select id="exRhesusSelect"
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                  <option value="">Semua</option>
                  <option value="Rh+">Rh+</option>
                  <option value="Rh-">Rh-</option>
                </select>
              </div>
              <div class="sm:col-span-2">
                <label class="text-xs font-medium text-neutral-500">Produk</label>
                <select id="exProdukSelect"
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                  <option value="">Semua</option>
                  @foreach ($kompOpts as $opt)
                    <option value="{{ $opt }}">{{ $opt }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="text-xs font-medium text-neutral-500">Tgl Masuk (dari)</label>
                <input type="date"
                       id="exMasukFrom"
                       class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              </div>
              <div>
                <label class="text-xs font-medium text-neutral-500">Tgl Masuk (hingga)</label>
                <input type="date"
                       id="exMasukTo"
                       class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              </div>
              <div class="flex items-center justify-between sm:col-span-2">
                <button type="button"
                        id="exResetBtn"
                        class="text-sm text-neutral-600 hover:underline">Reset</button>
                <button type="button"
                        id="exApplyBtn"
                        class="rounded-lg bg-neutral-900 px-3 py-1.5 text-sm text-white hover:bg-neutral-800">Terapkan</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <label for="exPageSize"
               class="text-sm text-neutral-600">Baris:</label>
        <select id="exPageSize"
                class="rounded-xl border border-neutral-200 bg-white px-2 py-2 text-sm">
          <option>5</option>
          <option selected>10</option>
          <option>20</option>
        </select>
      </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-neutral-50 text-neutral-600">
            <tr class="text-left">
              <th data-ex="id"
                  class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">ID Darah</th>
              <th data-ex="gol"
                  class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">Golongan Darah</th>
              <th data-ex="rh"
                  class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">Rhesus</th>
              <th data-ex="produk"
                  class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">Produk Darah</th>
              <th data-ex="masuk"
                  class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">Tanggal Masuk</th>
              <th data-ex="exp"
                  class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">Tanggal Kadaluwarsa</th>
            </tr>
          </thead>
          <tbody id="exTableBody"></tbody>
        </table>
      </div>
    </div>
=======
                                        @foreach ($kompOpts as $opt)
                                            <button type="button" role="menuitemradio"
                                                class="hk-produk-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
                                                data-value="{{ $opt }}">
                                                <span class="truncate">{{ $opt }}</span>
                                                <svg class="check-icon size-4 opacity-0" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
>>>>>>> 26a6e90c2beefc016005be09f4a340fa8bfff97c

                                {{-- Golongan --}}
                                <div class="relative">
                                    <label class="text-xs font-medium text-neutral-500">Golongan Darah</label>
                                    <button type="button" id="hkGolBtn"
                                        class="mt-1 flex w-full items-center justify-between rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm transition hover:bg-emerald-50/40 focus:outline-none focus:ring-2 focus:ring-navy-200"
                                        aria-haspopup="menu" aria-expanded="false" aria-controls="hkGolMenu">
                                        <span id="hkGolLabel" class="truncate">Semua</span>
                                        <svg class="size-4 shrink-0 text-neutral-500" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M6 9l6 6 6-6" />
                                        </svg>
                                    </button>

                                    <div id="hkGolMenu" role="menu" aria-labelledby="hkGolBtn"
                                        class="absolute left-0 z-30 mt-1 hidden w-full rounded-2xl border border-neutral-100 bg-white p-1 shadow-2xl ring-1 ring-black/5">
                                        @foreach ($gOpts as $val => $lab)
                                            <button type="button" role="menuitemradio"
                                                class="hk-gol-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
                                                data-value="{{ $val }}">
                                                <span class="truncate">{{ $lab }}</span>
                                                <svg class="check-icon size-4 opacity-0" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-1">
                                    <button type="button" id="hkResetBtn"
                                        class="text-sm text-neutral-600 hover:underline">
                                        Reset
                                    </button>
                                    <button type="button" id="hkApplyBtn"
                                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm">
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Page size --}}
                    <div class="relative sm:ml-auto">
                        <button type="button" id="hkPageSizeBtn"
                            class="inline-flex min-h-10 items-center gap-2 rounded-2xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-navy-200"
                            aria-haspopup="menu" aria-expanded="false" aria-controls="hkPageSizeMenu">
                            <svg class="size-5 text-neutral-600" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="M4 8h16M4 16h10" />
                            </svg>
                            <span>
                                Baris:
                                <strong id="hkPageSizeLabel" class="font-semibold text-neutral-800">10</strong>
                            </span>
                            <svg class="size-4 text-neutral-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 12a1 1 0 0 1-.7-.29l-4-4a1 1 0 0 1 1.4-1.42L10 9.59l3.3-3.3a1 1 0 1 1 1.4 1.42l-4 4A1 1 0 0 1 10 12Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div id="hkPageSizeMenu"
                            class="absolute right-0 z-20 mt-2 hidden w-40 rounded-2xl border border-neutral-100 bg-white p-1 shadow-xl">
                            <button type="button" role="menuitemradio"
                                class="hk-page-size-item w-full rounded-xl px-3 py-2 text-left text-sm" data-size="5">
                                5 per halaman
                            </button>
                            <button type="button" role="menuitemradio"
                                class="hk-page-size-item w-full rounded-xl px-3 py-2 text-left text-sm" data-size="10">
                                10 per halaman
                            </button>
                            <button type="button" role="menuitemradio"
                                class="hk-page-size-item w-full rounded-xl px-3 py-2 text-left text-sm" data-size="20">
                                20 per halaman
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Keluar --}}
            <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-neutral-50 text-neutral-600">
                            <tr class="text-left">
                                <th data-hk="id" class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    ID Darah
                                </th>
                                <th data-hk="gol" class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Golongan Darah
                                </th>
                                <th data-hk="rh" class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Rhesus
                                </th>
                                <th data-hk="produk" class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Produk Darah
                                </th>
                                <th data-hk="masuk" class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Tanggal Masuk
                                </th>
                                <th data-hk="exp" class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Tanggal Kadaluwarsa
                                </th>
                                <th data-hk="penerima"
                                    class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Penerima
                                </th>
                                <th data-hk="status" class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody id="hkTableBody"></tbody>
                    </table>
                </div>
            </div>

<<<<<<< HEAD
    /* ====== DATA dari Controller (fallback ke array kosong) ====== */
    const rows = Array.isArray(@json($rows ?? [])) ? @json($rows ?? []) : [];
    const hkRows = Array.isArray(@json($historyRows ?? [])) ? @json($historyRows ?? []) : [];
    const expiredRows = Array.isArray(@json($expiredRows ?? [])) ? @json($expiredRows ?? []) : [];
=======
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                <div id="hkPageInfo" class="text-sm text-neutral-600"></div>
                <div id="hkPagination" class="flex items-center gap-2"></div>
            </div>
        </section>
>>>>>>> 26a6e90c2beefc016005be09f4a340fa8bfff97c

        {{-- ========================= --}}
        {{-- SECTION: TABEL 3 (Kadaluwarsa) --}}
        {{-- ========================= --}}
        <section id="secExpired" class="mt-6 hidden space-y-6">
            {{-- Toolbar ala Verifikasi --}}
            <div class="space-y-3">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    {{-- Search --}}
                    <div class="relative flex-1 min-w-0">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                            <svg class="size-5 text-neutral-400" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" />
                            </svg>
                        </span>
                        <input id="exSearchInput" type="text"
                            class="w-full rounded-2xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:border-navy-300 focus:outline-none focus:ring-2 focus:ring-navy-200"
                            placeholder="Cari ID darah…" />
                    </div>

                    {{-- Filter dropdown (Produk & Gol) --}}
                    <div class="relative">
                        <button type="button" id="exFilterBtn"
                            class="inline-flex min-h-10 items-center gap-2 rounded-2xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-navy-200"
                            aria-haspopup="menu" aria-expanded="false" aria-controls="exFilterMenu">
                            <svg class="size-5 text-neutral-600" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="M3 6h18M6 12h12M10 18h4" />
                            </svg>
                            <span>Filter</span>
                        </button>

                        <div id="exFilterMenu" role="menu" aria-labelledby="exFilterBtn"
                            class="absolute right-0 z-20 mt-2 hidden w-[22rem] rounded-2xl border border-neutral-200 bg-white p-3 shadow-xl">
                            <div class="space-y-3">
                                {{-- Produk --}}
                                <div class="relative">
                                    <label class="text-xs font-medium text-neutral-500">Produk Darah</label>
                                    <button type="button" id="exProdukBtn"
                                        class="mt-1 flex w-full items-center justify-between rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm transition hover:bg-blue-50/40 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                        aria-haspopup="menu" aria-expanded="false" aria-controls="exProdukMenu">
                                        <span id="exProdukLabel" class="truncate">Semua Produk</span>
                                        <svg class="size-4 shrink-0 text-neutral-500" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M6 9l6 6 6-6" />
                                        </svg>
                                    </button>

                                    <div id="exProdukMenu" role="menu" aria-labelledby="exProdukBtn"
                                        class="absolute left-0 z-30 mt-1 hidden w-full rounded-2xl border border-neutral-100 bg-white p-1 shadow-2xl ring-1 ring-black/5">
                                        <button type="button" role="menuitemradio"
                                            class="ex-produk-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
                                            data-value="">
                                            <span class="truncate">Semua Produk</span>
                                            <svg class="check-icon size-4 opacity-0" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>

                                        @foreach ($kompOpts as $opt)
                                            <button type="button" role="menuitemradio"
                                                class="ex-produk-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
                                                data-value="{{ $opt }}">
                                                <span class="truncate">{{ $opt }}</span>
                                                <svg class="check-icon size-4 opacity-0" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Golongan --}}
                                <div class="relative">
                                    <label class="text-xs font-medium text-neutral-500">Golongan Darah</label>
                                    <button type="button" id="exGolBtn"
                                        class="mt-1 flex w-full items-center justify-between rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm transition hover:bg-emerald-50/40 focus:outline-none focus:ring-2 focus:ring-navy-200"
                                        aria-haspopup="menu" aria-expanded="false" aria-controls="exGolMenu">
                                        <span id="exGolLabel" class="truncate">Semua</span>
                                        <svg class="size-4 shrink-0 text-neutral-500" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M6 9l6 6 6-6" />
                                        </svg>
                                    </button>

                                    <div id="exGolMenu" role="menu" aria-labelledby="exGolBtn"
                                        class="absolute left-0 z-30 mt-1 hidden w-full rounded-2xl border border-neutral-100 bg-white p-1 shadow-2xl ring-1 ring-black/5">
                                        @foreach ($gOpts as $val => $lab)
                                            <button type="button" role="menuitemradio"
                                                class="ex-gol-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
                                                data-value="{{ $val }}">
                                                <span class="truncate">{{ $lab }}</span>
                                                <svg class="check-icon size-4 opacity-0" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-1">
                                    <button type="button" id="exResetBtn"
                                        class="text-sm text-neutral-600 hover:underline">
                                        Reset
                                    </button>
                                    <button type="button" id="exApplyBtn"
                                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm">
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Page size --}}
                    <div class="relative sm:ml-auto">
                        <button type="button" id="exPageSizeBtn"
                            class="inline-flex min-h-10 items-center gap-2 rounded-2xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-navy-200"
                            aria-haspopup="menu" aria-expanded="false" aria-controls="exPageSizeMenu">
                            <svg class="size-5 text-neutral-600" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="M4 8h16M4 16h10" />
                            </svg>
                            <span>
                                Baris:
                                <strong id="exPageSizeLabel" class="font-semibold text-neutral-800">10</strong>
                            </span>
                            <svg class="size-4 text-neutral-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 12a1 1 0 0 1-.7-.29l-4-4a1 1 0 0 1 1.4-1.42L10 9.59l3.3-3.3a1 1 0 1 1 1.4 1.42l-4 4A1 1 0 0 1 10 12Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div id="exPageSizeMenu"
                            class="absolute right-0 z-20 mt-2 hidden w-40 rounded-2xl border border-neutral-100 bg-white p-1 shadow-xl">
                            <button type="button" role="menuitemradio"
                                class="ex-page-size-item w-full rounded-xl px-3 py-2 text-left text-sm" data-size="5">
                                5 per halaman
                            </button>
                            <button type="button" role="menuitemradio"
                                class="ex-page-size-item w-full rounded-xl px-3 py-2 text-left text-sm" data-size="10">
                                10 per halaman
                            </button>
                            <button type="button" role="menuitemradio"
                                class="ex-page-size-item w-full rounded-xl px-3 py-2 text-left text-sm" data-size="20">
                                20 per halaman
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Kadaluwarsa dari Historis (keluar) --}}
            <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-neutral-50 text-neutral-600">
                            <tr class="text-left">
                                <th data-ex="id" class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    ID Darah
                                </th>
                                <th data-ex="gol" class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Golongan Darah
                                </th>
                                <th data-ex="rh" class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Rhesus
                                </th>
                                <th data-ex="produk" class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Produk Darah
                                </th>
                                <th data-ex="masuk" class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Tanggal Masuk
                                </th>
                                <th data-ex="exp" class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">
                                    Tanggal Kadaluwarsa
                                </th>
                            </tr>
                        </thead>
                        <tbody id="exTableBody"></tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                <div id="exPageInfo" class="text-sm text-neutral-600"></div>
                <div id="exPagination" class="flex items-center gap-2"></div>
            </div>
        </section>

        <script>
            /* ===== Utilities badge ===== */
            const dotGol = g =>
                `<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-rose-50 text-rose-500 text-xs font-semibold">${g}</span>`;

            const badgeProduk = p =>
                `<span class="inline-block rounded-full border border-sky-200 bg-sky-50 px-2 py-0.5 text-xs text-sky-700">${p}</span>`;

            function badgeStatus(s) {
                const map = {
                    Approved: 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    Pending: 'bg-amber-50 text-amber-700 border-amber-200',
                    Rejected: 'bg-rose-50 text-rose-700 border-rose-200',
                };

                return `<span class="inline-block rounded-full px-3 py-0.5 text-xs border ${
                map[s] || 'bg-neutral-50 text-neutral-600 border-neutral-200'
            }">${s}</span>`;
            }

            function formatDate(dateString) {
                if (!dateString) return '-';
                const date = new Date(dateString);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}-${month}-${year}`;
            }

            /* ===== Toggle 3 section ===== */
            const secAvail = document.getElementById('secAvail');
            const secUnavail = document.getElementById('secUnavail');
            const secExpired = document.getElementById('secExpired');
            const btnAvail = document.getElementById('btnAvail');
            const btnUnavail = document.getElementById('btnUnavail');
            const btnExpired = document.getElementById('btnExpired');

<<<<<<< HEAD
    /* ====== TABEL 3 (Kadaluwarsa) ====== */
    // Ambil data expired yang sudah dikirim dari controller
    const exRows = expiredRows;
=======
            function setTab(active) {
                if (secAvail) secAvail.classList.toggle('hidden', active !== 'avail');
                if (secUnavail) secUnavail.classList.toggle('hidden', active !== 'unavail');
                if (secExpired) secExpired.classList.toggle('hidden', active !== 'expired');
>>>>>>> 26a6e90c2beefc016005be09f4a340fa8bfff97c

                [btnAvail, btnUnavail, btnExpired].forEach(b => b?.classList.remove('is-active'));
                (active === 'avail' ?
                    btnAvail :
                    active === 'unavail' ?
                    btnUnavail :
                    btnExpired
                )?.classList.add('is-active');

                ['filterMenu', 'hkFilterMenu', 'exFilterMenu'].forEach(id =>
                    document.getElementById(id)?.classList.add('hidden'),
                );
            }

            btnAvail?.addEventListener('click', () => setTab('avail'));
            btnUnavail?.addEventListener('click', () => setTab('unavail'));
            btnExpired?.addEventListener('click', () => setTab('expired'));

            /* ====== DATA dari Controller ====== */
            const rows = Array.isArray(@json($rows ?? [])) ? @json($rows ?? []) : [];
            const hkRows = Array.isArray(@json($historyRows ?? [])) ? @json($historyRows ?? []) : [];

            /* ====================== */
            /* ====== TABEL 1 ======= */
            /* ====================== */
            let sortKey = 'id_darah';
            let sortDir = 'asc';
            let currentPage = 1;
            let pageSize = 10;
            let produkSelected = '';
            let golSelected = '';

            function getFiltered() {
                const q = (document.getElementById('searchInput')?.value || '').toLowerCase().trim();
                const gol = golSelected || '';
                const kp = produkSelected || '';

                return rows.filter(o => {
                    const hitQ = !q ||
                        String(o.id_darah).toLowerCase().includes(q) ||
                        String(o.komponen).toLowerCase().includes(q);
                    const hitG = !gol || o.gol_darah === gol;
                    const hitK = !kp || o.komponen === kp;
                    return hitQ && hitG && hitK;
                });
            }

            function getSorted(data) {
                const cp = [...data];
                cp.sort((a, b) => {
                    let va = a[sortKey];
                    let vb = b[sortKey];
                    va = String(va ?? '').toLowerCase();
                    vb = String(vb ?? '').toLowerCase();
                    if (va < vb) return sortDir === 'asc' ? -1 : 1;
                    if (va > vb) return sortDir === 'asc' ? 1 : -1;
                    return 0;
                });
                return cp;
            }

            function getPaged(data) {
                const total = data.length;
                const pages = Math.max(1, Math.ceil(total / pageSize));
                currentPage = Math.min(currentPage, pages);
                const start = (currentPage - 1) * pageSize;

                return {
                    slice: data.slice(start, start + pageSize),
                    total,
                    pages,
                };
            }

            function renderTable(data) {
                const tb = document.getElementById('tableBody');
                if (!tb) return;

                if (!data.length) {
                    tb.innerHTML =
                        '<tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>';
                    return;
                }

                tb.innerHTML = data
                    .map(
                        o => `
                        <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
                            <td class="px-4 py-3 font-medium text-neutral-800">${o.id_darah}</td>
                            <td class="px-4 py-3">${dotGol(o.gol_darah)}</td>
                            <td class="px-4 py-3">${o.rhesus}</td>
                            <td class="px-4 py-3">${badgeProduk(o.komponen)}</td>
                            <td class="px-4 py-3">${formatDate(o.tgl_masuk)}</td>
                            <td class="px-4 py-3">${formatDate(o.tgl_kadaluarsa)}</td>
                        </tr>`,
                    )
                    .join('');
            }

            function getPageRange(totalPages, current, max = 5) {
                const pages = [];
                const half = Math.floor(max / 2);
                let start = Math.max(1, current - half);
                let end = Math.min(totalPages, start + max - 1);

                if (end - start + 1 < max) {
                    start = Math.max(1, end - max + 1);
                }

                if (start > 1) {
                    pages.push(1);
                    if (start > 2) pages.push('…');
                }

                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }

                if (end < totalPages) {
                    if (end < totalPages - 1) pages.push('…');
                    pages.push(totalPages);
                }

                return pages;
            }

            function renderPagination(total, pages) {
                const cont = document.getElementById('pagination');
                const info = document.getElementById('pageInfo');
                if (!cont || !info) return;

                const start = total === 0 ? 0 : (currentPage - 1) * pageSize + 1;
                const end = Math.min(currentPage * pageSize, total);

                info.textContent = total > 0 ? `Menampilkan ${start}-${end} dari ${total} data` : 'Tidak ada data';

                if (pages <= 1) {
                    cont.innerHTML = '';
                    return;
                }

                const btn = (label, page, disabled = false, active = false) => `
                <button
                    class="min-w-9 h-9 px-3 rounded-2xl border text-sm
                    ${disabled ? 'opacity-50 cursor-not-allowed' : ''}
                    ${
                        active
                            ? 'bg-sky-600 text-white border-sky-600 shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-200'
                            : 'bg-white text-neutral-700 border-neutral-200 hover:bg-neutral-50 hover:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-sky-200'
                    }"
                    ${disabled ? 'disabled' : ''}
                    data-page="${page}"
                >
                    ${label}
                </button>
            `;

                let html = '';
                html += btn('«', currentPage - 1, currentPage === 1);

                const range = getPageRange(pages, currentPage, 5);
                range.forEach(p => {
                    if (p === '…') {
                        html += '<span class="px-2 text-neutral-400">…</span>';
                    } else {
                        html += btn(p, p, false, p === currentPage);
                    }
                });

                html += btn('»', currentPage + 1, currentPage === pages);
                cont.innerHTML = html;

                cont.querySelectorAll('button[data-page]').forEach(b => {
                    b.addEventListener('click', () => {
                        const p = Number(b.dataset.page);
                        if (!Number.isNaN(p)) {
                            currentPage = p;
                            renderAll();
                        }
                    });
                });
            }

            function markSortHeaders() {
                document.querySelectorAll('th.sortable').forEach(th => {
                    th.querySelector('.sort-ind')?.remove();
                    if (th.dataset.key === sortKey) {
                        const s = document.createElement('span');
                        s.className = 'sort-ind inline-block ml-1 text-neutral-400';
                        s.innerHTML = sortDir === 'asc' ? '▲' : '▼';
                        th.appendChild(s);
                    }
                });
            }

            function renderAll() {
                const filtered = getFiltered();
                const sorted = getSorted(filtered);
                const {
                    slice,
                    total,
                    pages
                } = getPaged(sorted);
                renderTable(slice);
                renderPagination(total, pages);
                markSortHeaders();
            }

            /* ====================== */
            /* ====== TABEL 2 ======= */
            /* ====================== */
            let hkSortKey = 'id';
            let hkSortDir = 'asc';
            let hkCurrentPage = 1;
            let hkPageSize = 10;
            let hkProdukSelected = '';
            let hkGolSelected = '';

            function hkGetFiltered() {
                const q = (document.getElementById('hkSearchInput')?.value || '').toLowerCase().trim();
                const g = hkGolSelected || '';
                const pr = hkProdukSelected || '';

                return hkRows.filter(o => {
                    const hitQ = !q ||
                        String(o.id).toLowerCase().includes(q) ||
                        String(o.penerima || '').toLowerCase().includes(q);
                    const hitG = !g || o.gol === g;
                    const hitP = !pr || o.produk === pr;
                    return hitQ && hitG && hitP;
                });
            }

            function hkGetSorted(data) {
                const cp = [...data];
                cp.sort((a, b) => {
                    let va = a[hkSortKey];
                    let vb = b[hkSortKey];
                    va = String(va ?? '').toLowerCase();
                    vb = String(vb ?? '').toLowerCase();
                    if (va < vb) return hkSortDir === 'asc' ? -1 : 1;
                    if (va > vb) return hkSortDir === 'asc' ? 1 : -1;
                    return 0;
                });
                return cp;
            }

            function hkGetPaged(data) {
                const total = data.length;
                const pages = Math.max(1, Math.ceil(total / hkPageSize));
                hkCurrentPage = Math.min(hkCurrentPage, pages);
                const start = (hkCurrentPage - 1) * hkPageSize;

                return {
                    slice: data.slice(start, start + hkPageSize),
                    total,
                    pages,
                };
            }

            function hkRenderTable(data) {
                const tb = document.getElementById('hkTableBody');
                if (!tb) return;

                if (!data.length) {
                    tb.innerHTML =
                        '<tr><td colspan="8" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>';
                    return;
                }

                tb.innerHTML = data
                    .map(
                        o => `
                        <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
                            <td class="px-4 py-3 font-medium text-neutral-800">${o.id}</td>
                            <td class="px-4 py-3">${dotGol(o.gol)}</td>
                            <td class="px-4 py-3">${o.rh}</td>
                            <td class="px-4 py-3">${badgeProduk(o.produk)}</td>
                            <td class="px-4 py-3">${formatDate(o.masuk)}</td>
                            <td class="px-4 py-3">${formatDate(o.exp)}</td>
                            <td class="px-4 py-3">${o.penerima ?? ''}</td>
                            <td class="px-4 py-3">${badgeStatus(o.status || '-')}</td>
                        </tr>`,
                    )
                    .join('');
            }

            function hkRange(totalPages, current, max = 5) {
                const pages = [];
                const half = Math.floor(max / 2);
                let start = Math.max(1, current - half);
                let end = Math.min(totalPages, start + max - 1);

                if (end - start + 1 < max) {
                    start = Math.max(1, end - max + 1);
                }

                if (start > 1) {
                    pages.push(1);
                    if (start > 2) pages.push('…');
                }

                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }

                if (end < totalPages) {
                    if (end < totalPages - 1) pages.push('…');
                    pages.push(totalPages);
                }

                return pages;
            }

            function hkRenderPagination(total, pages) {
                const cont = document.getElementById('hkPagination');
                const info = document.getElementById('hkPageInfo');
                if (!cont || !info) return;

                const start = total === 0 ? 0 : (hkCurrentPage - 1) * hkPageSize + 1;
                const end = Math.min(hkCurrentPage * hkPageSize, total);

                info.textContent =
                    total > 0 ? `Menampilkan ${start} - ${end} dari ${total} data` : 'Tidak ada data';

                if (pages <= 1) {
                    cont.innerHTML = '';
                    return;
                }

                const btn = (label, page, disabled = false, active = false) => `
                <button
                    class="min-w-9 h-9 px-3 rounded-2xl border text-sm
                    ${disabled ? 'opacity-50 cursor-not-allowed' : ''}
                    ${
                        active
                            ? 'bg-sky-600 text-white border-sky-600 shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-200'
                            : 'bg-white text-neutral-700 border-neutral-200 hover:bg-neutral-50 hover:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-sky-200'
                    }"
                    ${disabled ? 'disabled' : ''}
                    data-hkpage="${page}"
                >
                    ${label}
                </button>
            `;

                let html = '';
                html += btn('«', hkCurrentPage - 1, hkCurrentPage === 1);

                hkRange(pages, hkCurrentPage, 5).forEach(p => {
                    if (p === '…') {
                        html += '<span class="px-2 text-neutral-400">…</span>';
                    } else {
                        html += btn(p, p, false, p === hkCurrentPage);
                    }
                });

                html += btn('»', hkCurrentPage + 1, hkCurrentPage === pages);
                cont.innerHTML = html;

                cont.querySelectorAll('button[data-hkpage]').forEach(b => {
                    b.addEventListener('click', () => {
                        const p = Number(b.dataset.hkpage);
                        if (!Number.isNaN(p)) {
                            hkCurrentPage = p;
                            hkRenderAll();
                        }
                    });
                });
            }

            function hkMarkSortHeaders() {
                document.querySelectorAll('th.hk-sortable').forEach(th => {
                    th.querySelector('.hk-ind')?.remove();
                    if (th.dataset.hk === hkSortKey) {
                        const s = document.createElement('span');
                        s.className = 'hk-ind inline-block ml-1 text-neutral-400';
                        s.innerHTML = hkSortDir === 'asc' ? '▲' : '▼';
                        th.appendChild(s);
                    }
                });
            }

            function hkRenderAll() {
                const filtered = hkGetFiltered();
                const sorted = hkGetSorted(filtered);
                const {
                    slice,
                    total,
                    pages
                } = hkGetPaged(sorted);
                hkRenderTable(slice);
                hkRenderPagination(total, pages);
                hkMarkSortHeaders();
            }

            /* ====================== */
            /* ====== TABEL 3 ======= */
            /* ====================== */
            const todayYmd = new Date().toISOString().slice(0, 10);
            const exRows = hkRows.filter(r => String(r.exp || '') < todayYmd);

            let exSortKey = 'id';
            let exSortDir = 'asc';
            let exCurrentPage = 1;
            let exPageSize = 10;
            let exProdukSelected = '';
            let exGolSelected = '';

            function exGetFiltered() {
                const q = (document.getElementById('exSearchInput')?.value || '').toLowerCase().trim();
                const g = exGolSelected || '';
                const pr = exProdukSelected || '';

                return exRows.filter(o => {
                    const hitQ = !q || String(o.id).toLowerCase().includes(q);
                    const hitG = !g || o.gol === g;
                    const hitP = !pr || o.produk === pr;
                    return hitQ && hitG && hitP;
                });
            }

            function exGetSorted(data) {
                const cp = [...data];
                cp.sort((a, b) => {
                    let va = a[exSortKey];
                    let vb = b[exSortKey];
                    va = String(va ?? '').toLowerCase();
                    vb = String(vb ?? '').toLowerCase();
                    if (va < vb) return exSortDir === 'asc' ? -1 : 1;
                    if (va > vb) return exSortDir === 'asc' ? 1 : -1;
                    return 0;
                });
                return cp;
            }

            function exGetPaged(data) {
                const total = data.length;
                const pages = Math.max(1, Math.ceil(total / exPageSize));
                exCurrentPage = Math.min(exCurrentPage, pages);
                const start = (exCurrentPage - 1) * exPageSize;

                return {
                    slice: data.slice(start, start + exPageSize),
                    total,
                    pages,
                };
            }

            function exRenderTable(data) {
                const tb = document.getElementById('exTableBody');
                if (!tb) return;

                if (!data.length) {
                    tb.innerHTML =
                        '<tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>';
                    return;
                }

                tb.innerHTML = data
                    .map(
                        o => `
                        <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
                            <td class="px-4 py-3 font-medium text-neutral-800">${o.id}</td>
                            <td class="px-4 py-3">${dotGol(o.gol)}</td>
                            <td class="px-4 py-3">${o.rh}</td>
                            <td class="px-4 py-3">${badgeProduk(o.produk)}</td>
                            <td class="px-4 py-3">${formatDate(o.masuk)}</td>
                            <td class="px-4 py-3">${formatDate(o.exp)}</td>
                        </tr>`,
                    )
                    .join('');
            }

            function exRange(totalPages, current, max = 5) {
                const pages = [];
                const half = Math.floor(max / 2);
                let start = Math.max(1, current - half);
                let end = Math.min(totalPages, start + max - 1);

                if (end - start + 1 < max) {
                    start = Math.max(1, end - max + 1);
                }

                if (start > 1) {
                    pages.push(1);
                    if (start > 2) pages.push('…');
                }

                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }

                if (end < totalPages) {
                    if (end < totalPages - 1) pages.push('…');
                    pages.push(totalPages);
                }

                return pages;
            }

            function exRenderPagination(total, pages) {
                const cont = document.getElementById('exPagination');
                const info = document.getElementById('exPageInfo');
                if (!cont || !info) return;

                const start = total === 0 ? 0 : (exCurrentPage - 1) * exPageSize + 1;
                const end = Math.min(exCurrentPage * exPageSize, total);

                info.textContent =
                    total > 0 ? `Menampilkan ${start}-${end} dari ${total} data` : 'Tidak ada data';

                if (pages <= 1) {
                    cont.innerHTML = '';
                    return;
                }

                const btn = (label, page, disabled = false, active = false) => `
                <button
                    class="min-w-9 h-9 px-3 rounded-2xl border text-sm
                    ${disabled ? 'opacity-50 cursor-not-allowed' : ''}
                    ${
                        active
                            ? 'bg-sky-600 text-white border-sky-600 shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-200'
                            : 'bg-white text-neutral-700 border-neutral-200 hover:bg-neutral-50 hover:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-sky-200'
                    }"
                    ${disabled ? 'disabled' : ''}
                    data-expage="${page}"
                >
                    ${label}
                </button>
            `;

                let html = '';
                html += btn('«', exCurrentPage - 1, exCurrentPage === 1);

                exRange(pages, exCurrentPage, 5).forEach(p => {
                    if (p === '…') {
                        html += '<span class="px-2 text-neutral-400">…</span>';
                    } else {
                        html += btn(p, p, false, p === exCurrentPage);
                    }
                });

                html += btn('»', exCurrentPage + 1, exCurrentPage === pages);
                cont.innerHTML = html;

                cont.querySelectorAll('button[data-expage]').forEach(b => {
                    b.addEventListener('click', () => {
                        const p = Number(b.dataset.expage);
                        if (!Number.isNaN(p)) {
                            exCurrentPage = p;
                            exRenderAll();
                        }
                    });
                });
            }

            function exMarkSortHeaders() {
                document.querySelectorAll('th.ex-sortable').forEach(th => {
                    th.querySelector('.ex-ind')?.remove();
                    if (th.dataset.ex === exSortKey) {
                        const s = document.createElement('span');
                        s.className = 'ex-ind inline-block ml-1 text-neutral-400';
                        s.innerHTML = exSortDir === 'asc' ? '▲' : '▼';
                        th.appendChild(s);
                    }
                });
            }

            function exRenderAll() {
                const filtered = exGetFiltered();
                const sorted = exGetSorted(filtered);
                const {
                    slice,
                    total,
                    pages
                } = exGetPaged(sorted);
                exRenderTable(slice);
                exRenderPagination(total, pages);
                exMarkSortHeaders();
            }

            /* ===== Mount ===== */
            document.addEventListener('DOMContentLoaded', () => {
                setTab('avail'); // default tab

                /* === Tabel 1: Search === */
                document.getElementById('searchInput')?.addEventListener('input', () => {
                    currentPage = 1;
                    renderAll();
                });

                /* === Tabel 1: Filter Produk & Gol === */
                const filterBtn = document.getElementById('filterBtn');
                const filterMenu = document.getElementById('filterMenu');
                const produkBtn = document.getElementById('produkBtn');
                const produkMenu = document.getElementById('produkMenu');
                const produkLabel = document.getElementById('produkLabel');
                const golBtn = document.getElementById('golBtn');
                const golMenu = document.getElementById('golMenu');
                const golLabel = document.getElementById('golLabel');
                const apply = document.getElementById('applyBtn');
                const reset = document.getElementById('resetBtn');

                filterBtn?.addEventListener('click', e => {
                    e.stopPropagation();
                    updateProdukActive(produkSelected);
                    document.getElementById('pageSizeMenu')?.classList.add('hidden');
                    document.getElementById('produkMenu')?.classList.add('hidden');
                    document.getElementById('golMenu')?.classList.add('hidden');
                    filterMenu?.classList.toggle('hidden');
                });

                document.addEventListener('click', e => {
                    if (filterMenu && filterBtn && !filterMenu.contains(e.target) && !filterBtn.contains(e
                            .target)) {
                        filterMenu.classList.add('hidden');
                    }
                });

                function updateProdukActive(value) {
                    if (!produkMenu) return;
                    produkMenu.querySelectorAll('.produk-item').forEach(el => {
                        const active = el.getAttribute('data-value') === (value ?? '');
                        el.setAttribute('aria-checked', active ? 'true' : 'false');
                        el.classList.toggle('bg-blue-50', active);
                        el.classList.toggle('text-blue-800', active);
                        el.classList.toggle('ring-1', active);
                        el.classList.toggle('ring-blue-200', active);
                        el.classList.toggle('cursor-default', active);
                        const icon = el.querySelector('.check-icon');
                        if (icon) icon.classList.toggle('opacity-100', active);
                        if (icon) icon.classList.toggle('opacity-0', !active);
                    });
                }

                produkBtn?.addEventListener('click', e => {
                    e.stopPropagation();
                    document.getElementById('pageSizeMenu')?.classList.add('hidden');
                    document.getElementById('golMenu')?.classList.add('hidden');
                    produkMenu?.classList.toggle('hidden');
                });

                produkMenu?.querySelectorAll('.produk-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const val = item.getAttribute('data-value') ?? '';
                        const label = item.querySelector('span')?.textContent?.trim() || 'Semua Produk';
                        produkSelected = val;
                        if (produkLabel) produkLabel.textContent = label;
                        updateProdukActive(val);
                        produkMenu?.classList.add('hidden');
                    });
                });

                function updateGolActive(value) {
                    if (!golMenu) return;
                    golMenu.querySelectorAll('.gol-item').forEach(el => {
                        const active = el.getAttribute('data-value') === (value ?? '');
                        el.setAttribute('aria-checked', active ? 'true' : 'false');
                        el.classList.toggle('bg-blue-50', active);
                        el.classList.toggle('text-blue-800', active);
                        el.classList.toggle('ring-1', active);
                        el.classList.toggle('ring-blue-200', active);
                        el.classList.toggle('cursor-default', active);
                        const icon = el.querySelector('.check-icon');
                        if (icon) icon.classList.toggle('opacity-100', active);
                        if (icon) icon.classList.toggle('opacity-0', !active);
                    });
                }

                golBtn?.addEventListener('click', e => {
                    e.stopPropagation();
                    updateGolActive(golSelected);
                    document.getElementById('pageSizeMenu')?.classList.add('hidden');
                    document.getElementById('produkMenu')?.classList.add('hidden');
                    golMenu?.classList.toggle('hidden');
                });

                golMenu?.querySelectorAll('.gol-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const val = item.getAttribute('data-value') ?? '';
                        const label = item.querySelector('span')?.textContent?.trim() || 'Semua';
                        golSelected = val;
                        if (golLabel) golLabel.textContent = label;
                        updateGolActive(val);
                        golMenu?.classList.add('hidden');
                    });
                });

                apply?.addEventListener('click', () => {
                    filterMenu?.classList.add('hidden');
                    currentPage = 1;
                    renderAll();
                });

                reset?.addEventListener('click', () => {
                    produkSelected = '';
                    golSelected = '';
                    if (produkLabel) produkLabel.textContent = 'Semua Produk';
                    if (golLabel) golLabel.textContent = 'Semua';
                    updateProdukActive('');
                    updateGolActive('');
                    currentPage = 1;
                    renderAll();
                });

                /* === Tabel 1: Page size dropdown === */
                const pageSizeBtn = document.getElementById('pageSizeBtn');
                const pageSizeMenu = document.getElementById('pageSizeMenu');
                const pageSizeLabel = document.getElementById('pageSizeLabel');

                function updatePageSizeActive(size) {
                    if (!pageSizeMenu) return;
                    pageSizeMenu.querySelectorAll('.page-size-item').forEach(btn => {
                        const bSize = Number(btn.getAttribute('data-size')) || 10;
                        const active = bSize === size;
                        btn.setAttribute('aria-checked', active ? 'true' : 'false');
                        btn.classList.toggle('bg-blue-50', active);
                        btn.classList.toggle('text-blue-800', active);
                        btn.classList.toggle('ring-1', active);
                        btn.classList.toggle('ring-blue-200', active);
                        btn.classList.toggle('cursor-default', active);
                    });
                }

                if (pageSizeLabel) pageSizeLabel.textContent = pageSize;

                pageSizeBtn?.addEventListener('click', e => {
                    e.stopPropagation();
                    document.getElementById('filterMenu')?.classList.add('hidden');
                    document.getElementById('produkMenu')?.classList.add('hidden');
                    document.getElementById('golMenu')?.classList.add('hidden');
                    updatePageSizeActive(pageSize);
                    pageSizeMenu?.classList.toggle('hidden');
                });

                document.addEventListener('click', e => {
                    if (pageSizeMenu && pageSizeBtn && !pageSizeMenu.contains(e.target) && !pageSizeBtn
                        .contains(e.target)) {
                        pageSizeMenu.classList.add('hidden');
                    }
                });

                pageSizeMenu?.querySelectorAll('.page-size-item').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const size = Number(btn.getAttribute('data-size')) || 10;
                        pageSize = size;
                        if (pageSizeLabel) pageSizeLabel.textContent = pageSize;
                        pageSizeMenu.querySelectorAll('.page-size-item').forEach(b => {
                            const active = b === btn;
                            b.setAttribute('aria-checked', active ? 'true' : 'false');
                            b.classList.toggle('bg-blue-50', active);
                            b.classList.toggle('text-blue-800', active);
                            b.classList.toggle('ring-1', active);
                            b.classList.toggle('ring-blue-200', active);
                            b.classList.toggle('cursor-default', active);
                        });
                        pageSizeMenu.classList.add('hidden');
                        currentPage = 1;
                        renderAll();
                    });
                });

                document.querySelectorAll('th.sortable').forEach(th => {
                    th.addEventListener('click', () => {
                        const key = th.dataset.key;
                        if (sortKey === key) {
                            sortDir = sortDir === 'asc' ? 'desc' : 'asc';
                        } else {
                            sortKey = key;
                            sortDir = 'asc';
                        }
                        renderAll();
                    });
                });

                renderAll();

                /* === Tabel 2: Search === */
                document.getElementById('hkSearchInput')?.addEventListener('input', () => {
                    hkCurrentPage = 1;
                    hkRenderAll();
                });

                /* === Tabel 2: Filter === */
                const hkBtn = document.getElementById('hkFilterBtn');
                const hkMenu = document.getElementById('hkFilterMenu');
                const hkProdukBtn = document.getElementById('hkProdukBtn');
                const hkProdukMenu = document.getElementById('hkProdukMenu');
                const hkProdukLabel = document.getElementById('hkProdukLabel');
                const hkGolBtn = document.getElementById('hkGolBtn');
                const hkGolMenu = document.getElementById('hkGolMenu');
                const hkGolLabel = document.getElementById('hkGolLabel');
                const hkApply = document.getElementById('hkApplyBtn');
                const hkReset = document.getElementById('hkResetBtn');

                hkBtn?.addEventListener('click', e => {
                    e.stopPropagation();
                    document.getElementById('hkPageSizeMenu')?.classList.add('hidden');
                    document.getElementById('hkProdukMenu')?.classList.add('hidden');
                    document.getElementById('hkGolMenu')?.classList.add('hidden');
                    hkMenu?.classList.toggle('hidden');
                });

                document.addEventListener('click', e => {
                    if (hkMenu && hkBtn && !hkMenu.contains(e.target) && !hkBtn.contains(e.target)) {
                        hkMenu.classList.add('hidden');
                    }
                });

                function hkUpdateProdukActive(value) {
                    if (!hkProdukMenu) return;
                    hkProdukMenu.querySelectorAll('.hk-produk-item').forEach(el => {
                        const active = el.getAttribute('data-value') === (value ?? '');
                        el.setAttribute('aria-checked', active ? 'true' : 'false');
                        el.classList.toggle('bg-blue-50', active);
                        el.classList.toggle('text-blue-800', active);
                        el.classList.toggle('ring-1', active);
                        el.classList.toggle('ring-blue-200', active);
                        el.classList.toggle('cursor-default', active);
                        const icon = el.querySelector('.check-icon');
                        if (icon) icon.classList.toggle('opacity-100', active);
                        if (icon) icon.classList.toggle('opacity-0', !active);
                    });
                }

                hkProdukBtn?.addEventListener('click', e => {
                    e.stopPropagation();
                    hkUpdateProdukActive(hkProdukSelected);
                    document.getElementById('hkPageSizeMenu')?.classList.add('hidden');
                    document.getElementById('hkGolMenu')?.classList.add('hidden');
                    hkProdukMenu?.classList.toggle('hidden');
                });

                hkProdukMenu?.querySelectorAll('.hk-produk-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const val = item.getAttribute('data-value') ?? '';
                        const label = item.querySelector('span')?.textContent?.trim() || 'Semua Produk';
                        hkProdukSelected = val;
                        if (hkProdukLabel) hkProdukLabel.textContent = label;
                        hkUpdateProdukActive(val);
                        hkProdukMenu?.classList.add('hidden');
                    });
                });

                function hkUpdateGolActive(value) {
                    if (!hkGolMenu) return;
                    hkGolMenu.querySelectorAll('.hk-gol-item').forEach(el => {
                        const active = el.getAttribute('data-value') === (value ?? '');
                        el.setAttribute('aria-checked', active ? 'true' : 'false');
                        el.classList.toggle('bg-blue-50', active);
                        el.classList.toggle('text-blue-800', active);
                        el.classList.toggle('ring-1', active);
                        el.classList.toggle('ring-blue-200', active);
                        el.classList.toggle('cursor-default', active);
                        const icon = el.querySelector('.check-icon');
                        if (icon) icon.classList.toggle('opacity-100', active);
                        if (icon) icon.classList.toggle('opacity-0', !active);
                    });
                }

                hkGolBtn?.addEventListener('click', e => {
                    e.stopPropagation();
                    hkUpdateGolActive(hkGolSelected);
                    document.getElementById('hkPageSizeMenu')?.classList.add('hidden');
                    document.getElementById('hkProdukMenu')?.classList.add('hidden');
                    hkGolMenu?.classList.toggle('hidden');
                });

                hkGolMenu?.querySelectorAll('.hk-gol-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const val = item.getAttribute('data-value') ?? '';
                        const label = item.querySelector('span')?.textContent?.trim() || 'Semua';
                        hkGolSelected = val;
                        if (hkGolLabel) hkGolLabel.textContent = label;
                        hkUpdateGolActive(val);
                        hkGolMenu?.classList.add('hidden');
                    });
                });

                hkApply?.addEventListener('click', () => {
                    hkMenu?.classList.add('hidden');
                    hkCurrentPage = 1;
                    hkRenderAll();
                });

                hkReset?.addEventListener('click', () => {
                    hkProdukSelected = '';
                    hkGolSelected = '';
                    if (hkProdukLabel) hkProdukLabel.textContent = 'Semua Produk';
                    if (hkGolLabel) hkGolLabel.textContent = 'Semua';
                    hkUpdateProdukActive('');
                    hkUpdateGolActive('');
                    hkCurrentPage = 1;
                    hkRenderAll();
                });

                /* === Tabel 2: Page size === */
                const hkPageSizeBtn = document.getElementById('hkPageSizeBtn');
                const hkPageSizeMenu = document.getElementById('hkPageSizeMenu');
                const hkPageSizeLabel = document.getElementById('hkPageSizeLabel');

                function updateHkPageSizeActive(size) {
                    if (!hkPageSizeMenu) return;
                    hkPageSizeMenu.querySelectorAll('.hk-page-size-item').forEach(btn => {
                        const bSize = Number(btn.getAttribute('data-size')) || 10;
                        const active = bSize === size;
                        btn.setAttribute('aria-checked', active ? 'true' : 'false');
                        btn.classList.toggle('bg-blue-50', active);
                        btn.classList.toggle('text-blue-800', active);
                        btn.classList.toggle('ring-1', active);
                        btn.classList.toggle('ring-blue-200', active);
                        btn.classList.toggle('cursor-default', active);
                    });
                }

                if (hkPageSizeLabel) hkPageSizeLabel.textContent = hkPageSize;

                hkPageSizeBtn?.addEventListener('click', e => {
                    e.stopPropagation();
                    document.getElementById('hkFilterMenu')?.classList.add('hidden');
                    document.getElementById('hkProdukMenu')?.classList.add('hidden');
                    document.getElementById('hkGolMenu')?.classList.add('hidden');
                    updateHkPageSizeActive(hkPageSize);
                    hkPageSizeMenu?.classList.toggle('hidden');
                });

                document.addEventListener('click', e => {
                    if (
                        hkPageSizeMenu &&
                        hkPageSizeBtn &&
                        !hkPageSizeMenu.contains(e.target) &&
                        !hkPageSizeBtn.contains(e.target)
                    ) {
                        hkPageSizeMenu.classList.add('hidden');
                    }
                });

                hkPageSizeMenu?.querySelectorAll('.hk-page-size-item').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const size = Number(btn.getAttribute('data-size')) || 10;
                        hkPageSize = size;
                        if (hkPageSizeLabel) hkPageSizeLabel.textContent = hkPageSize;
                        hkPageSizeMenu.querySelectorAll('.hk-page-size-item').forEach(b => {
                            const active = b === btn;
                            b.setAttribute('aria-checked', active ? 'true' : 'false');
                            b.classList.toggle('bg-blue-50', active);
                            b.classList.toggle('text-blue-800', active);
                            b.classList.toggle('ring-1', active);
                            b.classList.toggle('ring-blue-200', active);
                            b.classList.toggle('cursor-default', active);
                        });
                        hkPageSizeMenu.classList.add('hidden');
                        hkCurrentPage = 1;
                        hkRenderAll();
                    });
                });

                document.querySelectorAll('th.hk-sortable').forEach(th => {
                    th.addEventListener('click', () => {
                        const key = th.dataset.hk;
                        if (hkSortKey === key) {
                            hkSortDir = hkSortDir === 'asc' ? 'desc' : 'asc';
                        } else {
                            hkSortKey = key;
                            hkSortDir = 'asc';
                        }
                        hkRenderAll();
                    });
                });

                hkRenderAll();

                /* === Tabel 3: Search === */
                document.getElementById('exSearchInput')?.addEventListener('input', () => {
                    exCurrentPage = 1;
                    exRenderAll();
                });

                /* === Tabel 3: Filter === */
                const exBtn = document.getElementById('exFilterBtn');
                const exMenu = document.getElementById('exFilterMenu');
                const exProdukBtn = document.getElementById('exProdukBtn');
                const exProdukMenu = document.getElementById('exProdukMenu');
                const exProdukLabel = document.getElementById('exProdukLabel');
                const exGolBtn = document.getElementById('exGolBtn');
                const exGolMenu = document.getElementById('exGolMenu');
                const exGolLabel = document.getElementById('exGolLabel');
                const exApply = document.getElementById('exApplyBtn');
                const exReset = document.getElementById('exResetBtn');

                exBtn?.addEventListener('click', e => {
                    e.stopPropagation();
                    document.getElementById('exPageSizeMenu')?.classList.add('hidden');
                    document.getElementById('exProdukMenu')?.classList.add('hidden');
                    document.getElementById('exGolMenu')?.classList.add('hidden');
                    exMenu?.classList.toggle('hidden');
                });

                document.addEventListener('click', e => {
                    if (exMenu && exBtn && !exMenu.contains(e.target) && !exBtn.contains(e.target)) {
                        exMenu.classList.add('hidden');
                    }
                });

                function exUpdateProdukActive(value) {
                    if (!exProdukMenu) return;
                    exProdukMenu.querySelectorAll('.ex-produk-item').forEach(el => {
                        const active = el.getAttribute('data-value') === (value ?? '');
                        el.setAttribute('aria-checked', active ? 'true' : 'false');
                        el.classList.toggle('bg-blue-50', active);
                        el.classList.toggle('text-blue-800', active);
                        el.classList.toggle('ring-1', active);
                        el.classList.toggle('ring-blue-200', active);
                        el.classList.toggle('cursor-default', active);
                        const icon = el.querySelector('.check-icon');
                        if (icon) icon.classList.toggle('opacity-100', active);
                        if (icon) icon.classList.toggle('opacity-0', !active);
                    });
                }

                exProdukBtn?.addEventListener('click', e => {
                    e.stopPropagation();
                    exUpdateProdukActive(exProdukSelected);
                    document.getElementById('exPageSizeMenu')?.classList.add('hidden');
                    document.getElementById('exGolMenu')?.classList.add('hidden');
                    exProdukMenu?.classList.toggle('hidden');
                });

                exProdukMenu?.querySelectorAll('.ex-produk-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const val = item.getAttribute('data-value') ?? '';
                        const label = item.querySelector('span')?.textContent?.trim() || 'Semua Produk';
                        exProdukSelected = val;
                        if (exProdukLabel) exProdukLabel.textContent = label;
                        exUpdateProdukActive(val);
                        exProdukMenu?.classList.add('hidden');
                    });
                });

                function exUpdateGolActive(value) {
                    if (!exGolMenu) return;
                    exGolMenu.querySelectorAll('.ex-gol-item').forEach(el => {
                        const active = el.getAttribute('data-value') === (value ?? '');
                        el.setAttribute('aria-checked', active ? 'true' : 'false');
                        el.classList.toggle('bg-blue-50', active);
                        el.classList.toggle('text-blue-800', active);
                        el.classList.toggle('ring-1', active);
                        el.classList.toggle('ring-blue-200', active);
                        el.classList.toggle('cursor-default', active);
                        const icon = el.querySelector('.check-icon');
                        if (icon) icon.classList.toggle('opacity-100', active);
                        if (icon) icon.classList.toggle('opacity-0', !active);
                    });
                }

                exGolBtn?.addEventListener('click', e => {
                    e.stopPropagation();
                    exUpdateGolActive(exGolSelected);
                    document.getElementById('exPageSizeMenu')?.classList.add('hidden');
                    document.getElementById('exProdukMenu')?.classList.add('hidden');
                    exGolMenu?.classList.toggle('hidden');
                });

                exGolMenu?.querySelectorAll('.ex-gol-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const val = item.getAttribute('data-value') ?? '';
                        const label = item.querySelector('span')?.textContent?.trim() || 'Semua';
                        exGolSelected = val;
                        if (exGolLabel) exGolLabel.textContent = label;
                        exUpdateGolActive(val);
                        exGolMenu?.classList.add('hidden');
                    });
                });

                exApply?.addEventListener('click', () => {
                    exMenu?.classList.add('hidden');
                    exCurrentPage = 1;
                    exRenderAll();
                });

                exReset?.addEventListener('click', () => {
                    exProdukSelected = '';
                    exGolSelected = '';
                    if (exProdukLabel) exProdukLabel.textContent = 'Semua Produk';
                    if (exGolLabel) exGolLabel.textContent = 'Semua';
                    exUpdateProdukActive('');
                    exUpdateGolActive('');
                    exCurrentPage = 1;
                    exRenderAll();
                });

                /* === Tabel 3: Page size === */
                const exPageSizeBtn = document.getElementById('exPageSizeBtn');
                const exPageSizeMenu = document.getElementById('exPageSizeMenu');
                const exPageSizeLabel = document.getElementById('exPageSizeLabel');

                function updateExPageSizeActive(size) {
                    if (!exPageSizeMenu) return;
                    exPageSizeMenu.querySelectorAll('.ex-page-size-item').forEach(btn => {
                        const bSize = Number(btn.getAttribute('data-size')) || 10;
                        const active = bSize === size;
                        btn.setAttribute('aria-checked', active ? 'true' : 'false');
                        btn.classList.toggle('bg-blue-50', active);
                        btn.classList.toggle('text-blue-800', active);
                        btn.classList.toggle('ring-1', active);
                        btn.classList.toggle('ring-blue-200', active);
                        btn.classList.toggle('cursor-default', active);
                    });
                }

                if (exPageSizeLabel) exPageSizeLabel.textContent = exPageSize;

                exPageSizeBtn?.addEventListener('click', e => {
                    e.stopPropagation();
                    document.getElementById('exFilterMenu')?.classList.add('hidden');
                    document.getElementById('exProdukMenu')?.classList.add('hidden');
                    document.getElementById('exGolMenu')?.classList.add('hidden');
                    updateExPageSizeActive(exPageSize);
                    exPageSizeMenu?.classList.toggle('hidden');
                });

                document.addEventListener('click', e => {
                    if (
                        exPageSizeMenu &&
                        exPageSizeBtn &&
                        !exPageSizeMenu.contains(e.target) &&
                        !exPageSizeBtn.contains(e.target)
                    ) {
                        exPageSizeMenu.classList.add('hidden');
                    }
                });

                exPageSizeMenu?.querySelectorAll('.ex-page-size-item').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const size = Number(btn.getAttribute('data-size')) || 10;
                        exPageSize = size;
                        if (exPageSizeLabel) exPageSizeLabel.textContent = exPageSize;
                        exPageSizeMenu.querySelectorAll('.ex-page-size-item').forEach(b => {
                            const active = b === btn;
                            b.setAttribute('aria-checked', active ? 'true' : 'false');
                            b.classList.toggle('bg-blue-50', active);
                            b.classList.toggle('text-blue-800', active);
                            b.classList.toggle('ring-1', active);
                            b.classList.toggle('ring-blue-200', active);
                            b.classList.toggle('cursor-default', active);
                        });
                        exPageSizeMenu.classList.add('hidden');
                        exCurrentPage = 1;
                        exRenderAll();
                    });
                });

                document.querySelectorAll('th.ex-sortable').forEach(th => {
                    th.addEventListener('click', () => {
                        const key = th.dataset.ex;
                        if (exSortKey === key) {
                            exSortDir = exSortDir === 'asc' ? 'desc' : 'asc';
                        } else {
                            exSortKey = key;
                            exSortDir = 'asc';
                        }
                        exRenderAll();
                    });
                });

                exRenderAll();
            });
        </script>

        <style>
            th.sortable:hover,
            th.hk-sortable:hover,
            th.ex-sortable:hover {
                background-color: rgba(0, 0, 0, 0.02);
            }

            .tabbtn {
                padding: 0.45rem 1.1rem;
                font-size: 0.875rem;
                /* text-sm */
                font-weight: 500;
                border-radius: 9999px;
                border: 1px solid transparent;
                color: #6b7280;
                /* text-neutral-500 */
                background-color: transparent;
                transition:
                    background-color 0.15s ease,
                    color 0.15s ease,
                    border-color 0.15s ease,
                    box-shadow 0.15s ease;
            }

            .tabbtn.is-active {
                background-color: #0284c7;
                /* blue-600 */
                color: #ffffff;
                border-color: transparent;
                box-shadow: 0 8px 18px rgba(37, 99, 235, 0.25);
            }

            .tabbtn:not(.is-active):hover {
                background-color: #e5e7eb;
                /* bg-neutral-200 */
                color: #111827;
                /* text-neutral-900 */
            }

            .check-icon {
                transition: opacity 0.12s ease;
            }
        </style>

    @endsection
