{{-- resources/views/admin/verifikasi/index.blade.php --}}
@extends('layouts.admin')

@section('content')
  <div class="space-y-6">
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
              $isRed = in_array($g, ['A+', 'A-', 'AB+', 'AB-']);
              $cls = $isRed ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-sky-50 text-sky-700 border-sky-100';
              return '<span class="' .
                  $cls .
                  ' inline-flex h-6 items-center justify-center rounded-full border px-2 text-xs font-semibold">' .
                  e($g) .
                  '</span>';
          }
      }
      if (!function_exists('rhesus_pill')) {
          function rhesus_pill($r)
          {
              $isPositive = trim((string) $r) === 'Rh+';
              $cls = $isPositive
                  ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                  : 'bg-sky-50 text-sky-700 border-sky-100';
              return '<span class="' .
                  $cls .
                  ' inline-flex h-6 items-center justify-center rounded-full border px-2 text-xs font-semibold">' .
                  e($r) .
                  '</span>';
          }
      }
      if (!function_exists('product_pill')) {
          function product_pill($p)
          {
              return '<span class="inline-flex items-center rounded-full border border-sky-200 bg-sky-50 px-2.5 py-0.5 text-xs text-sky-700">' .
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
                  aria-expanded="false">
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
               class="absolute right-0 z-20 mt-2 hidden w-[22rem] rounded-2xl border border-neutral-200 bg-white p-3 shadow-xl">
            <div class="space-y-3">
              {{-- Produk (dropdown lembut kustom) --}}
              <div class="relative">
                <label class="text-xs font-medium text-neutral-500">Produk Darah</label>

                {{-- Trigger --}}
                <button type="button"
                        id="produkBtn"
                        class="mt-1 flex w-full items-center justify-between rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm transition hover:bg-blue-50/40 focus:outline-none focus:ring-2 focus:ring-blue-200">
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

                {{-- Menu items --}}
                <div id="produkMenu"
                     class="absolute left-0 z-30 mt-1 hidden w-full rounded-2xl border border-neutral-100 bg-white p-1 shadow-2xl ring-1 ring-black/5">
                  @foreach ($produkOpts as $val => $label)
                    <button type="button"
                            role="menuitemradio"
                            aria-checked="{{ $produkQ === $val ? 'true' : 'false' }}"
                            class="produk-item {{ $produkQ === $val ? 'bg-blue-50 text-blue-800 ring-1 ring-blue-200 cursor-default' : 'text-neutral-800 hover:bg-blue-50/60' }} group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
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

                {{-- Trigger --}}
                <button type="button"
                        id="golBtn"
                        class="focus:ring-navy-200 mt-1 flex w-full items-center justify-between rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm transition hover:bg-emerald-50/40 focus:outline-none focus:ring-2">
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

                {{-- Menu items --}}
                <div id="golMenu"
                     class="absolute left-0 z-30 mt-1 hidden w-full rounded-2xl border border-neutral-100 bg-white p-1 shadow-2xl ring-1 ring-black/5">
                  @php $gOpts = [''=>'Semua','A'=>'A','B'=>'B','AB'=>'AB','O'=>'O']; @endphp
                  @foreach ($gOpts as $val => $lab)
                    <button type="button"
                            role="menuitemradio"
                            aria-checked="{{ $golQ === $val ? 'true' : 'false' }}"
                            class="gol-item {{ $golQ === $val ? 'bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200 cursor-default' : 'text-neutral-800 hover:bg-emerald-50/60' }} group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
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
                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm">
                  Terapkan
                </button>
              </div>
            </div>
          </div>
        </div>

        {{-- Page size (diluar panel filter) --}}
        @php $sizes = [5,10,20]; @endphp
        <div class="relative sm:ml-auto">
          <button type="button"
                  id="pageSizeBtn"
                  class="focus:ring-navy-200 inline-flex min-h-10 items-center gap-2 rounded-2xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50 focus:outline-none focus:ring-2"
                  aria-haspopup="menu"
                  aria-expanded="false">
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
               class="absolute right-0 z-20 mt-2 hidden w-40 rounded-2xl border border-neutral-100 bg-white p-1 shadow-xl">
            @foreach ($sizes as $sz)
              <button type="button"
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
                  class="inline-flex items-center gap-1 rounded-full border border-neutral-200 bg-neutral-50 px-2.5 py-1 text-xs">
              Cari: <strong class="font-medium">{{ $q }}</strong>
            </span>
          @endif
          @if ($produkQ !== '')
            <span
                  class="inline-flex items-center gap-1 rounded-full border border-sky-200 bg-sky-50 px-2.5 py-1 text-xs text-sky-800">
              Produk: <strong class="font-medium">{{ $produkOpts[$produkQ] ?? $produkQ }}</strong>
              <button type="button"
                      class="remove-chip ml-1"
                      data-target="produk">×</button>
            </span>
          @endif
          @if ($golQ)
            <span
                  class="border-navy-200 bg-navy-50 text-navy-800 inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs">
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
        <table class="min-w-full text-sm">
          <thead class="bg-neutral-50 text-neutral-600">
            <tr class="text-left">
              <th class="px-4 py-3 font-medium">Nama Pasien</th>
              <th class="px-4 py-3 font-medium">Rumah Sakit Pemesan</th>
              <th class="px-4 py-3 font-medium">Golongan Darah</th>
              <th class="px-4 py-3 font-medium">Rhesus</th>
              <th class="px-4 py-3 font-medium">Tanggal Pemesanan</th>
              <th class="px-4 py-3 font-medium">Produk Darah</th>
              <th class="px-4 py-3 font-medium">Status</th>
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
                    'tanggal' => $tglBaris,
                ];
              @endphp
              <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
                <td class="px-4 py-3">{{ $o->nama_pasien }}</td>
                <td class="px-4 py-3">{{ $o->rs_pemesan }}</td>
                <td class="px-4 py-3">{!! $o->gol_darah ? blood_pill($o->gol_darah) : '-' !!}</td>
                <td class="px-4 py-3">{!! $o->rhesus ? rhesus_pill($o->rhesus) : '-' !!}</td>
                <td class="px-4 py-3">{{ \Illuminate\Support\Carbon::parse($tglBaris)->format('d-m-Y') }}</td>
                <td class="px-4 py-3">{!! $o->produk ? product_pill($o->produk) : '-' !!}</td>
                <td class="px-4 py-3">
                  <span
                        class="{{ $statusClass }} inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium">
                    {{ ucfirst($o->status) }}
                  </span>
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
          Menampilkan {{ $pemesanan->firstItem() }}–{{ $pemesanan->lastItem() }} dari {{ $pemesanan->total() }} data
        @else
          Tidak ada data
        @endif
      </div>
      <div>{{ $pemesanan->withQueryString()->links() }}</div>
    </div>
  </div>

  {{-- =================== Modal: Detail Pemesanan =================== --}}
  <div id="detailModal"
       class="fixed inset-0 z-40 hidden items-center justify-center bg-black/20 p-4">
    <div class="relative w-full max-w-3xl rounded-3xl border border-neutral-200 bg-white shadow-xl">
      {{-- Header --}}
      <div class="flex items-center justify-between border-b border-neutral-100 px-6 py-4">
        <div class="flex items-center gap-3">
          <h3 class="text-xl font-semibold text-neutral-900">Detail Pemesanan</h3>
          <span class="text-sm text-neutral-500">Ringkasan identitas & kebutuhan darah</span>
        </div>
        <div class="flex items-center gap-2">
          <span id="dm_status_badge"
                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">-</span>
          <button type="button"
                  class="dm-close rounded-lg p-1.5 text-neutral-400 hover:bg-neutral-100 hover:text-neutral-600">
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
      <div class="px-6 py-5">
        <div class="max-h-[65vh] space-y-6 overflow-auto pr-2">
          {{-- A. Pasien & RS --}}
          <section>
            <div class="mb-3 flex items-center gap-2">
              <svg class="size-4 text-neutral-600"
                   viewBox="0 0 24 24"
                   fill="none"
                   stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2m8-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
              </svg>
              <h4 class="text-sm font-semibold text-neutral-700">A. Pasien & RS</h4>
            </div>
            <div class="grid grid-cols-1 gap-x-8 gap-y-3 text-sm md:grid-cols-2">
              <div>
                <dt class="mb-0.5 text-neutral-500">Rumah Sakit</dt>
                <dd id="dm_rs"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Jenis Kelamin</dt>
                <dd id="dm_jk"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">No. Registrasi</dt>
                <dd id="dm_no_regis"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Nama Dokter</dt>
                <dd id="dm_dokter"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Nama Pasien</dt>
                <dd id="dm_nama"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Suami/Istri</dt>
                <dd id="dm_suami_istri"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Telepon</dt>
                <dd id="dm_telp"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Email</dt>
                <dd id="dm_email"
                    class="break-all font-medium text-neutral-900">-</dd>
              </div>
            </div>
          </section>

          {{-- B. Detail Klinis --}}
          <section>
            <div class="mb-3 flex items-center gap-2">
              <svg class="size-4 text-neutral-600"
                   viewBox="0 0 24 24"
                   fill="none"
                   stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z" />
              </svg>
              <h4 class="text-sm font-semibold text-neutral-700">B. Detail Klinis</h4>
            </div>
            <div class="grid grid-cols-1 gap-x-8 gap-y-3 text-sm md:grid-cols-2">
              <div>
                <dt class="mb-0.5 text-neutral-500">Tgl Diperlukan</dt>
                <dd id="dm_tgl_minta"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Pernah Serologi</dt>
                <dd id="dm_pernah_serologi"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Diagnosa</dt>
                <dd id="dm_diagnosa"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Lokasi Serologi</dt>
                <dd id="dm_lokasi_serologi"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Tgl Serologi</dt>
                <dd id="dm_tgl_serologi"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Tgl Transfusi</dt>
                <dd id="dm_tgl_transfusi"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Alasan Transfusi</dt>
                <dd id="dm_alasan"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Hasil Serologi</dt>
                <dd id="dm_hasil_serologi"
                    class="font-medium text-neutral-900">-</dd>
              </div>
            </div>
          </section>

          {{-- C. Permintaan Darah --}}
          <section>
            <div class="mb-3 flex items-center gap-2">
              <svg class="size-4 text-neutral-600"
                   viewBox="0 0 24 24"
                   fill="none"
                   stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z" />
              </svg>
              <h4 class="text-sm font-semibold text-neutral-700">C. Permintaan Darah</h4>
            </div>
            <div class="grid grid-cols-1 gap-x-8 gap-y-3 text-sm md:grid-cols-2">
              <div>
                <dt class="mb-0.5 text-neutral-500">Jenis Darah</dt>
                <dd id="dm_produk"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Golongan Darah</dt>
                <dd id="dm_gol"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Rhesus</dt>
                <dd id="dm_rhesus"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Jumlah Kantong</dt>
                <dd id="dm_jumlah"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Alasan Tambahan</dt>
                <dd id="dm_gejala"
                    class="font-medium text-neutral-900">-</dd>
              </div>
              <div>
                <dt class="mb-0.5 text-neutral-500">Cek Transfusi</dt>
                <dd id="dm_cek"
                    class="font-medium text-neutral-900">-</dd>
              </div>
            </div>
          </section>
        </div>

        {{-- Form POST (hidden) --}}
        <form id="dm_form"
              method="POST"
              action="#"
              class="hidden">
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

      {{-- Footer --}}
      <div class="flex items-center justify-between gap-3 border-t border-neutral-100 px-6 py-4">
        <button type="button"
                class="dm-close rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
          Tutup
        </button>
        <div id="dm_action_buttons"
             class="flex items-center gap-2">
          <button type="button"
                  id="dm_reject"
                  class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-700">
            Tolak
          </button>
          <button type="button"
                  id="dm_approve"
                  class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
            Setuju
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- =================== Modal: Konfirmasi =================== --}}
  <div id="confirmModal"
       class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/40 p-3 backdrop-blur-sm transition-opacity duration-150 sm:p-4">
    <div class="w-full max-w-md overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-2xl">
      <div class="px-4 pt-4 sm:px-5 sm:pt-5">
        <div class="flex items-start gap-3">
          <span id="cm_icon"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-neutral-200 bg-neutral-50 text-neutral-700">
            <svg class="h-5 w-5"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 aria-hidden="true">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M12 9v4m0 4h.01M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20z" />
            </svg>
          </span>
          <div class="min-w-0">
            <h4 id="cm_title"
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
      /* ==== Filter panel ==== */
      const btn = document.getElementById('filterBtn');
      const menu = document.getElementById('filterMenu');
      const apply = document.getElementById('applyBtn');
      const reset = document.getElementById('resetBtn');
      const form = document.getElementById('filterForm');

      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        menu.classList.toggle('hidden');
        btn.setAttribute('aria-expanded', menu.classList.contains('hidden') ? 'false' : 'true');
      });
      document.addEventListener('click', (e) => {
        if (!menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden');
      });

      /* ==== Produk dropdown (lembut) ==== */
      const produkBtn = document.getElementById('produkBtn');
      const produkMenu = document.getElementById('produkMenu');
      const produkLabel = document.getElementById('produkLabel');
      const produkInput = document.getElementById('produkInput');
      let produkSelected = @json($produkQ);

      function closeProdukMenu() {
        produkMenu.classList.add('hidden');
      }

      function updateProdukActive(value) {
        document.querySelectorAll('#produkMenu .produk-item').forEach(el => {
          const active = el.getAttribute('data-value') === value;
          el.setAttribute('aria-checked', active ? 'true' : 'false');
          if (active) {
            el.classList.add('bg-blue-50', 'text-blue-800', 'ring-1', 'ring-blue-200', 'cursor-default');
          } else {
            el.classList.remove('bg-blue-50', 'text-blue-800', 'ring-1', 'ring-blue-200', 'cursor-default');
          }
        });
      }
      produkBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        produkMenu.classList.toggle('hidden');
        updateProdukActive(produkSelected || '');
      });
      produkMenu.querySelectorAll('.produk-item').forEach(item => {
        item.addEventListener('click', () => {
          const val = item.getAttribute('data-value') ?? '';
          const label = item.querySelector('span')?.textContent?.trim() || 'Semua Produk';
          produkSelected = val;
          produkLabel.textContent = label;
          updateProdukActive(val);
          closeProdukMenu();
        });
      });
      document.addEventListener('click', (e) => {
        if (!produkMenu.contains(e.target) && !produkBtn.contains(e.target)) closeProdukMenu();
      });

      /* ==== Golongan dropdown (lembut) ==== */
      const golBtn = document.getElementById('golBtn');
      const golMenu = document.getElementById('golMenu');
      const golLabel = document.getElementById('golLabel');
      const golInput = document.getElementById('golInput');
      let golSelected = @json($golQ);

      function closeGolMenu() {
        golMenu.classList.add('hidden');
      }

      function updateGolActive(value) {
        document.querySelectorAll('#golMenu .gol-item').forEach(el => {
          const active = el.getAttribute('data-value') === (value ?? '');
          el.setAttribute('aria-checked', active ? 'true' : 'false');
          if (active) {
            el.classList.add('bg-blue-50', 'text-blue-800', 'ring-1', 'ring-blue-200', 'cursor-default');
          } else {
            el.classList.remove('bg-blue-50', 'text-blue-800', 'ring-1', 'ring-blue-200', 'cursor-default');
          }
        });
      }
      golBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        golMenu.classList.toggle('hidden');
        updateGolActive(golSelected || '');
      });
      golMenu.querySelectorAll('.gol-item').forEach(item => {
        item.addEventListener('click', () => {
          const val = item.getAttribute('data-value') ?? '';
          const label = item.querySelector('span')?.textContent?.trim() || 'Semua';
          golSelected = val;
          golLabel.textContent = label;
          updateGolActive(val);
          closeGolMenu();
        });
      });
      document.addEventListener('click', (e) => {
        if (!golMenu.contains(e.target) && !golBtn.contains(e.target)) closeGolMenu();
      });

      /* ==== Apply & Reset ==== */
      apply.addEventListener('click', () => {
        produkInput.value = produkSelected || '';
        golInput.value = golSelected || '';
        menu.classList.add('hidden');
        form.submit();
      });
      reset.addEventListener('click', () => {
        produkSelected = '';
        produkInput.value = '';
        golSelected = '';
        golInput.value = '';
        document.getElementById('produkLabel').textContent = 'Semua Produk';
        golLabel.textContent = 'Semua';
        const q = form.querySelector('input[name="q"]');
        if (q) q.value = '';
        form.submit();
      });

      // Chip remove & clear all
      document.querySelectorAll('.remove-chip')?.forEach(chip => {
        chip.addEventListener('click', () => {
          const t = chip.dataset.target;
          if (t === 'produk') document.getElementById('produkInput').value = '';
          if (t === 'gol') document.getElementById('golInput').value = '';
          form.submit();
        });
      });
      const clearAllBtn = document.getElementById('clearAllBtn');
      if (clearAllBtn) {
        clearAllBtn.addEventListener('click', () => {
          document.getElementById('produkInput').value = '';
          document.getElementById('golInput').value = '';
          const q = form.querySelector('input[name="q"]');
          if (q) q.value = '';
          form.submit();
        });
      }

      /* ==== Page size ==== */
      const perInput = document.getElementById('perPageInput');
      const pageSizeBtn = document.getElementById('pageSizeBtn');
      const pageSizeMenu = document.getElementById('pageSizeMenu');
      pageSizeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        pageSizeMenu.classList.toggle('hidden');
        pageSizeBtn.setAttribute('aria-expanded', pageSizeMenu.classList.contains('hidden') ? 'false' : 'true');
      });
      document.addEventListener('click', (e) => {
        if (!pageSizeMenu.contains(e.target) && !pageSizeBtn.contains(e.target)) pageSizeMenu.classList.add(
          'hidden');
      });
      pageSizeMenu.querySelectorAll('button[data-size]').forEach(item => {
        item.addEventListener('click', () => {
          const size = item.getAttribute('data-size');
          if (!size) return;
          perInput.value = size;
          pageSizeMenu.classList.add('hidden');
          form.submit();
        });
      });

      /* ==== Detail modal & confirm modal (tidak diubah) ==== */
      const detailModal = document.getElementById('detailModal');
      const dmActionBtns = document.getElementById('dm_action_buttons');
      const dmForm = document.getElementById('dm_form');
      const dmStatusInput = document.getElementById('dm_status_input');
      const dmTanggalInput = document.getElementById('dm_tanggal_input');

      const dm = {
        statusBadge: document.getElementById('dm_status_badge'),
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
      };

      function fmt(v) {
        return v ? v : '-';
      }

      function yaTidak(v) {
        if (v === true) return 'Ya';
        if (v === false) return 'Tidak';
        const s = String(v || '').toLowerCase();
        return s === 'ya' ? 'Ya' : (s === 'tidak' ? 'Tidak' : '-');
      }

      function labelJK(v) {
        return !v ? '-' : (v === 'L' ? 'Laki-laki' : (v === 'P' ? 'Perempuan' : v));
      }

      function productLabel(c) {
        const m = {
          WB: 'WB: Whole Blood',
          PRC: 'PRC: Packed Red Cell',
          TC: 'TC: Thrombocyte Concentrate',
          FFP: 'FFP: Fresh Frozen Plasma',
          CRYO: 'CRYO: Cryoprecipitated Anti-Hemophilic Factor',
          LP: 'LP: Liquid Plasma',
          TCA: 'TCA: Thrombocyte Apheresis',
          CP: 'CP: Convalescent Plasma'
        };
        return m[c] || c || '-';
      }

      function jumlahLabel(v) {
        const n = Number(v || 0);
        return n > 0 ? `${n} kantong` : '-';
      }

      function badgeClass(s) {
        if (s === 'approved') return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
        if (s === 'pending') return 'bg-amber-50 text-amber-700 border border-amber-200';
        if (s === 'rejected') return 'bg-rose-50 text-rose-700 border border-rose-200';
        return 'bg-neutral-50 text-neutral-700 border border-neutral-200';
      }

      function openDetail(payload, actionUrl) {
        try {
          const status = (payload.status ?? 'pending').toLowerCase();
          const statusCap = status.charAt(0).toUpperCase() + status.slice(1);
          dm.statusBadge.textContent = statusCap;
          dm.statusBadge.className = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ' +
            badgeClass(status);

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

          dmForm.action = actionUrl || '#';
          dmTanggalInput.value = (payload.tanggal_permintaan ?? payload.tanggal ?? new Date().toISOString().slice(0,
            10));

          if (status === 'approved' || status === 'rejected') {
            dmActionBtns.classList.add('hidden');
          } else {
            dmActionBtns.classList.remove('hidden');
          }

          detailModal.classList.remove('hidden');
          detailModal.classList.add('flex');
        } catch (e) {
          console.error('Gagal membuka modal detail:', e);
          alert('Terjadi kesalahan saat membuka detail.');
        }
      }

      function closeDetail() {
        detailModal.classList.add('hidden');
        detailModal.classList.remove('flex');
      }
      detailModal.querySelectorAll('.dm-close').forEach(b => b.addEventListener('click', closeDetail));
      detailModal.addEventListener('click', (e) => {
        if (e.target === detailModal) closeDetail();
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

      /* ==== Confirm modal ==== */
      const cmTitle = document.getElementById('cm_title');
      const cmDesc = document.getElementById('cm_desc');
      const cmCancel = document.getElementById('cm_cancel');
      const cmOk = document.getElementById('cm_ok');
      const cmIcon = document.getElementById('cm_icon');
      const confirmModal = document.getElementById('confirmModal');
      let cmNext = null;

      function setConfirmAppearance(variant) {
        const base = 'inline-flex h-9 w-9 items-center justify-center rounded-lg border ';
        if (variant === 'approve') {
          cmIcon.className = base + 'border-emerald-200 bg-emerald-50 text-emerald-700';
          cmIcon.innerHTML =
            `<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"/></svg>`;
          cmOk.className =
            'min-h-10 rounded-lg px-3 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-300';
          cmOk.textContent = 'Setuju';
        } else if (variant === 'reject') {
          cmIcon.className = base + 'border-rose-200 bg-rose-50 text-rose-700';
          cmIcon.innerHTML =
            `<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"/></svg>`;
          cmOk.className =
            'min-h-10 rounded-lg px-3 py-2 text-sm font-medium text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-300';
          cmOk.textContent = 'Tolak';
        } else {
          cmIcon.className = base + 'border-neutral-200 bg-neutral-50 text-neutral-700';
          cmIcon.innerHTML =
            `<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20z"/></svg>`;
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
        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex');
      }

      function closeConfirm() {
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('flex');
        cmNext = null;
      }
      document.getElementById('cm_cancel').addEventListener('click', closeConfirm);
      confirmModal.addEventListener('click', (e) => {
        if (e.target === confirmModal) closeConfirm();
      });
      document.getElementById('cm_ok').addEventListener('click', () => {
        if (typeof cmNext === 'function') cmNext();
        closeConfirm();
      });

      document.getElementById('dm_approve').addEventListener('click', () => openConfirm('Setujui Pemesanan',
        'Anda akan <strong>MENYETUJUI</strong> pemesanan ini. Lanjutkan?', () => {
          dmStatusInput.value = 'approved';
          dmForm.submit();
        }, 'approve'));
      document.getElementById('dm_reject').addEventListener('click', () => openConfirm('Tolak Pemesanan',
        'Anda akan <strong>MENOLAK</strong> pemesanan ini. Lanjutkan?', () => {
          dmStatusInput.value = 'rejected';
          dmForm.submit();
        }, 'reject'));
    });
  </script>

  <style>
    th.sortable:hover {
      background-color: rgba(0, 0, 0, .02);
    }
  </style>
@endsection
