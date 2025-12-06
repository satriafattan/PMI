@extends('layouts.admin')
@section('title', 'Laporan Pemesanan Darah')

@section('content')
  <div class="space-y-6">

    {{-- ===================== Header ===================== --}}
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold md:text-3xl">Laporan Pemesanan Darah</h1>
    </div>

    {{-- ===================== Toolbar ===================== --}}
    <form id="laporanForm"
          method="GET"
          class="flex flex-col gap-3 md:flex-row md:items-stretch">
      {{-- Search --}}
      <div class="relative min-w-0 flex-1">
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
        <input name="q"
               value="{{ $filters['q'] ?? '' }}"
               class="focus:ring-navy-200 w-full rounded-2xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2"
               placeholder="Cari nama pasien atau rumah sakit..." />
      </div>

      {{-- Filter dropdown (Tanggal, Status, Golongan, Produk) --}}
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
               stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="1.6"
                  d="M3 6h18M6 12h12M10 18h4" />
          </svg>
          <span>Filter</span>
        </button>

        <div id="filterMenu"
             class="absolute right-0 z-20 mt-2 hidden w-[22rem] rounded-2xl border border-neutral-200 bg-white p-3 shadow-xl">
          <div class="space-y-3 text-sm">
            <div class="grid grid-cols-2 gap-3">
              {{-- Tanggal Mulai / Selesai --}}
              <div>
                <label class="text-xs font-medium text-neutral-500">Tanggal Mulai</label>
                <input type="date"
                       name="start"
                       value="{{ $filters['start'] ?? '' }}"
                       class="focus:ring-navy-200 mt-1 w-full rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2" />
              </div>
              <div>
                <label class="text-xs font-medium text-neutral-500">Tanggal Selesai</label>
                <input type="date"
                       name="end"
                       value="{{ $filters['end'] ?? '' }}"
                       class="focus:ring-navy-200 mt-1 w-full rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2" />
              </div>
            </div>

            {{-- PHP options --}}
            @php
              $statusOpts = [
                  '' => 'Semua Status',
                  'pending' => 'Pending',
                  'approved' => 'Approved',
                  'rejected' => 'Rejected',
              ];
              $statusVal = $filters['status'] ?? '';
              $statusLabel = $statusOpts[$statusVal] ?? 'Semua Status';

              $gOpts = [
                  '' => 'Semua',
                  'A' => 'A',
                  'B' => 'B',
                  'AB' => 'AB',
                  'O' => 'O',
              ];
              $golVal = $filters['gol'] ?? '';
              $golLabel = $gOpts[$golVal] ?? 'Semua';

              $produkOpts = [
                  '' => 'Semua Produk',
                  'WB' => 'WB',
                  'PRC' => 'PRC',
                  'TC' => 'TC',
                  'FFP' => 'FFP',
                  'CRYO' => 'CRYO',
                  'LP' => 'LP',
                  'TCA' => 'TCA',
                  'CP' => 'CP',
              ];
              $produkVal = $filters['produk'] ?? '';
              $produkLabel = $produkOpts[$produkVal] ?? 'Semua Produk';
            @endphp

            {{-- Hidden inputs untuk submit ke server --}}
            <input type="hidden"
                   name="status"
                   id="statusInput"
                   value="{{ $statusVal }}">
            <input type="hidden"
                   name="gol"
                   id="golInput"
                   value="{{ $golVal }}">
            <input type="hidden"
                   name="produk"
                   id="produkInput"
                   value="{{ $produkVal }}">

            {{-- Status dropdown custom --}}
            <div class="relative">
              <label class="text-xs font-medium text-neutral-500">Status</label>
              <button type="button"
                      id="statusBtn"
                      class="mt-1 flex w-full items-center justify-between rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm transition hover:bg-blue-50/40 focus:outline-none focus:ring-2 focus:ring-blue-200"
                      aria-haspopup="menu"
                      aria-expanded="false"
                      aria-controls="statusMenu">
                <span id="statusLabel"
                      class="truncate">{{ $statusLabel }}</span>
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
              <div id="statusMenu"
                   class="absolute left-0 z-30 mt-1 hidden w-full rounded-2xl border border-neutral-100 bg-white p-1 shadow-2xl ring-1 ring-black/5">
                @foreach ($statusOpts as $val => $label)
                  <button type="button"
                          class="status-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
                          data-value="{{ $val }}">
                    <span class="truncate">{{ $label }}</span>
                    <svg class="check-icon size-4 opacity-0"
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

            {{-- Golongan dropdown custom --}}
            <div class="relative">
              <label class="text-xs font-medium text-neutral-500">Golongan Darah</label>
              <button type="button"
                      id="golBtn"
                      class="focus:ring-navy-200 mt-1 flex w-full items-center justify-between rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm transition hover:bg-emerald-50/40 focus:outline-none focus:ring-2"
                      aria-haspopup="menu"
                      aria-expanded="false"
                      aria-controls="golMenu">
                <span id="golLabel"
                      class="truncate">{{ $golLabel }}</span>
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
                   class="absolute left-0 z-30 mt-1 hidden w-full rounded-2xl border border-neutral-100 bg-white p-1 shadow-2xl ring-1 ring-black/5">
                @foreach ($gOpts as $val => $lab)
                  <button type="button"
                          class="gol-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
                          data-value="{{ $val }}">
                    <span class="truncate">{{ $lab }}</span>
                    <svg class="check-icon size-4 opacity-0"
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

            {{-- Produk dropdown custom --}}
            <div class="relative">
              <label class="text-xs font-medium text-neutral-500">Produk Darah</label>
              <button type="button"
                      id="produkBtn"
                      class="mt-1 flex w-full items-center justify-between rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm transition hover:bg-blue-50/40 focus:outline-none focus:ring-2 focus:ring-blue-200"
                      aria-haspopup="menu"
                      aria-expanded="false"
                      aria-controls="produkMenu">
                <span id="produkLabel"
                      class="truncate">{{ $produkLabel }}</span>
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
                   class="absolute left-0 z-30 mt-1 hidden w-full rounded-2xl border border-neutral-100 bg-white p-1 shadow-2xl ring-1 ring-black/5">
                @foreach ($produkOpts as $val => $label)
                  <button type="button"
                          class="produk-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition"
                          data-value="{{ $val }}">
                    <span class="truncate">{{ $label }}</span>
                    <svg class="check-icon size-4 opacity-0"
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
                      class="text-sm text-neutral-600 hover:underline">
                Reset
              </button>
              <button type="submit"
                      class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm">
                Terapkan
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- Page size (button + dropdown) --}}
      <input type="hidden"
             name="per_page"
             id="perPageInput"
             value="{{ $filters['per_page'] ?? 10 }}">

      <div class="relative md:ml-auto">
        <button type="button"
                id="pageSizeBtn"
                class="focus:ring-navy-200 inline-flex min-h-10 items-center gap-2 rounded-2xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50 focus:outline-none focus:ring-2"
                aria-haspopup="menu"
                aria-expanded="false"
                aria-controls="pageSizeMenu">
          <svg class="size-5 text-neutral-600"
               viewBox="0 0 24 24"
               fill="none"
               stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="1.6"
                  d="M4 8h16M4 16h10" />
          </svg>
          <span>
            Baris:
            <strong id="pageSizeLabel"
                    class="font-semibold text-neutral-800">
              {{ $filters['per_page'] ?? 10 }}
            </strong>
          </span>
          <svg class="size-4 text-neutral-500"
               viewBox="0 0 20 20"
               fill="currentColor">
            <path fill-rule="evenodd"
                  d="M10 12a1 1 0 0 1-.7-.29l-4-4a1 1 0 0 1 1.4-1.42L10 9.59l3.3-3.3a1 1 0 1 1 1.4 1.42l-4 4A1 1 0 0 1 10 12Z"
                  clip-rule="evenodd" />
          </svg>
        </button>

        <div id="pageSizeMenu"
             class="absolute right-0 z-20 mt-2 hidden w-40 rounded-2xl border border-neutral-100 bg-white p-1 shadow-xl">
          @foreach ([10, 20, 50] as $pp)
            <button type="button"
                    role="menuitemradio"
                    class="page-size-item w-full rounded-xl px-3 py-2 text-left text-sm"
                    data-size="{{ $pp }}">
              {{ $pp }} per halaman
            </button>
          @endforeach
        </div>
      </div>

      {{-- Export --}}
      <div class="flex items-center gap-2">
        <a href="{{ route('admin.laporan.exportExcel', request()->all()) }}"
           class="inline-flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700 hover:bg-emerald-100">
          <svg class="size-4"
               viewBox="0 0 24 24"
               fill="none"
               stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="1.6"
                  d="m4 4 6 6m0 0L4 16m6-6h10" />
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
          @foreach ($summary['per_status'] as $k => $v)
            <span
                  class="{{ $k === 'approved'
                      ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                      : ($k === 'rejected'
                          ? 'border-rose-200 bg-rose-50 text-rose-700'
                          : 'border-amber-200 bg-amber-50 text-amber-700') }} inline-flex items-center gap-1 rounded-full border px-2.5 py-1">
              {{ ucfirst($k) }}: <b>{{ $v }}</b>
            </span>
          @endforeach
        </div>
      </div>
    </div>

    {{-- ===================== Tabel ===================== --}}
    <div class="overflow-x-auto rounded-2xl border border-neutral-200 bg-white">
      <table id="laporanTable"
             class="min-w-full text-sm">
        <thead class="bg-neutral-50 text-neutral-600">
          <tr class="text-left">
            <th class="px-4 py-3 font-medium">#</th>
            <x-sortable-th column="tanggal"
                           label="Tanggal" />
            <x-sortable-th column="rs_pemesan"
                           label="RS Pemesan" />
            <x-sortable-th column="pasien"
                           label="Pasien" />
            <x-sortable-th column="produk"
                           label="Produk" />
            <x-sortable-th column="gol"
                           label="Gol" />
            <x-sortable-th column="rhesus"
                           label="Rhesus" />
            <x-sortable-th column="kantong"
                           label="Kantong" />
            <x-sortable-th column="status"
                           label="Status" />
          </tr>
        </thead>
        <tbody>
          @forelse($items as $i => $row)
            <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
              <td class="px-4 py-3">{{ $items->firstItem() + $i }}</td>
              <td class="px-4 py-3"
                  data-tanggal="{{ optional($row->created_at)->format('Y-m-d H:i') }}">
                {{ optional($row->created_at)->format('Y-m-d H:i') }}</td>
              <td class="px-4 py-3"
                  data-rs_pemesan="{{ $row->rs_pemesan }}">{{ $row->rs_pemesan }}</td>
              <td class="px-4 py-3"
                  data-pasien="{{ $row->nama_pasien }}">{{ $row->nama_pasien }}</td>
              <td class="px-4 py-3"
                  data-produk="{{ $row->produk }}">{{ $row->produk }}</td>
              <td class="px-4 py-3"
                  data-gol="{{ $row->gol_darah }}">{{ $row->gol_darah }}</td>
              <td class="px-4 py-3"
                  data-rhesus="{{ $row->rhesus }}">{{ $row->rhesus }}</td>
              <td class="px-4 py-3"
                  data-kantong="{{ (int) $row->jumlah_kantong }}">{{ (int) $row->jumlah_kantong }}</td>
              <td class="px-4 py-3"
                  data-status="{{ $row->status }}">
                <span
                      class="{{ $row->status === 'approved'
                          ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                          : ($row->status === 'pending'
                              ? 'bg-amber-50 text-amber-700 border border-amber-200'
                              : 'bg-rose-50 text-rose-700 border border-rose-200') }} inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium">
                  {{ ucfirst($row->status) }}
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9"
                  class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- ===================== Pagination ===================== --}}
    @if ($items->hasPages())
      <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div class="text-sm text-neutral-600">
          @if ($items->total() > 0)
            Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }} data
          @else
            Tidak ada data
          @endif
        </div>

        <div class="flex items-center gap-2">
          {{-- Previous --}}
          @if ($items->onFirstPage())
            <button disabled
                    class="h-9 min-w-9 cursor-not-allowed rounded-lg border border-neutral-200 bg-white px-3 text-sm text-neutral-700 opacity-50">
              «
            </button>
          @else
            <a href="{{ $items->previousPageUrl() }}"
               class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-neutral-200 bg-white px-3 text-sm text-neutral-700 hover:bg-neutral-50">
              «
            </a>
          @endif

          {{-- Page Numbers --}}
          @php
            $current = $items->currentPage();
            $last = $items->lastPage();
            $range = [];

            if ($last <= 7) {
                $range = range(1, $last);
            } else {
                if ($current <= 3) {
                    $range = array_merge(range(1, 4), ['…'], [$last]);
                } elseif ($current >= $last - 2) {
                    $range = array_merge([1], ['…'], range($last - 3, $last));
                } else {
                    $range = array_merge([1], ['…'], range($current - 1, $current + 1), ['…'], [$last]);
                }
            }
          @endphp

          @foreach ($range as $page)
            @if ($page === '…')
              <span class="px-2 text-neutral-400">…</span>
            @elseif ($page == $current)
              <button class="h-9 min-w-9 rounded-lg border border-neutral-900 bg-neutral-900 px-3 text-sm text-white">
                {{ $page }}
              </button>
            @else
              <a href="{{ $items->url($page) }}"
                 class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-neutral-200 bg-white px-3 text-sm text-neutral-700 hover:bg-neutral-50">
                {{ $page }}
              </a>
            @endif
          @endforeach

          {{-- Next --}}
          @if ($items->hasMorePages())
            <a href="{{ $items->nextPageUrl() }}"
               class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-neutral-200 bg-white px-3 text-sm text-neutral-700 hover:bg-neutral-50">
              »
            </a>
          @else
            <button disabled
                    class="h-9 min-w-9 cursor-not-allowed rounded-lg border border-neutral-200 bg-white px-3 text-sm text-neutral-700 opacity-50">
              »
            </button>
          @endif
        </div>
      </div>
    @endif
  </div>

  {{-- ===== JS kecil untuk dropdown filter & page size ===== --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('laporanForm');

      // ===== Elemen utama filter =====
      const filterBtn = document.getElementById('filterBtn');
      const filterMenu = document.getElementById('filterMenu');
      const resetBtn = document.getElementById('resetBtn');

      // input tanggal di dalam filter
      const startDateInput = filterMenu?.querySelector('input[name="start"]');
      const endDateInput = filterMenu?.querySelector('input[name="end"]');

      // ===== Status dropdown =====
      const statusInput = document.getElementById('statusInput');
      const statusBtn = document.getElementById('statusBtn');
      const statusMenu = document.getElementById('statusMenu');
      const statusLabel = document.getElementById('statusLabel');

      // ===== Golongan dropdown =====
      const golInput = document.getElementById('golInput');
      const golBtn = document.getElementById('golBtn');
      const golMenu = document.getElementById('golMenu');
      const golLabel = document.getElementById('golLabel');

      // ===== Produk dropdown =====
      const produkInput = document.getElementById('produkInput');
      const produkBtn = document.getElementById('produkBtn');
      const produkMenu = document.getElementById('produkMenu');
      const produkLabel = document.getElementById('produkLabel');

      // ===== Page size dropdown =====
      const pageSizeBtn = document.getElementById('pageSizeBtn');
      const pageSizeMenu = document.getElementById('pageSizeMenu');
      const pageSizeLabel = document.getElementById('pageSizeLabel');
      const perPageInput = document.getElementById('perPageInput');

      // ===== Helper: tutup semua dropdown di dalam menu filter =====
      function closeAllFilterDropdowns(exceptId = null) {
        ['statusMenu', 'golMenu', 'produkMenu'].forEach(id => {
          if (id !== exceptId) {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
          }
        });
      }

      // ===== Helper: mark active option =====
      function markActive(menuEl, itemClass, value) {
        if (!menuEl) return;
        menuEl.querySelectorAll('.' + itemClass).forEach(el => {
          const active = el.getAttribute('data-value') === (value ?? '');
          el.setAttribute('aria-checked', active ? 'true' : 'false');
          el.classList.toggle('bg-blue-50', active);
          el.classList.toggle('text-blue-800', active);
          el.classList.toggle('ring-1', active);
          el.classList.toggle('ring-blue-200', active);
          el.classList.toggle('cursor-default', active);
          const icon = el.querySelector('.check-icon');
          if (icon) {
            icon.classList.toggle('opacity-100', active);
            icon.classList.toggle('opacity-0', !active);
          }
        });
      }

      // ===== Helper: page size active =====
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

      // ===== Filter utama (ikon filter) =====
      filterBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        closeAllFilterDropdowns();
        pageSizeMenu?.classList.add('hidden');
        filterMenu?.classList.toggle('hidden');
      });

      // ===== Saat klik / focus tanggal: tutup semua dropdown lain =====
      [startDateInput, endDateInput].forEach(el => {
        el?.addEventListener('focus', () => {
          closeAllFilterDropdowns();
          pageSizeMenu?.classList.add('hidden');
        });
        el?.addEventListener('click', () => {
          closeAllFilterDropdowns();
          pageSizeMenu?.classList.add('hidden');
        });
      });

      // ===== Status dropdown =====
      statusBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        closeAllFilterDropdowns('statusMenu');
        markActive(statusMenu, 'status-item', statusInput?.value || '');
        statusMenu?.classList.toggle('hidden');
      });

      statusMenu?.querySelectorAll('.status-item').forEach(item => {
        item.addEventListener('click', () => {
          const val = item.getAttribute('data-value') ?? '';
          const label = item.querySelector('span')?.textContent?.trim() || 'Semua Status';
          if (statusInput) statusInput.value = val;
          if (statusLabel) statusLabel.textContent = label;
          markActive(statusMenu, 'status-item', val);
          statusMenu?.classList.add('hidden');
        });
      });

      // ===== Golongan dropdown =====
      golBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        closeAllFilterDropdowns('golMenu');
        markActive(golMenu, 'gol-item', golInput?.value || '');
        golMenu?.classList.toggle('hidden');
      });

      golMenu?.querySelectorAll('.gol-item').forEach(item => {
        item.addEventListener('click', () => {
          const val = item.getAttribute('data-value') ?? '';
          const label = item.querySelector('span')?.textContent?.trim() || 'Semua';
          if (golInput) golInput.value = val;
          if (golLabel) golLabel.textContent = label;
          markActive(golMenu, 'gol-item', val);
          golMenu?.classList.add('hidden');
        });
      });

      // ===== Produk dropdown =====
      produkBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        closeAllFilterDropdowns('produkMenu');
        markActive(produkMenu, 'produk-item', produkInput?.value || '');
        produkMenu?.classList.toggle('hidden');
      });

      produkMenu?.querySelectorAll('.produk-item').forEach(item => {
        item.addEventListener('click', () => {
          const val = item.getAttribute('data-value') ?? '';
          const label = item.querySelector('span')?.textContent?.trim() || 'Semua Produk';
          if (produkInput) produkInput.value = val;
          if (produkLabel) produkLabel.textContent = label;
          markActive(produkMenu, 'produk-item', val);
          produkMenu?.classList.add('hidden');
        });
      });

      // ===== Reset: langsung reset + submit =====
      resetBtn?.addEventListener('click', () => {
        // reset tanggal
        filterMenu?.querySelectorAll('input[type="date"]').forEach(i => i.value = '');

        // reset status, gol, produk
        if (statusInput) statusInput.value = '';
        if (statusLabel) statusLabel.textContent = 'Semua Status';
        markActive(statusMenu, 'status-item', '');

        if (golInput) golInput.value = '';
        if (golLabel) golLabel.textContent = 'Semua';
        markActive(golMenu, 'gol-item', '');

        if (produkInput) produkInput.value = '';
        if (produkLabel) produkLabel.textContent = 'Semua Produk';
        markActive(produkMenu, 'produk-item', '');

        closeAllFilterDropdowns();
        filterMenu?.classList.add('hidden');

        form?.submit();
      });

      // ===== Page size dropdown =====
      if (pageSizeLabel && perPageInput) {
        pageSizeLabel.textContent = perPageInput.value || '10';
      }

      pageSizeBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        filterMenu?.classList.add('hidden');
        closeAllFilterDropdowns();
        updatePageSizeActive(Number(perPageInput?.value || 10));
        pageSizeMenu?.classList.toggle('hidden');
      });

      pageSizeMenu?.querySelectorAll('.page-size-item').forEach(btn => {
        btn.addEventListener('click', () => {
          const size = Number(btn.getAttribute('data-size')) || 10;
          if (perPageInput) perPageInput.value = size;
          if (pageSizeLabel) pageSizeLabel.textContent = size;
          updatePageSizeActive(size);
          pageSizeMenu.classList.add('hidden');
          form?.submit();
        });
      });

      // ===== Klik di luar: tutup semua =====
      document.addEventListener('click', (e) => {
        // panel filter
        if (filterMenu && filterBtn &&
          !filterMenu.contains(e.target) &&
          !filterBtn.contains(e.target)) {
          filterMenu.classList.add('hidden');
          closeAllFilterDropdowns();
        }

        // page size
        if (pageSizeMenu && pageSizeBtn &&
          !pageSizeMenu.contains(e.target) &&
          !pageSizeBtn.contains(e.target)) {
          pageSizeMenu.classList.add('hidden');
        }
      });
    });

    // Initialize Table Sorter
    if (typeof TableSorter !== 'undefined') {
      new TableSorter('#laporanTable');
    }
  </script>
@endsection
