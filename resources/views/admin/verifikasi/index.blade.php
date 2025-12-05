{{-- resources/views/admin/verifikasi/index.blade.php --}}
@extends('layouts.admin')

@section('content')
  <div id="pageRoot"
       class="space-y-6">
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
      <h1 class="text-2xl font-semibold md:text-3xl">Verifikasi Pemesanan</h1>
      <p class="text-sm text-neutral-500">Kelola dan verifikasi permintaan darah dari rumah sakit</p>
    </div>

    @php
      /* Badges */
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

      // Filter state
      $q = request('q', '');
      $statusQ = request('status', '');
      $golQ = request('gol', '');
      $produkQ = request('produk', '');
      $perPage = (int) request('per_page', 10);

      $statusMap = [
          'approved' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
          'pending' => 'bg-amber-50 text-amber-700 border border-amber-200',
          'rejected' => 'bg-rose-50 text-rose-700 border border-rose-200',
      ];

      // Opsi produk (dipakai di chip & dropdown)
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

      $hasActiveFilter = $q || $golQ || $produkQ || $statusQ;
    @endphp

    {{-- =================== Toolbar (Search • Filters • Page Size) =================== --}}
    <form id="filterForm"
          method="GET"
          class="space-y-3">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        {{-- Search --}}
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
          <input name="q"
                 value="{{ $q }}"
                 type="text"
                 class="focus:border-navy-300 focus:ring-navy-200 w-full rounded-2xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:outline-none focus:ring-2"
                 placeholder="Cari nama pasien atau rumah sakit" />
        </div>

        {{-- Filter dropdown trigger --}}
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

          {{-- Panel filter --}}
          <div id="filterMenu"
               role="menu"
               aria-labelledby="filterBtn"
               class="absolute right-0 z-20 mt-2 hidden w-[22rem] rounded-2xl border border-neutral-200 bg-white p-3 shadow-xl">
            <div class="space-y-3">
              {{-- Produk (dropdown lembut kustom) --}}
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

              {{-- Golongan (dropdown lembut kustom) --}}
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
                  @php $gOpts = [''=>'Semua','A'=>'A','B'=>'B','AB'=>'AB','O'=>'O']; @endphp
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

              {{-- Actions --}}
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

        {{-- Page size (di luar panel filter) --}}
        @php $sizes = [5,10,20]; @endphp
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

      {{-- Active filter chips --}}
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
                  class="text-xs text-neutral-600 hover:underline">Bersihkan
            semua</button>
        </div>
      @endif

      {{-- Hidden inputs (untuk submit GET) --}}
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
        <table id="verifikasiTable"
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
              <x-sortable-th column="status"
                             label="Status" />
              <th class="px-4 py-3 font-medium">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pemesanan as $o)
              @php
                $statusClass = $statusMap[$o->status] ?? 'bg-neutral-100 text-neutral-700 border border-neutral-200';
                $tglBaris = \Illuminate\Support\Carbon::parse(
                    optional($o->verifikasiTerakhir)->tanggal_permintaan ?? $o->created_at,
                )->toDateString();
                $payload = [
                    'id' => $o->id,
                    'status' => $o->status,
                    'tanggal_pemesanan' =>
                        optional($o->tanggal_pemesanan)->toDateString() ?? ($o->tanggal_pemesanan ?? null),
                    'tanggal_permintaan' =>
                        optional($o->tanggal_permintaan)->toDateString() ?? ($o->tanggal_permintaan ?? null),
                    'nama_pasien' => $o->nama_pasien,
                    'rs_pemesan' => $o->rs_pemesan,
                    'jenis_kelamin' => $o->jenis_kelamin,
                    'nama_dokter' => $o->nama_dokter,
                    'email' => $o->email,
                    'nomor_telepon' => $o->nomor_telepon,
                    'no_regis_rs' => $o->no_regis_rs,
                    'gol_darah' => $o->gol_darah,
                    'rhesus' => $o->rhesus,
                    'produk' => $o->produk,
                    'jumlah_kantong' => $o->jumlah_kantong,
                    'alasan_tambahan' => $o->alasan_tambahan,
                    'alasan_transfusi' => $o->alasan_transfusi,
                    'gejala_transfusi' => $o->gejala_transfusi,
                    'cek_transfusi' => (bool) $o->cek_transfusi,
                    'nama_suami_istri' => $o->nama_suami_istri,
                    'diagnosa_klinik' => $o->diagnosa_klinik,
                    'pernah_serologi' => $o->pernah_serologi,
                    'lokasi_serologi' => $o->lokasi_serologi,
                    'tanggal_serologi' =>
                        optional($o->tanggal_serologi)->toDateString() ?? ($o->tanggal_serologi ?? null),
                    'tanggal_transfusi' =>
                        optional($o->tanggal_transfusi)->toDateString() ?? ($o->tanggal_transfusi ?? null),
                    'hasil_serologi' => $o->hasil_serologi,
                    'jumlah_kehamilan' => $o->jumlah_kehamilan,
                    'abortus' => $o->abortus,
                    'riwayat_hemolitik' => $o->riwayat_hemolitik,
                    'tanggal' => $tglBaris,
                ];
              @endphp
              <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
                <td class="px-4 py-3"
                    data-nama_pasien="{{ $o->nama_pasien }}">{{ $o->nama_pasien }}</td>
                <td class="px-4 py-3"
                    data-rs_pemesan="{{ $o->rs_pemesan }}">{{ $o->rs_pemesan }}</td>
                <td class="px-4 py-3"
                    data-golongan_darah="{{ $o->gol_darah }}">{!! $o->gol_darah ? blood_pill($o->gol_darah) : '-' !!}</td>
                <td class="px-4 py-3"
                    data-rhesus="{{ $o->rhesus }}">{!! $o->rhesus ? rhesus_pill($o->rhesus) : '-' !!}</td>
                <td class="px-4 py-3"
                    data-tanggal_pemesanan="{{ $tglBaris }}">
                  {{ \Illuminate\Support\Carbon::parse($tglBaris)->format('d-m-Y') }}
                </td>
                <td class="px-4 py-3"
                    data-produk_darah="{{ $o->produk }}">{!! $o->produk ? product_pill($o->produk) : '-' !!}</td>
                <td class="px-4 py-3"
                    data-status="{{ $o->status }}">
                  <span
                        class="{{ $statusClass }} inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium">{{ ucfirst($o->status) }}</span>
                </td>
                <td class="px-4 py-3">
                  <button type="button"
                          class="lihat-detail-btn focus:ring-navy-200 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm font-medium text-neutral-700 shadow-sm transition hover:bg-neutral-50 focus:outline-none focus:ring-2"
                          data-action="{{ route('admin.verifikasi.store', $o) }}"
                          data-payload='@json($payload)'
                          aria-label="Lihat detail pemesanan {{ $o->nama_pasien }}">
                    <svg class="size-4 opacity-75"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         aria-hidden="true">
                      <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M2.5 12s3.5-6.5 9.5-6.5S21.5 12 21.5 12s-3.5 6.5-9.5 6.5S2.5 12 2.5 12z" />
                      <circle cx="12"
                              cy="12"
                              r="2.7"
                              stroke-width="1.8"></circle>
                    </svg>
                    <span>Lihat Detail</span>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8"
                    class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- =================== CARDS (mobile) =================== --}}
    <div class="space-y-3 md:hidden">
      @forelse($pemesanan as $o)
        @php
          $statusClass = $statusMap[$o->status] ?? 'bg-neutral-100 text-neutral-700 border border-neutral-200';
          $tgl = optional($o->verifikasiTerakhir)->tanggal_permintaan ?? $o->created_at;
          $tglBaris = \Illuminate\Support\Carbon::parse($tgl)->toDateString();
          $payloadMobile = [
              'id' => $o->id,
              'status' => $o->status,
              'tanggal' => $tglBaris,
              'tanggal_pemesanan' => optional($o->tanggal_pemesanan)->toDateString() ?? ($o->tanggal_pemesanan ?? null),
              'tanggal_permintaan' =>
                  optional($o->tanggal_permintaan)->toDateString() ?? ($o->tanggal_permintaan ?? null),
              'nama_pasien' => $o->nama_pasien,
              'rs_pemesan' => $o->rs_pemesan,
              'jenis_kelamin' => $o->jenis_kelamin,
              'nama_dokter' => $o->nama_dokter,
              'email' => $o->email,
              'nomor_telepon' => $o->nomor_telepon,
              'no_regis_rs' => $o->no_regis_rs,
              'gol_darah' => $o->gol_darah,
              'rhesus' => $o->rhesus,
              'produk' => $o->produk,
              'jumlah_kantong' => $o->jumlah_kantong,
              'alasan_transfusi' => $o->alasan_transfusi,
              'alasan_tambahan' => $o->alasan_tambahan,
              'gejala_transfusi' => $o->gejala_transfusi,
              'cek_transfusi' => (bool) $o->cek_transfusi,
              'nama_suami_istri' => $o->nama_suami_istri,
              'diagnosa_klinik' => $o->diagnosa_klinik,
              'pernah_serologi' => $o->pernah_serologi,
              'lokasi_serologi' => $o->lokasi_serologi,
              'tanggal_serologi' => optional($o->tanggal_serologi)->toDateString() ?? ($o->tanggal_serologi ?? null),
              'tanggal_transfusi' => optional($o->tanggal_transfusi)->toDateString() ?? ($o->tanggal_transfusi ?? null),
              'hasil_serologi' => $o->hasil_serologi,
              'jumlah_kehamilan' => $o->jumlah_kehamilan,
              'abortus' => $o->abortus,
              'riwayat_hemolitik' => $o->riwayat_hemolitik,
          ];
        @endphp

        <div class="rounded-2xl border border-neutral-200 bg-white p-4">
          <div class="flex items-start justify-between gap-3">
            <p class="text-sm font-medium sm:text-base">{{ $o->nama_pasien }}</p>
            <span
                  class="{{ $statusClass }} inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium">{{ ucfirst($o->status) }}</span>
          </div>
          <p class="mt-0.5 text-xs text-neutral-500">{{ $o->rs_pemesan }}</p>

          <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
            <div class="text-neutral-500">Golongan</div>
            <div>{!! $o->gol_darah ? blood_pill($o->gol_darah) : '-' !!}</div>
            <div class="text-neutral-500">Rhesus</div>
            <div>{!! $o->rhesus ? rhesus_pill($o->rhesus) : '-' !!}</div>
            <div class="text-neutral-500">Tanggal</div>
            <div>{{ \Illuminate\Support\Carbon::parse($tgl)->format('d-m-Y') }}</div>
            <div class="text-neutral-500">Produk</div>
            <div>{!! $o->produk ? product_pill($o->produk) : '-' !!}</div>
          </div>

          <div class="mt-3">
            <button type="button"
                    class="lihat-detail-btn inline-flex items-center gap-2 rounded-xl border border-neutral-200 bg-white px-3 py-2 text-xs font-medium text-neutral-700 shadow-sm transition hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-emerald-200 sm:text-sm"
                    data-action="{{ route('admin.verifikasi.store', $o) }}"
                    data-payload='@json($payloadMobile)'
                    aria-label="Lihat detail pemesanan {{ $o->nama_pasien }}">
              <svg class="size-4 opacity-75"
                   viewBox="0 0 24 24"
                   fill="none"
                   stroke="currentColor"
                   aria-hidden="true">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M2.5 12s3.5-6.5 9.5-6.5S21.5 12 21.5 12s-3.5 6.5-9.5 6.5S2.5 12 2.5 12z" />
                <circle cx="12"
                        cy="12"
                        r="2.7"
                        stroke-width="1.8"></circle>
              </svg>
              <span>Lihat Detail</span>
            </button>
          </div>
        </div>
      @empty
        <div class="text-center text-neutral-500">Tidak ada data.</div>
      @endforelse
    </div>

    {{-- Pagination footer --}}
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
      <div class="text-sm text-neutral-600">
        @if ($pemesanan->total() > 0)
          Menampilkan {{ $pemesanan->firstItem() }}–{{ $pemesanan->lastItem() }} dari {{ $pemesanan->total() }}
          data
        @else
          Tidak ada data
        @endif
      </div>
      <div>{{ $pemesanan->withQueryString()->links() }}</div>
    </div>
  </div>

  {{-- =================== Modal: Detail Pemesanan =================== --}}
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
              class="truncate text-lg font-semibold tracking-tight text-neutral-900 sm:text-xl">
            Detail Pemesanan
          </h3>
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
                 stroke="currentColor"
                 aria-hidden="true">
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
                     stroke="currentColor"
                     aria-hidden="true">
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
                     stroke="currentColor"
                     aria-hidden="true">
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
          <section class="rounded-2xl border border-neutral-100 bg-neutral-50/60 p-3 sm:p-4">
            <div class="mb-3 flex items-center gap-2">
              <span class="inline-flex size-6 items-center justify-center rounded-lg border border-neutral-200 bg-white">
                <svg class="size-3.5 text-neutral-700"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     aria-hidden="true">
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
            </dl>
          </section>

          {{-- D. Data Kehamilan (hanya untuk pasien perempuan) --}}
          <section id="dm_section_kehamilan"
                   class="hidden rounded-2xl border border-neutral-100 bg-neutral-50/60 p-3 sm:p-4">
            <div class="mb-3 flex items-center gap-2">
              <span class="inline-flex size-6 items-center justify-center rounded-lg border border-neutral-200 bg-white">
                <svg class="size-3.5 text-neutral-700"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     aria-hidden="true">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.6"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </span>
              <h4 class="text-sm font-semibold tracking-wide text-neutral-800">D. Data Kehamilan</h4>
            </div>
            <dl class="two-col-dl">
              <dt>Jumlah Kehamilan</dt>
              <dd id="dm_jumlah_kehamilan">-</dd>
              <dt>Abortus</dt>
              <dd id="dm_abortus">-</dd>
              <dt>Riwayat Hemolitik</dt>
              <dd id="dm_riwayat_hemolitik">-</dd>
            </dl>
          </section>

          {{-- Info Tanggal & Status --}}
          <section class="md:col-span-2">
            <div class="grid grid-cols-1 gap-3 rounded-xl border border-neutral-100 bg-white/60 p-3 sm:grid-cols-2">
              <div class="flex items-center justify-between rounded-lg border border-neutral-100 bg-white px-3 py-2">
                <span class="text-xs text-neutral-500">Tgl. Pemesanan</span>
                <span id="dm_tgl_pesan"
                      class="text-sm font-medium text-neutral-900">-</span>
              </div>
              <div class="flex items-center justify-between rounded-lg border border-neutral-100 bg-white px-3 py-2">
                <span class="text-xs text-neutral-500">Status Saat Ini</span>
                <span class="text-sm font-semibold text-neutral-900"
                      id="dm_status_clone">-</span>
              </div>
            </div>
          </section>

          {{-- Form POST (hidden) --}}
          <form id="dm_form"
                method="POST"
                action="#"
                class="hidden md:col-span-2">
            @csrf
            <input type="hidden"
                   name="status"
                   id="dm_status_input"
                   value="">
            <input type="hidden"
                   name="tanggal_permintaan"
                   id="dm_tanggal_input"
                   value="">
          </form>
        </div>
      </div>

      {{-- Footer --}}
      <div
           class="sticky bottom-0 z-10 flex items-center justify-between gap-3 border-t border-neutral-100/80 bg-white/80 px-4 py-3 backdrop-blur sm:px-6 sm:py-4">
        <button type="button"
                class="dm-close focus:ring-navy-300 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 transition hover:bg-neutral-50 focus:outline-none focus:ring-2">Tutup</button>
        <div id="dm_action_buttons"
             class="flex items-center gap-2">
          <button type="button"
                  id="dm_reject"
                  class="min-h-10 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700 transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-200">Tolak</button>
          <button type="button"
                  id="dm_approve"
                  class="min-h-10 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700 transition hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-200">Setuju</button>
        </div>
      </div>
    </div>
  </div>

  {{-- =================== Modal: Konfirmasi =================== --}}
  <div id="confirmModal"
       class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/40 p-3 backdrop-blur-sm transition-opacity duration-150 sm:p-4"
       aria-hidden="true">
    <div id="confirmCard"
         class="w-full max-w-md overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-2xl"
         role="dialog"
         aria-modal="true"
         aria-labelledby="confirmModalTitle"
         tabindex="-1">
      <div class="px-4 pt-4 sm:px-5 sm:pt-5">
        <div class="flex items-start gap-3">
          <div class="min-w-0">
            <h4 id="confirmModalTitle"
                class="text-base font-semibold text-neutral-900">Konfirmasi</h4>
            <p id="cm_desc"
               class="mt-0.5 text-sm text-neutral-600">Apakah Anda yakin?</p>
          </div>
        </div>
      </div>
      <div class="mt-4 flex items-center justify-end gap-2 border-t border-neutral-100 px-4 py-3 sm:px-5 sm:py-3.5">
        <button type="button"
                id="cm_cancel"
                class="min-h-10 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 transition hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-emerald-300">Batal</button>
        <button type="button"
                id="cm_ok"
                class="min-h-10 rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-300">Ya,
          lanjutkan</button>
      </div>
    </div>
  </div>

  {{-- =================== JS =================== --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      /* ====== UTIL: Focus helpers (POIN 2 & 3) ====== */
      const FOCUSABLE_SELECTOR = [
        'a[href]',
        'area[href]',
        'button:not([disabled])',
        'input:not([disabled]):not([type="hidden"])',
        'select:not([disabled])',
        'textarea:not([disabled])',
        'details',
        '[contenteditable="true"]',
        '[tabindex]:not([tabindex="-1"])'
      ].join(',');

      function getFocusable(container) {
        return Array.from(container.querySelectorAll(FOCUSABLE_SELECTOR))
          .filter(el => el.offsetParent !== null || el === document.activeElement);
      }

      function trapTabKey(e, container) {
        if (e.key !== 'Tab') return;
        const nodes = getFocusable(container);
        if (!nodes.length) {
          // kalau tidak ada fokusable, tahan tab, tetap fokus di dialog
          e.preventDefault();
          container.focus();
          return;
        }
        const first = nodes[0];
        const last = nodes[nodes.length - 1];
        if (e.shiftKey && document.activeElement === first) {
          e.preventDefault();
          last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      }

      function openModal(overlayEl, dialogEl, firstFocusEl, lastActiveStore) {
        // simpan pemicu fokus
        lastActiveStore.el = document.activeElement;
        overlayEl.classList.remove('hidden');
        overlayEl.classList.add('flex');
        overlayEl.setAttribute('aria-hidden', 'false');
        document.documentElement.classList.add('overflow-hidden'); // lock scroll page

        // fokus awal
        const target = firstFocusEl || getFocusable(dialogEl)[0] || dialogEl;
        requestAnimationFrame(() => (target && target.focus()));

        // pasang trap
        dialogEl.__trapHandler = (e) => trapTabKey(e, dialogEl);
        dialogEl.addEventListener('keydown', dialogEl.__trapHandler);
      }

      function closeModal(overlayEl, dialogEl, lastActiveStore) {
        overlayEl.classList.add('hidden');
        overlayEl.classList.remove('flex');
        overlayEl.setAttribute('aria-hidden', 'true');
        document.documentElement.classList.remove('overflow-hidden');

        // lepas trap
        if (dialogEl.__trapHandler) {
          dialogEl.removeEventListener('keydown', dialogEl.__trapHandler);
          delete dialogEl.__trapHandler;
        }

        // restore fokus ke pemicu
        if (lastActiveStore.el && typeof lastActiveStore.el.focus === 'function') {
          lastActiveStore.el.focus();
        }
      }

      /* ====== FILTER & PAGE SIZE ====== */
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
      const produkInput = document.getElementById('produkInput');
      const golLabel = document.getElementById('golLabel');
      const golInput = document.getElementById('golInput');

      const apply = document.getElementById('applyBtn');
      const reset = document.getElementById('resetBtn');

      let produkSelected = @json($produkQ);
      let golSelected = @json($golQ);

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

      // Page size
      pageSizeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const willOpen = !isOpen(pageSizeMenu);
        closeAll();
        if (willOpen) {
          open(pageSizeMenu);
          setExpanded(pageSizeBtn, true);
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

      // Apply & Reset
      apply.addEventListener('click', () => {
        produkInput.value = produkSelected || '';
        golInput.value = golSelected || '';
        closeAll();
        form.submit();
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

      /* ===== Hapus chip & Bersihkan semua ===== */
      const clearAllBtn = document.getElementById('clearAllBtn');

      // tombol "x" di setiap chip
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
          if (typeof closeAll === 'function') closeAll();
          form.submit();
        });
      });

      // tombol "Bersihkan semua"
      if (clearAllBtn) {
        clearAllBtn.addEventListener('click', (e) => {
          e.preventDefault();
          const q = form.querySelector('input[name="q"]');
          if (q) q.value = '';
          produkSelected = '';
          golSelected = '';
          produkInput.value = '';
          golInput.value = '';
          if (typeof closeAll === 'function') closeAll();
          form.submit();
        });
      }

      // Page size choose
      pageSizeMenu.querySelectorAll('button[data-size]').forEach(item => {
        item.addEventListener('click', () => {
          document.getElementById('perPageInput').value = item.getAttribute('data-size');
          // update aria-checked juga
          pageSizeMenu.querySelectorAll('button[role="menuitemradio"]').forEach(b => {
            b.setAttribute('aria-checked', b === item ? 'true' : 'false');
          });
          closeAll();
          form.submit();
        });
      });

      // Click outside to close menus
      document.addEventListener('click', (e) => {
        const safe = e.target.closest(
          '#filterMenu, #pageSizeMenu, #produkMenu, #golMenu, #filterBtn, #pageSizeBtn, #produkBtn, #golBtn'
        );
        if (!safe) closeAll();
      });
      // ESC (tutup menu)
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAll();
      });

      /* ====== DETAIL MODAL & CONFIRM MODAL (POIN 2 & 3) ====== */
      const pageRoot = document.getElementById('pageRoot');

      // Detail
      const detailModal = document.getElementById('detailModal');
      const detailCard = document.getElementById('detailCard');
      const dmActionBtns = document.getElementById('dm_action_buttons');
      const dmForm = document.getElementById('dm_form');
      const dmStatusInput = document.getElementById('dm_status_input');
      const dmTanggalInput = document.getElementById('dm_tanggal_input');
      const lastFocusDetail = {
        el: null
      };

      const dm = {
        status: document.getElementById('dm_status'),
        tglPesan: document.getElementById('dm_tgl_pesan'),
        tglMinta: document.getElementById('dm_tgl_minta'),
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
        suamiIstri: document.getElementById('dm_suami_istri'),
        diagnosa: document.getElementById('dm_diagnosa'),
        pernahSerologi: document.getElementById('dm_pernah_serologi'),
        lokasiSerologi: document.getElementById('dm_lokasi_serologi'),
        tglSerologi: document.getElementById('dm_tgl_serologi'),
        tglTransfusi: document.getElementById('dm_tgl_transfusi'),
        hasilSerologi: document.getElementById('dm_hasil_serologi'),
        sectionKehamilan: document.getElementById('dm_section_kehamilan'),
        jumlahKehamilan: document.getElementById('dm_jumlah_kehamilan'),
        abortus: document.getElementById('dm_abortus'),
        riwayatHemolitik: document.getElementById('dm_riwayat_hemolitik'),
      };

      const fmt = v => v ? v : '-';
      const yaTidak = v => (v === true ? 'Ya' : (v === false ? 'Tidak' : (['ya', 'tidak'].includes(String(v || '')
        .toLowerCase()) ? (String(v).toLowerCase() === 'ya' ? 'Ya' : 'Tidak') : '-')));
      const labelJK = v => !v ? '-' : (v === 'L' ? 'Laki-laki' : (v === 'P' ? 'Perempuan' : v));
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
      const jumlahLabel = v => (Number(v || 0) > 0 ? `${Number(v)} kantong` : '-');

      function colorStatusChip(s) {
        const base = 'inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs font-medium ';
        let tone = s === 'approved' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' :
          s === 'rejected' ? 'border-rose-200 bg-rose-50 text-rose-700' :
          s === 'pending' ? 'border-amber-200 bg-amber-50 text-amber-700' :
          'border-neutral-200 bg-neutral-50 text-neutral-700';
        dm.status.className = base + tone;
      }

      function openDetail(payload, actionUrl) {
        try {
          const statusCap = (payload.status ?? '-').toString().replace(/^./, c => c.toUpperCase());
          dm.status.textContent = statusCap;
          const clone = document.getElementById('dm_status_clone');
          if (clone) clone.textContent = statusCap;

          dm.tglPesan.textContent = fmt(payload.tanggal_pemesanan ?? payload.tanggal);
          dm.tglMinta.textContent = fmt(payload.tanggal_permintaan ?? payload.tanggal);
          dm.nama.textContent = payload.nama_pasien ?? '-';
          dm.rs.textContent = payload.rs_pemesan ?? '-';
          dm.jk.textContent = labelJK(payload.jenis_kelamin);
          dm.dokter.textContent = payload.nama_dokter ?? '-';
          dm.noRegis.textContent = payload.no_regis_rs ?? '-';
          dm.email.textContent = payload.email ?? '-';
          dm.telp.textContent = payload.nomor_telepon ?? '-';
          dm.gol.textContent = payload.gol_darah ?? '-';
          dm.rhesus.textContent = payload.rhesus ?? '-';
          dm.produk.textContent = productLabel(payload.produk);
          dm.jumlah.textContent = jumlahLabel(payload.jumlah_kantong);
          dm.alasan.textContent = payload.alasan_transfusi ?? '-';
          dm.gejala.textContent = (payload.alasan_tambahan ?? '').toString().trim() || '—';
          dm.suamiIstri.textContent = payload.nama_suami_istri ?? '-';
          dm.diagnosa.textContent = payload.diagnosa_klinik ?? '-';
          dm.pernahSerologi.textContent = yaTidak(payload.pernah_serologi);
          dm.lokasiSerologi.textContent = payload.lokasi_serologi ?? '-';
          dm.tglSerologi.textContent = fmt(payload.tanggal_serologi);
          dm.tglTransfusi.textContent = fmt(payload.tanggal_transfusi);
          dm.hasilSerologi.textContent = payload.hasil_serologi ?? '-';

          // Toggle & populate data kehamilan (hanya untuk pasien perempuan)
          if (payload.jenis_kelamin === 'P') {
            dm.sectionKehamilan.classList.remove('hidden');
            dm.jumlahKehamilan.textContent = payload.jumlah_kehamilan != null ? payload.jumlah_kehamilan + ' kali' :
              '-';
            dm.abortus.textContent = yaTidak(payload.abortus);
            dm.riwayatHemolitik.textContent = yaTidak(payload.riwayat_hemolitik);
          } else {
            dm.sectionKehamilan.classList.add('hidden');
          }

          dmForm.action = actionUrl || '#';
          dmTanggalInput.value = (payload.tanggal_permintaan ?? payload.tanggal ?? new Date().toISOString().slice(0,
            10));

          const currentStatus = (payload.status || '').toLowerCase();
          if (currentStatus === 'approved' || currentStatus === 'rejected') dmActionBtns.classList.add('hidden');
          else dmActionBtns.classList.remove('hidden');
          colorStatusChip(currentStatus);

          // OPEN with focus management + tab trap
          openModal(detailModal, detailCard, detailCard, lastFocusDetail);
          // animasi
          requestAnimationFrame(() => {
            detailModal.classList.add('modal-show');
            detailCard.classList.add('card-show');
            pageRoot.setAttribute('aria-hidden', 'true'); // bantu SR fokus ke dialog
          });
        } catch (e) {
          console.error('Gagal membuka modal detail:', e);
          alert('Terjadi kesalahan saat membuka detail.');
        }
      }

      function closeDetail() {
        detailCard.classList.remove('card-show');
        detailModal.classList.remove('modal-show');
        setTimeout(() => {
          closeModal(detailModal, detailCard, lastFocusDetail);
          pageRoot.removeAttribute('aria-hidden');
        }, 200);
      }

      detailModal.querySelectorAll('.dm-close').forEach(b => b.addEventListener('click', closeDetail));
      detailModal.addEventListener('click', (e) => {
        if (e.target === detailModal) closeDetail();
      });
      // ESC untuk modal detail
      detailCard.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
          e.preventDefault();
          closeDetail();
        }
      });

      document.addEventListener('click', (e) => {
        const b = e.target.closest('.lihat-detail-btn');
        if (!b) return;
        try {
          openDetail(JSON.parse(b.dataset.payload || '{}'), b.dataset.action || '#');
        } catch (err) {
          console.error('Payload tidak valid:', err);
          alert('Gagal membuka detail. Data tidak valid.');
        }
      });

      // ===== Confirm modal =====
      const cmTitle = document.getElementById('confirmModalTitle');
      const cmDesc = document.getElementById('cm_desc');
      const cmOk = document.getElementById('cm_ok');
      const cmCancel = document.getElementById('cm_cancel');
      const confirmModal = document.getElementById('confirmModal');
      const confirmCard = document.getElementById('confirmCard');
      const lastFocusConfirm = {
        el: null
      };
      let cmNext = null;

      function setConfirmAppearance(variant) {
        if (variant === 'approve') {
          cmOk.className =
            'min-h-10 rounded-lg px-3 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-300';
          cmOk.textContent = 'Setuju';
        } else if (variant === 'reject') {
          cmOk.className =
            'min-h-10 rounded-lg px-3 py-2 text-sm font-medium text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-300';
          cmOk.textContent = 'Tolak';
        } else {
          cmOk.className =
            'min-h-10 rounded-lg px-3 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-300';
          cmOk.textContent = 'Lanjutkan';
        }
      }

      function openConfirm(title, desc, onOk, variant = 'neutral') {
        cmTitle.textContent = title || 'Konfirmasi';
        cmDesc.innerHTML = desc || 'Apakah Anda yakin?';
        cmNext = onOk || null;
        setConfirmAppearance(variant);
        // buka + fokus ke tombol OK (firstFocusEl)
        openModal(confirmModal, confirmCard, cmOk, lastFocusConfirm);
      }

      function closeConfirm() {
        closeModal(confirmModal, confirmCard, lastFocusConfirm);
      }

      cmCancel.addEventListener('click', closeConfirm);
      confirmModal.addEventListener('click', (e) => {
        if (e.target === confirmModal) closeConfirm();
      });
      // ESC untuk confirm
      confirmCard.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
          e.preventDefault();
          closeConfirm();
        }
      });

      cmOk.addEventListener('click', () => {
        if (typeof cmNext === 'function') cmNext();
        closeConfirm();
      });

      document.getElementById('dm_approve').addEventListener('click', () => openConfirm(
        'Setujui Pemesanan',
        'Anda akan <strong>MENYETUJUI</strong> pemesanan ini. Lanjutkan?',
        () => {
          dmStatusInput.value = 'approved';
          dmForm.submit();
        },
        'approve'
      ));
      document.getElementById('dm_reject').addEventListener('click', () => openConfirm(
        'Tolak Pemesanan',
        'Anda akan <strong>MENOLAK</strong> pemesanan ini. Lanjutkan?',
        () => {
          dmStatusInput.value = 'rejected';
          dmForm.submit();
        },
        'reject'
      ));

      // Initialize Table Sorter
      if (typeof TableSorter !== 'undefined') {
        new TableSorter('#verifikasiTable');
      }
    });
  </script>

  <style>
    th.sortable:hover {
      background-color: rgba(0, 0, 0, .02);
    }

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

    #confirmModal {
      opacity: 1;
    }

    #confirmModal.hidden {
      opacity: 0;
    }

    .check-icon {
      transition: opacity .12s ease;
    }
  </style>
@endsection
