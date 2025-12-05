{{-- resources/views/admin/riwayat/index.blade.php --}}
@extends('layouts.admin')

@section('content')
  <div id="pageRoot"
       class="space-y-6">
    {{-- Flash/Error --}}
    @if ($errors->any())
      <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
        {{ $errors->first() }}
      </div>
    @endif
    @if (session('error'))
      <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
        {{ session('error') }}
      </div>
    @endif
    @if (session('success'))
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
      </div>
    @endif

    {{-- Header --}}
    <div class="space-y-1">
      <h1 class="text-2xl font-semibold md:text-3xl">Riwayat Pemesanan</h1>
      <p class="text-sm text-neutral-500">Daftar pemesanan yang sudah diproses</p>
    </div>

    @php
      /* Helper pills (gaya sama seperti Verifikasi) */
      if (!function_exists('blood_pill')) {
          function blood_pill($g)
          {
              return '<span class="inline-flex h-6 items-center rounded-full border border-rose-100 bg-rose-50 px-2 text-xs font-semibold text-rose-700">' .
                  e($g) .
                  '</span>';
          }
      }
      if (!function_exists('rhesus_pill')) {
          function rhesus_pill($r)
          {
              return '<span class="inline-flex h-6 items-center rounded-full border border-slate-100 bg-slate-50 px-2 text-xs font-semibold text-slate-700">' .
                  e($r) .
                  '</span>';
          }
      }
      if (!function_exists('product_pill')) {
          function product_pill($p)
          {
              return '<span class="inline-flex h-6 items-center rounded-full border border-sky-100 bg-sky-50 px-2 text-xs font-semibold text-sky-700">' .
                  e($p) .
                  '</span>';
          }
      }

      // State GET (hanya untuk persist UI/filter; data tetap dari rowsJson)
      $q = request('q', '');
      $golQ = request('gol', '');
      $produkQ = request('produk', '');
      $perPage = (int) request('per_page', 10);

      $produkOpts = [
          '' => 'Semua Produk',
          'WB' => 'WB: Whole Blood',
          'PRC' => 'PRC: Packed Red Cell',
          'TC' => 'TC: Thrombocyte Concentrate',
          'FFP' => 'FFP: Fresh Frozen Plasma',
          'CRYO' => 'CRYO: Cryoprecipitated Anti-Hemophilic Factor',
          'LP' => 'LP: Liquid Plasma',
          'TCA' => 'TCA: Thrombocyte Apheresis',
          'CP' => 'CP: Convalescent Plasma',
      ];

      $hasActiveFilter = $q || $golQ || $produkQ;
    @endphp

    {{-- ================= Toolbar (Search • Filters • Page Size) ================= --}}
    <form id="filterForm"
          method="GET"
          class="space-y-3">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        {{-- Search (client-side, tapi tetap sinkronkan nilai dengan GET agar chip konsisten) --}}
        <div class="relative min-w-0 flex-1">
          <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
            <svg class="size-5 text-neutral-400"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 aria-hidden="true">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.6"
                    d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" />
            </svg>
          </span>
          <input id="searchInput"
                 name="q"
                 value="{{ $q }}"
                 type="text"
                 class="focus:border-navy-300 focus:ring-navy-200 w-full rounded-2xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:outline-none focus:ring-2"
                 placeholder="Cari nama pasien atau rumah sakit " />
        </div>

        {{-- Filter dropdown (Produk & Gol) --}}
        <div class="relative">
          <button type="button"
                  id="filterBtn"
                  class="focus:ring-navy-200 inline-flex min-h-10 items-center gap-2 rounded-2xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50 focus:outline-none focus:ring-2"
                  aria-haspopup="menu"
                  aria-expanded="false"
                  aria-controls="filterMenu">
            <svg class="size-5 text-neutral-600"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 aria-hidden="true">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.6"
                    d="M3 6h18M6 12h12M10 18h4" />
            </svg>
            <span>Filter</span>
          </button>

          <div id="filterMenu"
               role="menu"
               aria-labelledby="filterBtn"
               class="absolute right-0 z-20 mt-2 hidden w-[22rem] rounded-2xl border border-neutral-200 bg-white p-3 shadow-xl">
            <div class="space-y-3">
              {{-- Produk --}}
              <div class="relative">
                <label class="text-xs font-medium text-neutral-500">Produk Darah</label>
                <button type="button"
                        id="produkBtn"
                        class="mt-1 flex w-full items-center justify-between rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm transition hover:bg-blue-50/40 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        aria-haspopup="menu"
                        aria-expanded="false"
                        aria-controls="produkMenu">
                  <span id="produkLabel"
                        class="truncate">{{ $produkOpts[$produkQ] ?? 'Semua Produk' }}</span>
                  <svg class="size-4 shrink-0 text-neutral-500"
                       viewBox="0 0 24 24"
                       fill="none"
                       stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M6 9l6 6 6-6" />
                  </svg>
                </button>
                <div id="produkMenu"
                     role="menu"
                     aria-labelledby="produkBtn"
                     class="absolute left-0 z-30 mt-1 hidden w-full rounded-2xl border border-neutral-100 bg-white p-1 shadow-2xl ring-1 ring-black/5">
                  @foreach ($produkOpts as $val => $label)
                    <button type="button"
                            role="menuitemradio"
                            aria-checked="{{ $produkQ === $val ? 'true' : 'false' }}"
                            class="produk-item {{ $produkQ === $val ? 'bg-blue-50 text-blue-800 ring-1 ring-blue-200 cursor-default' : 'text-blue-800 hover:bg-blue-50/60' }} group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
                            data-value="{{ $val }}">
                      <span class="truncate">{{ $label }}</span>
                      <svg class="check-icon size-4 opacity-0 group-[aria-checked=true]:opacity-100"
                           viewBox="0 0 24 24"
                           fill="none"
                           stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7" />
                      </svg>
                    </button>
                  @endforeach
                </div>
              </div>

              {{-- Golongan --}}
              <div class="relative">
                <label class="text-xs font-medium text-neutral-500">Golongan Darah</label>
                <button type="button"
                        id="golBtn"
                        class="focus:ring-navy-200 mt-1 flex w-full items-center justify-between rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm transition hover:bg-emerald-50/40 focus:outline-none focus:ring-2"
                        aria-haspopup="menu"
                        aria-expanded="false"
                        aria-controls="golMenu">
                  <span id="golLabel"
                        class="truncate">{{ $golQ ?: 'Semua' }}</span>
                  <svg class="size-4 shrink-0 text-neutral-500"
                       viewBox="0 0 24 24"
                       fill="none"
                       stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M6 9l6 6 6-6" />
                  </svg>
                </button>
                <div id="golMenu"
                     role="menu"
                     aria-labelledby="golBtn"
                     class="absolute left-0 z-30 mt-1 hidden w-full rounded-2xl border border-neutral-100 bg-white p-1 shadow-2xl ring-1 ring-black/5">
                  @php $gOpts=[''=>'Semua','A'=>'A','B'=>'B','AB'=>'AB','O'=>'O']; @endphp
                  @foreach ($gOpts as $val => $lab)
                    <button type="button"
                            role="menuitemradio"
                            aria-checked="{{ $golQ === $val ? 'true' : 'false' }}"
                            class="gol-item {{ $golQ === $val ? 'bg-blue-50 text-blue-800 ring-1 ring-blue-200 cursor-default' : 'text-blue-800 hover:bg-blue-50/60' }} group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
                            data-value="{{ $val }}">
                      <span class="truncate">{{ $lab }}</span>
                      <svg class="check-icon size-4 opacity-0 group-[aria-checked=true]:opacity-100"
                           viewBox="0 0 24 24"
                           fill="none"
                           stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7" />
                      </svg>
                    </button>
                  @endforeach
                </div>
              </div>

              <div class="flex items-center justify-between pt-1">
                <button type="button"
                        id="resetBtn"
                        class="text-sm text-neutral-600 hover:underline">Reset</button>
                <button type="button"
                        id="applyBtn"
                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm">Terapkan</button>
              </div>
            </div>
          </div>
        </div>

        {{-- Page size --}}
        @php $sizes=[5,10,20]; @endphp
        <div class="relative sm:ml-auto">
          <button type="button"
                  id="pageSizeBtn"
                  class="focus:ring-navy-200 inline-flex min-h-10 items-center gap-2 rounded-2xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50 focus:outline-none focus:ring-2"
                  aria-haspopup="menu"
                  aria-expanded="false"
                  aria-controls="pageSizeMenu">
            <svg class="size-5 text-neutral-600"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 aria-hidden="true">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.6"
                    d="M4 8h16M4 16h10" />
            </svg>
            <span>Baris: <strong class="font-semibold text-neutral-800">{{ $perPage }}</strong></span>
            <svg class="size-4 text-neutral-500"
                 viewBox="0 0 20 20"
                 fill="currentColor"
                 aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M10 12a1 1 0 0 1-.7-.29l-4-4a1 1 0 0 1 1.4-1.42L10 9.59l3.3-3.3a1 1 0 1 1 1.4 1.42l-4 4A1 1 0 0 1 10 12Z"
                    clip-rule="evenodd" />
            </svg>
          </button>
          <div id="pageSizeMenu"
               role="menu"
               aria-labelledby="pageSizeBtn"
               class="absolute right-0 z-20 mt-2 hidden w-40 rounded-2xl border border-neutral-100 bg-white p-1 shadow-xl">
            @foreach ($sizes as $sz)
              <button type="button"
                      role="menuitemradio"
                      aria-checked="{{ $perPage === $sz ? 'true' : 'false' }}"
                      class="{{ $perPage === $sz ? 'bg-blue-50 text-blue-800 ring-1 ring-blue-200 cursor-default' : 'text-neutral-700' }} w-full rounded-xl px-3 py-2 text-left text-sm"
                      data-size="{{ $sz }}">
                {{ $sz }} per halaman
              </button>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Chips aktif (hanya untuk tampilan; filter tetap client-side) --}}
      @if ($hasActiveFilter)
        <div class="flex flex-wrap items-center gap-2">
          @if ($q)
            <span
                  class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs text-neutral-800">
              Cari: <strong class="font-medium">{{ $q }}</strong>
              <button type="button"
                      class="remove-chip ml-1"
                      data-target="q">×</button>
            </span>
          @endif
          @if ($produkQ !== '')
            <span
                  class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs text-neutral-800">
              Produk: <strong class="font-medium">{{ $produkOpts[$produkQ] ?? $produkQ }}</strong>
              <button type="button"
                      class="remove-chip ml-1"
                      data-target="produk">×</button>
            </span>
          @endif
          @if ($golQ)
            <span
                  class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs text-neutral-800">
              Gol: <strong class="font-medium">{{ $golQ }}</strong>
              <button type="button"
                      class="remove-chip ml-1"
                      data-target="gol">×</button>
            </span>
          @endif
          <button type="button"
                  id="clearAllBtn"
                  class="text-xs text-neutral-600 hover:underline">Bersihkan semua</button>
        </div>
      @endif

      {{-- Hidden inputs untuk persist nilai di URL --}}
      <input type="hidden"
             name="produk"
             id="produkInput"
             value="{{ $produkQ }}">
      <input type="hidden"
             name="gol"
             id="golInput"
             value="{{ $golQ }}">
      <input type="hidden"
             name="per_page"
             id="perPageInput"
             value="{{ $perPage }}">
    </form>

    {{-- =================== TABLE (≥ md) =================== --}}
    <div class="hidden overflow-hidden rounded-2xl border border-neutral-200 bg-white md:block">
      <div class="overflow-x-auto">
        <table id="riwayatTable"
               class="min-w-full text-sm">
          <thead class="bg-neutral-50 text-neutral-600">
            <tr class="text-left">
              <x-sortable-th column="nama_pasien"
                             label="Nama Pasien" />
              <x-sortable-th column="rs_pemesan"
                             label="Rumah Sakit Pemesan" />
              <x-sortable-th column="golongan_darah"
                             label="Golongan Darah" />
              <x-sortable-th column="rhesus"
                             label="Rhesus" />
              <x-sortable-th column="tanggal_pemesanan"
                             label="Tanggal Pemesanan" />
              <x-sortable-th column="produk_darah"
                             label="Produk Darah" />
              <x-sortable-th column="tanggal_verifikasi"
                             label="Tanggal Verifikasi" />
              <x-sortable-th column="status"
                             label="Status" />
              <th class="px-4 py-3 font-medium">Aksi</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            {{-- diisi via JS --}}
          </tbody>
        </table>
      </div>
    </div>

    {{-- =================== CARDS (mobile) =================== --}}
    <div id="cardsContainer"
         class="space-y-3 md:hidden"><!-- diisi via JS --></div>

    {{-- Pagination (client-side) --}}
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
      <div id="pageInfo"
           class="text-sm text-neutral-600">Tidak ada data</div>
      <div id="pagination"
           class="flex items-center gap-2"></div>
    </div>
  </div>

  {{-- =============== Modal Detail (view-only) =============== --}}
  <div id="detailModal"
       class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 p-3 backdrop-blur-sm transition-opacity duration-200 sm:p-4"
       aria-hidden="true">
    <div id="detailCard"
         class="w-full max-w-5xl origin-center translate-y-2 scale-95 overflow-hidden rounded-2xl border border-neutral-200/70 bg-white opacity-0 shadow-2xl transition-all duration-200 sm:rounded-3xl"
         role="dialog"
         aria-modal="true"
         aria-labelledby="detailModalTitle"
         tabindex="-1">

      {{-- Header --}}
      <div
           class="sticky top-0 z-10 flex items-center justify-between gap-4 border-b border-neutral-100/80 bg-white/80 px-4 py-3 backdrop-blur sm:px-6 sm:py-4">
        <div class="min-w-0">
          <h3 id="detailModalTitle"
              class="truncate text-lg font-semibold tracking-tight text-neutral-900 sm:text-xl">Detail Pemesanan</h3>
          <p class="mt-0.5 text-xs text-neutral-500">Ringkasan identitas & kebutuhan darah</p>
        </div>
        <div class="flex items-center gap-2">
          <span id="dm_status"
                class="inline-flex items-center gap-1 rounded-full border border-neutral-200 bg-neutral-50 px-2.5 py-1 text-xs font-medium text-neutral-700">-</span>
          <button type="button"
                  class="dm-close focus:ring-navy-300 inline-flex items-center justify-center rounded-xl p-2 text-neutral-500 ring-1 ring-transparent transition hover:bg-neutral-100 focus:outline-none"
                  aria-label="Tutup modal">
            <svg class="size-5"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.6"
                    d="M6 18 18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      {{-- Body --}}
      <div class="px-4 py-4 sm:px-6 sm:py-5">
        <div class="grid max-h-[70vh] grid-cols-1 gap-4 overflow-auto pr-1 sm:gap-6 md:grid-cols-2">
          {{-- A. Pasien & RS --}}
          <section class="rounded-2xl border border-neutral-100 bg-neutral-50/60 p-3 sm:p-4">
            <div class="mb-3 flex items-center gap-2">
              <span class="inline-flex size-6 items-center justify-center rounded-lg border border-neutral-200 bg-white">
                <svg class="size-3.5 text-neutral-700"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.6"
                        d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0M12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                </svg>
              </span>
              <h4 class="text-sm font-semibold tracking-wide text-neutral-800">A. Pasien & RS</h4>
            </div>
            <dl class="two-col-dl">
              <dt>Rumah Sakit</dt>
              <dd id="dm_rs">-</dd>
              <dt>Jenis Kelamin</dt>
              <dd id="dm_jk">-</dd>
              <dt>No. Registrasi</dt>
              <dd id="dm_no_regis">-</dd>
              <dt>Nama Dokter</dt>
              <dd id="dm_dokter">-</dd>
              <dt>Nama Pasien</dt>
              <dd id="dm_nama">-</dd>
              <dt>Suami/Istri</dt>
              <dd id="dm_suami_istri">-</dd>
              <dt>Telepon</dt>
              <dd id="dm_telp">-</dd>
              <dt>Email</dt>
              <dd id="dm_email"
                  class="break-all">-</dd>
            </dl>
          </section>

          {{-- B. Detail Klinis --}}
          <section class="rounded-2xl border border-neutral-100 bg-neutral-50/60 p-3 sm:p-4">
            <div class="mb-3 flex items-center gap-2">
              <span class="inline-flex size-6 items-center justify-center rounded-lg border border-neutral-200 bg-white">
                <svg class="size-3.5 text-neutral-700"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.6"
                        d="M12 8v8M8 12h8M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
              </span>
              <h4 class="text-sm font-semibold tracking-wide text-neutral-800">B. Detail Klinis</h4>
            </div>
            <dl class="two-col-dl">
              <dt>Tgl Diperlukan</dt>
              <dd id="dm_tgl_minta">-</dd>
              <dt>Pernah Serologi</dt>
              <dd id="dm_pernah_serologi">-</dd>
              <dt>Diagnosa</dt>
              <dd id="dm_diagnosa">-</dd>
              <dt>Lokasi Serologi</dt>
              <dd id="dm_lokasi_serologi">-</dd>
              <dt>Tgl Serologi</dt>
              <dd id="dm_tgl_serologi">-</dd>
              <dt>Tgl Transfusi</dt>
              <dd id="dm_tgl_transfusi">-</dd>
              <dt>Alasan Transfusi</dt>
              <dd id="dm_alasan">-</dd>
              <dt>Hasil Serologi</dt>
              <dd id="dm_hasil_serologi">-</dd>
            </dl>
          </section>

          {{-- C. Permintaan Darah --}}
          <section class="rounded-2xl border border-neutral-100 bg-neutral-50/60 p-3 sm:p-4 md:col-span-2">
            <div class="mb-3 flex items-center gap-2">
              <span class="inline-flex size-6 items-center justify-center rounded-lg border border-neutral-200 bg-white">
                <svg class="size-3.5 text-neutral-700"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.6"
                        d="M12 21s-6-4.35-6-10a6 6 0 1 1 12 0c0 5.65-6 10-6 10z" />
                </svg>
              </span>
              <h4 class="text-sm font-semibold tracking-wide text-neutral-800">C. Permintaan Darah</h4>
            </div>
            <dl class="two-col-dl">
              <dt>Jenis Darah</dt>
              <dd id="dm_produk">-</dd>
              <dt>Golongan Darah</dt>
              <dd id="dm_gol">-</dd>
              <dt>Rhesus</dt>
              <dd id="dm_rhesus">-</dd>
              <dt>Jumlah Kantong</dt>
              <dd id="dm_jumlah">-</dd>
              <dt>Alasan Tambahan</dt>
              <dd id="dm_gejala">—</dd>
              <dt>Cek Transfusi</dt>
              <dd id="dm_cek">-</dd>
            </dl>

            <div class="mt-4 grid grid-cols-1 gap-3 rounded-xl border border-neutral-100 bg-white/60 p-3 sm:grid-cols-3">
              <div class="flex items-center justify-between rounded-lg border border-neutral-100 bg-white px-3 py-2">
                <span class="text-xs text-neutral-500">Tgl. Pemesanan</span>
                <span id="dm_tgl_pesan"
                      class="text-sm font-medium text-neutral-900">-</span>
              </div>
              <div class="flex items-center justify-between rounded-lg border border-neutral-100 bg-white px-3 py-2">
                <span class="text-xs text-neutral-500">Tanggal Verifikasi</span>
                <span id="dm_waktu_verifikasi"
                      class="text-sm font-medium text-neutral-900">-</span>
              </div>
              <div class="flex items-center justify-between rounded-lg border border-neutral-100 bg-white px-3 py-2">
                <span class="text-xs text-neutral-500">Status Saat Ini</span>
                <span class="text-sm font-semibold text-neutral-900"
                      id="dm_status_clone">-</span>
              </div>
            </div>
          </section>
        </div>
      </div>

      {{-- Footer --}}
      <div
           class="sticky bottom-0 z-10 flex items-center justify-end gap-3 border-t border-neutral-100/80 bg-white/80 px-4 py-3 backdrop-blur sm:px-6 sm:py-4">
        <button type="button"
                class="dm-close focus:ring-navy-300 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 transition hover:bg-neutral-50 focus:outline-none focus:ring-2">Tutup</button>
      </div>
    </div>
  </div>

  {{-- =============== JS (client-side render) =============== --}}
  <script>
    /* ---------- Data dari controller ---------- */
    const rows = {!! $rowsJson ?? '[]' !!}; // array of { id,nama,tgl,gol,rhesus,produk,kantong,status,payload }

    /* ---------- State ---------- */
    let pageSize = Number(@json($perPage)) || 10;
    let page = 1;
    let qPersist = @json($q); // dari GET, untuk set nilai awal search input
    let golPersist = @json($golQ);
    let prodPersist = @json($produkQ);

    /* ---------- Helper ---------- */
    const statusBadgeClass = s =>
      (s || '').toLowerCase() === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' :
      (s || '').toLowerCase() === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' :
      (s || '').toLowerCase() === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' :
      'bg-neutral-100 text-neutral-700 border border-neutral-200';

    const bloodPill = g =>
      `<span class="inline-flex h-6 items-center rounded-full border border-rose-100 bg-rose-50 px-2 text-xs text-rose-700 font-semibold">${g ?? '-'}</span>`;
    const rhesusPill = r =>
      `<span class="inline-flex h-6 items-center rounded-full border border-slate-100 bg-slate-50 px-2 text-xs text-slate-700 font-semibold">${r ?? '-'}</span>`;
    const productPill = p =>
      `<span class="inline-flex h-6 items-center rounded-full border border-sky-100 bg-sky-50 px-2 text-xs text-sky-700 font-semibold">${p ?? '-'}</span>`;

    const productLabel = c => ({
      WB: 'WB: Whole Blood',
      PRC: 'PRC: Packed Red Cell',
      TC: 'TC: Thrombocyte Concentrate',
      FFP: 'FFP: Fresh Frozen Plasma',
      CRYO: 'CRYO: Cryoprecipitated Anti-Hemophilic Factor',
      LP: 'LP: Liquid Plasma',
      TCA: 'TCA: Thrombocyte Apheresis',
      CP: 'CP: Convalescent Plasma'
    })[c] || c || '-';

    const yaTidak = v => v === true ? 'Ya' : v === false ? 'Tidak' : (['ya', 'tidak'].includes(String(v ?? '')
      .toLowerCase()) ? (String(v).toLowerCase() === 'ya' ? 'Ya' : 'Tidak') : '-');
    const labelJK = v => v === 'L' ? 'Laki-laki' : (v === 'P' ? 'Perempuan' : (v || '-'));
    const jumlahLabel = v => (Number(v || 0) > 0 ? `${Number(v)} kantong` : '-');
    const fmt = v => v ? v : '-';

    /* ---------- Filtering (client-side) ---------- */
    function filterRows() {
      const inp = document.getElementById('searchInput');
      const q = (inp?.value || '').toLowerCase().trim();

      const gol = document.getElementById('golInput')?.value || '';
      const prd = document.getElementById('produkInput')?.value || '';

      return rows.filter(o => {
        // text search ke nama pasien atau RS (ada di payload.rs_pemesan)
        const nama = String(o.nama ?? o.payload?.nama_pasien ?? '').toLowerCase();
        const rs = String(o.payload?.rs_pemesan ?? '').toLowerCase();
        const okQ = !q || nama.includes(q) || rs.includes(q);

        const okGol = !gol || (String(o.gol ?? o.payload?.gol_darah ?? '').toUpperCase() === gol.toUpperCase());
        const okPrd = prd === '' || (String(o.produk ?? o.payload?.produk ?? '') === prd);

        return okQ && okGol && okPrd;
      });
    }

    /* ---------- Paging ---------- */
    function getPaged(data) {
      const total = data.length;
      const pages = Math.max(1, Math.ceil(total / pageSize));
      page = Math.min(page, pages);
      const start = (page - 1) * pageSize;
      const end = start + pageSize;
      return {
        data: data.slice(start, end),
        total,
        pages
      };
    }

    function getPageRange(totalPages, current, max = 5) {
      const out = [];
      const half = Math.floor(max / 2);
      let start = Math.max(1, current - half),
        end = Math.min(totalPages, start + max - 1);
      if (end - start + 1 < max) start = Math.max(1, end - max + 1);
      if (start > 1) {
        out.push(1);
        if (start > 2) out.push('…');
      }
      for (let i = start; i <= end; i++) out.push(i);
      if (end < totalPages) {
        if (end < totalPages - 1) out.push('…');
        out.push(totalPages);
      }
      return out;
    }

    /* ---------- Master Render (global scope agar bisa diakses dari pagination) ---------- */
    function masterRender() {
      const data = filterRows();
      const {
        data: slice,
        total,
        pages
      } = getPaged(data);
      renderTable(slice);
      renderCards(slice);
      renderPagination(total, pages);
    }

    /* ---------- Renderers ---------- */
    function renderTable(slice) {
      const tbody = document.getElementById('tableBody');
      if (!slice.length) {
        tbody.innerHTML = `<tr><td colspan="9" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>`;
        return;
      }
      tbody.innerHTML = slice.map(o => {
        const nama = o.nama ?? o.payload?.nama_pasien ?? '-';
        const rs = o.payload?.rs_pemesan ?? '-';
        const gol = o.gol ?? o.payload?.gol_darah ?? '-';
        const rh = o.rhesus ?? o.payload?.rhesus ?? '-';
        const tgl = o.tgl ?? o.payload?.tanggal ?? '-';
        const prod = o.produk ?? o.payload?.produk ?? '-';
        const stat = o.status ?? o.payload?.status ?? '-';
        const waktu = o.waktu_verifikasi ?? o.payload?.waktu_verifikasi ?? '-';
        const payload = JSON.stringify(o.payload || {});
        return `
      <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
        <td class="px-4 py-3" data-nama_pasien="${nama}">${nama}</td>
        <td class="px-4 py-3" data-rs_pemesan="${rs}">${rs}</td>
        <td class="px-4 py-3" data-golongan_darah="${gol}">${gol ? `${bloodPill(gol)}` : '-'}</td>
        <td class="px-4 py-3" data-rhesus="${rh}">${rh ? `${rhesusPill(rh)}` : '-'}</td>
        <td class="px-4 py-3" data-tanggal_pemesanan="${tgl}">${tgl}</td>
        <td class="px-4 py-3" data-produk_darah="${prod}">${prod ? `${productPill(prod)}` : '-'}</td>
        <td class="px-4 py-3" data-tanggal_verifikasi="${waktu}"><span class="text-sm font-medium text-neutral-700">${waktu}</span></td>
        <td class="px-4 py-3" data-status="${stat}"><span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ${statusBadgeClass(stat)}">${String(stat).charAt(0).toUpperCase()+String(stat).slice(1)}</span></td>
        <td class="px-4 py-3">
          <button type="button" class="lihat-detail-btn inline-flex w-full items-center justify-center gap-2 rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm font-medium text-neutral-700 shadow-sm transition hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-navy-200"
                  data-payload='${payload}'>
            <svg class="size-4 opacity-75" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12s3.5-6.5 9.5-6.5S21.5 12 21.5 12s-3.5 6.5-9.5 6.5S2.5 12 2.5 12z"/>
              <circle cx="12" cy="12" r="2.7" stroke-width="1.8"></circle>
            </svg>
            <span>Lihat Detail</span>
          </button>
        </td>
      </tr>
    `;
      }).join('');
      tbody.querySelectorAll('.lihat-detail-btn').forEach(b => b.addEventListener('click', onDetailClick));
    }

    function renderCards(slice) {
      const wrap = document.getElementById('cardsContainer');
      if (!slice.length) {
        wrap.innerHTML = `<div class="text-center text-neutral-500">Tidak ada data.</div>`;
        return;
      }
      wrap.innerHTML = slice.map(o => {
        const nama = o.nama ?? o.payload?.nama_pasien ?? '-';
        const rs = o.payload?.rs_pemesan ?? '-';
        const gol = o.gol ?? o.payload?.gol_darah ?? '-';
        const rh = o.rhesus ?? o.payload?.rhesus ?? '-';
        const tgl = o.tgl ?? o.payload?.tanggal ?? '-';
        const prod = o.produk ?? o.payload?.produk ?? '-';
        const stat = o.status ?? o.payload?.status ?? '-';
        const waktu = o.waktu_verifikasi ?? o.payload?.waktu_verifikasi ?? '-';
        const payload = JSON.stringify(o.payload || {});
        return `
      <div class="rounded-2xl border border-neutral-200 bg-white p-4">
        <div class="flex items-start justify-between gap-3">
          <p class="text-sm font-medium sm:text-base">${nama}</p>
          <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ${statusBadgeClass(stat)}">${String(stat).charAt(0).toUpperCase()+String(stat).slice(1)}</span>
        </div>
        <p class="mt-0.5 text-xs text-neutral-500">${rs}</p>

        <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
          <div class="text-neutral-500">Golongan</div><div>${gol ? bloodPill(gol) : '-'}</div>
          <div class="text-neutral-500">Rhesus</div><div>${rh ? rhesusPill(rh) : '-'}</div>
          <div class="text-neutral-500">Tanggal</div><div>${tgl}</div>
          <div class="text-neutral-500">Produk</div><div>${prod ? productPill(prod) : '-'}</div>
          <div class="text-neutral-500">Tanggal Verifikasi</div><div class="font-medium text-neutral-700">${waktu}</div>
        </div>

        <div class="mt-3">
          <button type="button" class="lihat-detail-btn inline-flex items-center gap-2 rounded-xl border border-neutral-200 bg-white px-3 py-2 text-xs sm:text-sm font-medium text-neutral-700 shadow-sm transition hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                  data-payload='${payload}'>
            <svg class="size-4 opacity-75" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12s3.5-6.5 9.5-6.5S21.5 12 21.5 12s-3.5 6.5-9.5 6.5S2.5 12 2.5 12z"/>
              <circle cx="12" cy="12" r="2.7" stroke-width="1.8"></circle>
            </svg>
            <span>Lihat Detail</span>
          </button>
        </div>
      </div>
    `;
      }).join('');
      wrap.querySelectorAll('.lihat-detail-btn').forEach(b => b.addEventListener('click', onDetailClick));
    }

    function renderPagination(total, pages) {
      const cont = document.getElementById('pagination');
      const info = document.getElementById('pageInfo');
      const start = total === 0 ? 0 : (page - 1) * pageSize + 1;
      const end = Math.min(page * pageSize, total);
      info.textContent = total > 0 ? `Menampilkan ${start}-${end} dari ${total} data` : 'Tidak ada data';
      if (pages <= 1) {
        cont.innerHTML = '';
        return;
      }

      const btn = (lab, goto, disabled = false, active = false) => `
    <button class="min-w-9 h-9 px-3 rounded-lg border text-sm ${active?'bg-neutral-900 text-white border-neutral-900':'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'} ${disabled?'opacity-50 cursor-not-allowed':''}"
            ${disabled?'disabled':''} data-page="${goto}">${lab}</button>`;
      let html = btn('«', page - 1, page === 1);
      const range = getPageRange(pages, page, 5);
      range.forEach(p => html += (p === '…') ? `<span class="px-2 text-neutral-400">…</span>` : btn(p, p, false, p ===
        page));
      html += btn('»', page + 1, page === pages);
      cont.innerHTML = html;
      cont.querySelectorAll('button[data-page]:not([disabled])').forEach(b => {
        b.addEventListener('click', (e) => {
          e.preventDefault();
          const newPage = parseInt(b.dataset.page);
          if (!isNaN(newPage) && newPage >= 1 && newPage <= pages && newPage !== page) {
            page = newPage;
            masterRender();
            // Scroll ke atas setelah ganti halaman
            window.scrollTo({
              top: 0,
              behavior: 'smooth'
            });
          }
        });
      });
    }

    /* ---------- Modal Detail ---------- */
    const detailModal = document.getElementById('detailModal');
    const detailCard = document.getElementById('detailCard');
    const lastFocusDetail = {
      el: null
    };

    const dm = {
      status: document.getElementById('dm_status'),
      tglPesan: document.getElementById('dm_tgl_pesan'),
      tglMinta: document.getElementById('dm_tgl_minta'),
      waktuVerifikasi: document.getElementById('dm_waktu_verifikasi'),
      nama: document.getElementById('dm_nama'),
      rs: document.getElementById('dm_rs'),
      jk: document.getElementById('dm_jk'),
      dokter: document.getElementById('dm_dokter'),
      noRegis: document.getElementById('dm_no_regis'),
      email: document.getElementById('dm_email'),
      telp: document.getElementById('dm_telp'),
      gol: document.getElementById('dm_gol'),
      rhesus: document.getElementById('dm_rhesus'),
      produk: document.getElementById('dm_produk'),
      jumlah: document.getElementById('dm_jumlah'),
      alasan: document.getElementById('dm_alasan'),
      gejala: document.getElementById('dm_gejala'),
      cek: document.getElementById('dm_cek'),
      suamiIstri: document.getElementById('dm_suami_istri'),
      diagnosa: document.getElementById('dm_diagnosa'),
      pernahSerologi: document.getElementById('dm_pernah_serologi'),
      lokasiSerologi: document.getElementById('dm_lokasi_serologi'),
      tglSerologi: document.getElementById('dm_tgl_serologi'),
      tglTransfusi: document.getElementById('dm_tgl_transfusi'),
      hasilSerologi: document.getElementById('dm_hasil_serologi'),
      statusClone: document.getElementById('dm_status_clone'),
    };

    const FOCUSABLE_SELECTOR = [
      'a[href]', 'area[href]', 'button:not([disabled])', 'input:not([disabled]):not([type="hidden"])',
      'select:not([disabled])', 'textarea:not([disabled])', 'details', '[contenteditable="true"]',
      '[tabindex]:not([tabindex="-1"])'
    ].join(',');

    const getFocusable = c => Array.from(c.querySelectorAll(FOCUSABLE_SELECTOR))
      .filter(el => el.offsetParent !== null || el === document.activeElement);

    function trapTabKey(e, container) {
      if (e.key !== 'Tab') return;
      const nodes = getFocusable(container);
      if (!nodes.length) {
        e.preventDefault();
        container.focus();
        return;
      }
      const first = nodes[0],
        last = nodes[nodes.length - 1];
      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    }

    function openModal(overlay, dialog, firstFocus, store) {
      store.el = document.activeElement;
      overlay.classList.remove('hidden');
      overlay.classList.add('flex');
      overlay.setAttribute('aria-hidden', 'false');
      document.documentElement.classList.add('overflow-hidden');
      const target = firstFocus || getFocusable(dialog)[0] || dialog;
      requestAnimationFrame(() => target && target.focus());
      dialog.__trapHandler = (e) => trapTabKey(e, dialog);
      dialog.addEventListener('keydown', dialog.__trapHandler);
    }

    function closeModal(overlay, dialog, store) {
      overlay.classList.add('hidden');
      overlay.classList.remove('flex');
      overlay.setAttribute('aria-hidden', 'true');
      document.documentElement.classList.remove('overflow-hidden');
      if (dialog.__trapHandler) {
        dialog.removeEventListener('keydown', dialog.__trapHandler);
        delete dialog.__trapHandler;
      }
      if (store.el && typeof store.el.focus === 'function') store.el.focus();
    }

    function colorStatusChip(s) {
      const base = 'inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs font-medium ';
      let tone = s === 'approved' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' :
        s === 'rejected' ? 'border-rose-200 bg-rose-50 text-rose-700' :
        s === 'pending' ? 'border-amber-200 bg-amber-50 text-amber-700' :
        'border-neutral-200 bg-neutral-50 text-neutral-700';
      dm.status.className = base + tone;
    }

    function openDetail(payload) {
      const s = (payload.status || '-').toString();
      dm.status.textContent = s.charAt(0).toUpperCase() + s.slice(1);
      dm.statusClone.textContent = dm.status.textContent;
      colorStatusChip(s.toLowerCase());

      dm.tglPesan.textContent = fmt(payload.tanggal_pemesanan ?? payload.tanggal);
      dm.tglMinta.textContent = fmt(payload.tanggal_permintaan ?? payload.tanggal);
      dm.waktuVerifikasi.textContent = fmt(payload.waktu_verifikasi);

      dm.nama.textContent = payload.nama_pasien ?? '-';
      dm.rs.textContent = payload.rs_pemesan ?? '-';
      dm.jk.textContent = labelJK(payload.jenis_kelamin);
      dm.dokter.textContent = payload.nama_dokter ?? '-';
      dm.noRegis.textContent = payload.no_regis_rs ?? '-';
      dm.email.textContent = payload.email ?? '-';
      dm.telp.textContent = payload.nomor_telepon ?? '-';
      dm.suamiIstri.textContent = payload.nama_suami_istri ?? '-';

      dm.gol.textContent = payload.gol_darah ?? '-';
      dm.rhesus.textContent = payload.rhesus ?? '-';
      dm.produk.textContent = productLabel(payload.produk);
      dm.jumlah.textContent = jumlahLabel(payload.jumlah_kantong);

      dm.alasan.textContent = payload.alasan_transfusi ?? '-';
      dm.gejala.textContent = (payload.alasan_tambahan ?? payload.gejala_transfusi ?? '').toString().trim() || '—';
      dm.cek.textContent = yaTidak(payload.cek_transfusi);

      dm.diagnosa.textContent = payload.diagnosa_klinik ?? '-';
      dm.pernahSerologi.textContent = yaTidak(payload.pernah_serologi);
      dm.lokasiSerologi.textContent = payload.lokasi_serologi ?? '-';
      dm.tglSerologi.textContent = fmt(payload.tanggal_serologi);
      dm.tglTransfusi.textContent = fmt(payload.tanggal_transfusi);
      dm.hasilSerologi.textContent = payload.hasil_serologi ?? '-';

      openModal(detailModal, detailCard, detailCard, lastFocusDetail);
      requestAnimationFrame(() => {
        detailModal.classList.add('modal-show');
        detailCard.classList.add('card-show');
        document.getElementById('pageRoot')?.setAttribute('aria-hidden', 'true');
      });
    }

    function closeDetail() {
      detailCard.classList.remove('card-show');
      detailModal.classList.remove('modal-show');
      setTimeout(() => {
        closeModal(detailModal, detailCard, lastFocusDetail);
        document.getElementById('pageRoot')?.removeAttribute('aria-hidden');
      }, 200);
    }
    detailModal.querySelectorAll('.dm-close').forEach(b => b.addEventListener('click', closeDetail));
    detailModal.addEventListener('click', (e) => {
      if (e.target === detailModal) closeDetail();
    });
    detailCard.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        e.preventDefault();
        closeDetail();
      }
    });

    function onDetailClick(e) {
      const payload = JSON.parse(e.currentTarget.dataset.payload || '{}');
      openDetail(payload);
    }

    /* ---------- Toolbar interaksi (sinkron UI ↔ filter client-side) ---------- */
    document.addEventListener('DOMContentLoaded', () => {
      // set nilai awal search input (dari GET) untuk konsistensi chip
      const search = document.getElementById('searchInput');
      if (search && qPersist) search.value = qPersist;

      // search realtime
      if (search) {
        search.addEventListener('input', () => {
          page = 1;
          masterRender();
        });
      }

      // menu & filter dropdowns
      const form = document.getElementById('filterForm');

      const filterBtn = document.getElementById('filterBtn');
      const pageSizeBtn = document.getElementById('pageSizeBtn');
      const produkBtn = document.getElementById('produkBtn');
      const golBtn = document.getElementById('golBtn');

      const filterMenu = document.getElementById('filterMenu');
      const pageSizeMenu = document.getElementById('pageSizeMenu');
      const produkMenu = document.getElementById('produkMenu');
      const golMenu = document.getElementById('golMenu');

      const produkLabel = document.getElementById('produkLabel');
      const golLabel = document.getElementById('golLabel');

      const produkInput = document.getElementById('produkInput');
      const golInput = document.getElementById('golInput');
      const perPageInput = document.getElementById('perPageInput');

      const apply = document.getElementById('applyBtn');
      const reset = document.getElementById('resetBtn');

      let produkSelected = prodPersist;
      let golSelected = golPersist;

      const MENUS = [filterMenu, pageSizeMenu, produkMenu, golMenu];
      const isOpen = el => el && !el.classList.contains('hidden');
      const open = el => el && el.classList.remove('hidden');
      const close = el => el && el.classList.add('hidden');
      const setExpanded = (btn, val) => btn && btn.setAttribute('aria-expanded', val ? 'true' : 'false');

      function closeAll(excepts = []) {
        MENUS.forEach(m => {
          if (m && !excepts.includes(m)) close(m);
        });
        setExpanded(filterBtn, excepts.includes(filterMenu) && isOpen(filterMenu));
        setExpanded(pageSizeBtn, excepts.includes(pageSizeMenu) && isOpen(pageSizeMenu));
        setExpanded(produkBtn, excepts.includes(produkMenu) && isOpen(produkMenu));
        setExpanded(golBtn, excepts.includes(golMenu) && isOpen(golMenu));
      }

      // Filter panel
      filterBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const willOpen = !isOpen(filterMenu);
        closeAll();
        if (willOpen) {
          open(filterMenu);
          setExpanded(filterBtn, true);
        }
      });

      // Produk
      function updateProdukActive(value) {
        produkMenu.querySelectorAll('.produk-item').forEach(el => {
          const active = el.getAttribute('data-value') === (value ?? '');
          el.setAttribute('aria-checked', active ? 'true' : 'false');
          el.classList.toggle('bg-blue-50', active);
          el.classList.toggle('text-blue-800', active);
          el.classList.toggle('ring-1', active);
          el.classList.toggle('ring-blue-200', active);
          el.classList.toggle('cursor-default', active);
        });
      }
      produkBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const willOpen = !isOpen(produkMenu);
        closeAll([filterMenu]);
        if (willOpen) {
          open(produkMenu);
          setExpanded(produkBtn, true);
          updateProdukActive(produkSelected || '');
        }
      });
      produkMenu.querySelectorAll('.produk-item').forEach(item => {
        item.addEventListener('click', () => {
          const val = item.getAttribute('data-value') ?? '';
          const label = item.querySelector('span')?.textContent?.trim() || 'Semua Produk';
          produkSelected = val;
          produkLabel.textContent = label;
          updateProdukActive(val);
          close(produkMenu);
          setExpanded(produkBtn, false);
        });
      });

      // Gol
      function updateGolActive(value) {
        golMenu.querySelectorAll('.gol-item').forEach(el => {
          const active = el.getAttribute('data-value') === (value ?? '');
          el.setAttribute('aria-checked', active ? 'true' : 'false');
          el.classList.toggle('bg-blue-50', active);
          el.classList.toggle('text-blue-800', active);
          el.classList.toggle('ring-1', active);
          el.classList.toggle('ring-blue-200', active);
          el.classList.toggle('cursor-default', active);
        });
      }
      golBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const willOpen = !isOpen(golMenu);
        closeAll([filterMenu]);
        if (willOpen) {
          open(golMenu);
          setExpanded(golBtn, true);
          updateGolActive(golSelected || '');
        }
      });
      golMenu.querySelectorAll('.gol-item').forEach(item => {
        item.addEventListener('click', () => {
          const val = item.getAttribute('data-value') ?? '';
          const label = item.querySelector('span')?.textContent?.trim() || 'Semua';
          golSelected = val;
          golLabel.textContent = label;
          updateGolActive(val);
          close(golMenu);
          setExpanded(golBtn, false);
        });
      });

      // Apply & Reset (submit GET untuk persist URL, tapi render tetap client-side)
      apply.addEventListener('click', () => {
        produkInput.value = produkSelected || '';
        golInput.value = golSelected || '';
        closeAll();
        form.submit(); // persist URL
      });
      reset.addEventListener('click', () => {
        produkSelected = '';
        golSelected = '';
        produkLabel.textContent = 'Semua Produk';
        golLabel.textContent = 'Semua';
        produkInput.value = '';
        golInput.value = '';
        const q = form.querySelector('input[name="q"]');
        if (q) q.value = '';
        closeAll();
        form.submit();
      });

      // Chips remove
      document.querySelectorAll('.remove-chip').forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          const target = btn.dataset.target;
          if (target === 'produk') {
            produkSelected = '';
            produkInput.value = '';
          } else if (target === 'gol') {
            golSelected = '';
            golInput.value = '';
          } else if (target === 'q') {
            const q = form.querySelector('input[name="q"]');
            if (q) q.value = '';
          }
          closeAll();
          form.submit();
        });
      });
      const clearAllBtn = document.getElementById('clearAllBtn');
      if (clearAllBtn) clearAllBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const q = form.querySelector('input[name="q"]');
        if (q) q.value = '';
        produkSelected = '';
        golSelected = '';
        produkInput.value = '';
        golInput.value = '';
        closeAll();
        form.submit();
      });

      // Page size (persist di URL, tapi dipakai juga sebagai pageSize awal)
      pageSizeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const willOpen = !isOpen(pageSizeMenu);
        closeAll();
        if (willOpen) {
          open(pageSizeMenu);
          setExpanded(pageSizeBtn, true);
        }
      });
      pageSizeMenu.querySelectorAll('button[data-size]').forEach(item => {
        item.addEventListener('click', () => {
          perPageInput.value = item.getAttribute('data-size');
          pageSizeMenu.querySelectorAll('button[role="menuitemradio"]').forEach(b => b.setAttribute(
            'aria-checked', b === item ? 'true' : 'false'));
          closeAll();
          form.submit(); // persist URL dan reload supaya pageSize awal sinkron
        });
      });

      // Click outside & ESC menutup menu
      document.addEventListener('click', (e) => {
        const safe = e.target.closest(
          '#filterMenu, #pageSizeMenu, #produkMenu, #golMenu, #filterBtn, #pageSizeBtn, #produkBtn, #golBtn');
        if (!safe) closeAll();
      });
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAll();
      });

      // Render awal
      pageSize = Number(@json($perPage)) || 10; // sync pageSize dari GET
      page = 1;
      masterRender();

      // Initialize Table Sorter
      if (typeof TableSorter !== 'undefined') {
        new TableSorter('#riwayatTable');
      }

      // Klik detail (delegasi untuk table & cards)
      document.addEventListener('click', (e) => {
        const b = e.target.closest('.lihat-detail-btn');
        if (!b) return;
        try {
          openDetail(JSON.parse(b.dataset.payload || '{}'));
        } catch (err) {
          console.error('Payload invalid:', err);
          alert('Gagal membuka detail.');
        }
      });
    });
  </script>

  <style>
    .two-col-dl {
      display: grid;
      grid-template-columns: 1fr;
      gap: .5rem 1.25rem;
      font-size: .875rem;
    }

    .two-col-dl dt {
      color: rgb(115 115 115);
    }

    .two-col-dl dd {
      color: rgb(23 23 23);
    }

    @media (min-width:768px) {
      .two-col-dl {
        grid-template-columns: 1fr 1.2fr;
        gap: .5rem 2rem;
      }
    }

    /* Animasi modal */
    #detailModal.modal-show {
      opacity: 1;
    }

    #detailModal:not(.modal-show) {
      opacity: 0;
    }

    #detailCard.card-show {
      transform: translateY(0) scale(1);
      opacity: 1;
    }

    #detailCard:not(.card-show) {
      transform: translateY(.5rem) scale(.97);
      opacity: 0;
    }

    /* Hover kecil untuk header sortable (kalau nanti ditambah) */
    th.sortable:hover {
      background-color: rgba(0, 0, 0, .02);
    }

    /* Check icon in menus */
    .check-icon {
      transition: opacity .12s ease;
    }
  </style>
@endsection
