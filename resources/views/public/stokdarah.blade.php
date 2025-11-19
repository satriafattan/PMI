@extends('layouts.app')
@section('title', 'Stok Darah – SIMPHONY')

@section('content')
  <div class="min-h-screen bg-white text-slate-800">
    <x-navbar />

    @php
      // Data kartu dari controller
      $stok = [
          'A' => $stokA ?? 0,
          'B' => $stokB ?? 0,
          'O' => $stokO ?? 0,
          'AB' => $stokAB ?? 0,
      ];

      // Data tabel (agregat per produk) dari controller
      $komponenRows = $komponenRows ?? [];
      $sumA = collect($komponenRows)->sum('A');
      $sumAB = collect($komponenRows)->sum('AB');
      $sumB = collect($komponenRows)->sum('B');
      $sumO = collect($komponenRows)->sum('O');
      $sumAll = $sumA + $sumAB + $sumB + $sumO;
    @endphp

    {{-- HERO SECTION --}}
    <section class="bg-gradient-to-b from-red-50 to-white py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-4xl font-extrabold text-slate-900 sm:text-5xl">
            Stok Darah Real-time
          </h1>
          <p class="mt-3 text-lg text-slate-600">
            Pantau ketersediaan darah melalui SIMPHONY
          </p>
          <div class="mt-6 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
            <div class="flex items-center gap-2 text-sm text-slate-600">
              <span class="h-2 w-2 animate-pulse rounded-full bg-green-500"></span>
              <span>Update terakhir: <strong id="lastUpdate">{{ now()->format('d M Y, H:i') }} WIB</strong></span>
            </div>
            <button id="refreshStok"
                    class="rounded-lg bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 hover:shadow">
              Refresh Data
            </button>
          </div>
        </div>

        {{-- SEARCH BAR --}}
        <div class="mt-8 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
          <div class="relative w-full max-w-xl">
            <input id="q"
                   type="text"
                   placeholder="Cari komponen (PRC, WB, TC...) atau golongan (A, B, AB, O)"
                   class="w-full rounded-xl border-2 border-slate-300 bg-white px-4 py-3 pr-24 text-sm shadow-sm transition-all duration-200 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500">
            <button id="clearQ"
                    class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium hover:bg-slate-50">
              Clear
            </button>
          </div>
          <div class="flex gap-2">
            <button onclick="filterByGolongan('A')"
                    class="filter-btn rounded-lg bg-white px-3 py-2 text-sm font-medium shadow-sm transition hover:bg-red-50 hover:text-red-600">
              A
            </button>
            <button onclick="filterByGolongan('B')"
                    class="filter-btn rounded-lg bg-white px-3 py-2 text-sm font-medium shadow-sm transition hover:bg-red-50 hover:text-red-600">
              B
            </button>
            <button onclick="filterByGolongan('AB')"
                    class="filter-btn rounded-lg bg-white px-3 py-2 text-sm font-medium shadow-sm transition hover:bg-red-50 hover:text-red-600">
              AB
            </button>
            <button onclick="filterByGolongan('O')"
                    class="filter-btn rounded-lg bg-white px-3 py-2 text-sm font-medium shadow-sm transition hover:bg-red-50 hover:text-red-600">
              O
            </button>
          </div>
        </div>
      </div>
    </section>

    {{-- CARDS: total per golongan (SELALU TAMPIL) --}}
    <section class="py-8">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
          <h2 class="text-2xl font-bold text-slate-900">Stok per Golongan Darah</h2>
          <div id="totalAllStock"
               class="text-right">
            <div class="text-3xl font-bold text-slate-900">
              {{ number_format($stok['A'] + $stok['B'] + $stok['AB'] + $stok['O']) }}</div>
            <div class="text-sm text-slate-500">Total Unit</div>
          </div>
        </div>

        <div id="cardGrid"
             class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          @foreach ($stok as $gol => $jumlah)
            @php
              $status = 'Aman';
              $color = 'emerald';
              $border = 'border-emerald-400/40';
              $bgGradient = 'from-emerald-500/20 to-emerald-400/10';
              if ($jumlah < 50) {
                  $status = 'Menipis';
                  $color = 'amber';
                  $border = 'border-amber-400/40';
                  $bgGradient = 'from-amber-500/20 to-amber-400/10';
              }
              if ($jumlah < 20) {
                  $status = 'Kritis';
                  $color = 'red';
                  $border = 'border-red-400/50';
                  $bgGradient = 'from-red-500/20 to-red-400/10';
              }

              $percentage = $jumlah > 0 ? min(100, ($jumlah / 100) * 100) : 0;
            @endphp
            <div class="card group relative"
                 data-gol="{{ strtoupper($gol) }}">
              <div
                   class="{{ $bgGradient }} absolute -inset-0.5 rounded-3xl bg-gradient-to-br opacity-0 blur transition duration-300 group-hover:opacity-100">
              </div>
              <div
                   class="{{ $border }} relative rounded-3xl border-2 bg-white p-6 shadow-lg transition duration-300 group-hover:shadow-xl">
                <div class="flex items-center justify-between">
                  <div
                       class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-red-600 to-red-500 text-xl font-bold text-white shadow-lg ring-4 ring-white">
                    {{ $gol }}
                  </div>
                  <div
                       class="bg-{{ $color }}-50 inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-medium">
                    <span class="bg-{{ $color }}-500 h-2 w-2 animate-pulse rounded-full"></span>
                    <span class="text-{{ $color }}-700">{{ $status }}</span>
                  </div>
                </div>
                <div class="mt-6 text-center">
                  <div class="text-5xl font-extrabold tabular-nums tracking-tight text-slate-900"
                       data-counter>
                    {{ number_format($jumlah, 0, ',', '.') }}
                  </div>
                  <div class="mt-1 text-sm font-medium text-slate-600">Unit tersedia</div>

                  {{-- Progress Bar --}}
                  <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
                    <div class="from-{{ $color }}-500 to-{{ $color }}-400 h-full rounded-full bg-gradient-to-r transition-all duration-1000"
                         style="width: {{ $percentage }}%"
                         data-progress="{{ $percentage }}">
                    </div>
                  </div>
                  <div class="mt-2 text-xs text-slate-500">
                    Target minimum: 50 unit
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-6 rounded-xl border border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4 shadow-sm">
          <div class="flex items-start gap-3">
            <div
                 class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-600">
              i
            </div>
            <div class="text-sm text-slate-600">
              <strong>Keterangan Status:</strong>
              <span class="text-emerald-600">≥50 Aman</span>,
              <span class="text-amber-600">20–49 Menipis</span>,
              <span class="text-red-600">&lt;20 Kritis</span>.
              Data diperbarui secara real-time dari sistem inventory PMI.
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- TABLE: agregat per produk (SELALU TAMPIL) --}}
    <section class="pb-16 pt-8">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
          <div>
            <h2 class="text-2xl font-bold text-slate-900">Stok per Komponen Darah</h2>
            <p class="mt-1 text-sm text-slate-600">Rincian ketersediaan komponen berdasarkan golongan darah</p>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-sm text-slate-600">Tampilkan:</span>
            <select id="viewMode"
                    class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500">
              <option value="all">Semua Komponen</option>
              <option value="available">Hanya Tersedia</option>
              <option value="low">Stok Rendah</option>
            </select>
          </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-xl">
          <div class="overflow-x-auto">
            <table class="min-w-full border-collapse text-base">
              <thead class="bg-gradient-to-r from-red-50 to-rose-50 text-slate-800">
                <tr class="border-b border-slate-300">
                  <th class="border-r border-slate-300 px-6 py-4 text-left font-bold">
                    <div class="flex items-center gap-2">
                      <span>Komponen</span>
                      <button onclick="sortTable(0)"
                              class="text-slate-400 transition hover:text-slate-600">
                        <svg class="h-4 w-4"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                          <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                      </button>
                    </div>
                  </th>
                  <th class="border-r border-slate-300 px-6 py-4 text-center font-bold">
                    <div class="flex items-center justify-center gap-1">
                      <span
                            class="inline-block h-6 w-6 rounded-lg bg-red-600 text-center text-sm font-bold leading-6 text-white">A</span>
                    </div>
                  </th>
                  <th class="border-r border-slate-300 px-6 py-4 text-center font-bold">
                    <div class="flex items-center justify-center gap-1">
                      <span
                            class="inline-block h-6 w-6 rounded-lg bg-red-600 text-center text-sm font-bold leading-6 text-white">AB</span>
                    </div>
                  </th>
                  <th class="border-r border-slate-300 px-6 py-4 text-center font-bold">
                    <div class="flex items-center justify-center gap-1">
                      <span
                            class="inline-block h-6 w-6 rounded-lg bg-red-600 text-center text-sm font-bold leading-6 text-white">B</span>
                    </div>
                  </th>
                  <th class="border-r border-slate-300 px-6 py-4 text-center font-bold">
                    <div class="flex items-center justify-center gap-1">
                      <span
                            class="inline-block h-6 w-6 rounded-lg bg-red-600 text-center text-sm font-bold leading-6 text-white">O</span>
                    </div>
                  </th>
                  <th class="px-6 py-4 text-center font-bold">Total</th>
                </tr>
              </thead>
              <tbody id="tableBody"
                     class="text-slate-800">
                @forelse ($komponenRows as $row)
                  @php
                    $total = ($row['A'] ?? 0) + ($row['AB'] ?? 0) + ($row['B'] ?? 0) + ($row['O'] ?? 0);
                    $isLow = $total < 20;
                  @endphp
                  <tr class="row-item border-b border-slate-200 transition-colors odd:bg-white even:bg-slate-50 hover:bg-red-50"
                      data-produk="{{ strtoupper($row['produk'] ?? '-') }}"
                      data-total="{{ $total }}"
                      data-is-low="{{ $isLow ? '1' : '0' }}">
                    <td class="border-r border-slate-200 px-6 py-4 font-semibold">
                      <div class="flex items-center gap-3">
                        <span
                              class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-600">
                          {{ substr($row['produk'] ?? '-', 0, 2) }}
                        </span>
                        <div>
                          <div class="font-semibold">{{ $row['produk'] ?? '-' }}</div>
                          @if ($isLow)
                            <span class="text-xs text-red-600">Stok Rendah</span>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td class="border-r border-slate-200 px-6 py-4 text-center tabular-nums">
                      <span
                            class="{{ ($row['A'] ?? 0) < 10 ? 'font-bold text-red-600' : '' }} inline-block min-w-[3rem]">
                        {{ number_format($row['A'] ?? 0) }}
                      </span>
                    </td>
                    <td class="border-r border-slate-200 px-6 py-4 text-center tabular-nums">
                      <span
                            class="{{ ($row['AB'] ?? 0) < 10 ? 'font-bold text-red-600' : '' }} inline-block min-w-[3rem]">
                        {{ number_format($row['AB'] ?? 0) }}
                      </span>
                    </td>
                    <td class="border-r border-slate-200 px-6 py-4 text-center tabular-nums">
                      <span
                            class="{{ ($row['B'] ?? 0) < 10 ? 'font-bold text-red-600' : '' }} inline-block min-w-[3rem]">
                        {{ number_format($row['B'] ?? 0) }}
                      </span>
                    </td>
                    <td class="border-r border-slate-200 px-6 py-4 text-center tabular-nums">
                      <span
                            class="{{ ($row['O'] ?? 0) < 10 ? 'font-bold text-red-600' : '' }} inline-block min-w-[3rem]">
                        {{ number_format($row['O'] ?? 0) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 text-center font-bold tabular-nums">
                      <span
                            class="{{ $isLow ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-900' }} inline-flex items-center justify-center rounded-lg px-3 py-1">
                        {{ number_format($total) }}
                      </span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6"
                        class="px-8 py-12 text-center text-slate-500">
                      <div class="flex flex-col items-center gap-3">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                          <svg class="h-8 w-8 text-slate-400"
                               fill="none"
                               stroke="currentColor"
                               viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                          </svg>
                        </div>
                        <p class="font-medium">Belum ada data stok</p>
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>

              <tfoot class="bg-gradient-to-r from-slate-100 to-slate-50 text-slate-900">
                <tr class="border-t-2 border-slate-300">
                  <td class="border-r border-slate-300 px-6 py-4 font-bold">Total Keseluruhan</td>
                  <td class="border-r border-slate-300 px-6 py-4 text-center font-bold tabular-nums">
                    <span class="inline-block rounded-lg bg-white px-3 py-1">{{ number_format($sumA) }}</span>
                  </td>
                  <td class="border-r border-slate-300 px-6 py-4 text-center font-bold tabular-nums">
                    <span class="inline-block rounded-lg bg-white px-3 py-1">{{ number_format($sumAB) }}</span>
                  </td>
                  <td class="border-r border-slate-300 px-6 py-4 text-center font-bold tabular-nums">
                    <span class="inline-block rounded-lg bg-white px-3 py-1">{{ number_format($sumB) }}</span>
                  </td>
                  <td class="border-r border-slate-300 px-6 py-4 text-center font-bold tabular-nums">
                    <span class="inline-block rounded-lg bg-white px-3 py-1">{{ number_format($sumO) }}</span>
                  </td>
                  <td class="px-6 py-4 text-center font-extrabold tabular-nums">
                    <span
                          class="inline-block rounded-lg bg-red-600 px-4 py-2 text-white shadow-lg">{{ number_format($sumAll) }}</span>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        {{-- Info komponen --}}
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <div class="mb-2 font-semibold text-slate-900">WB (Whole Blood)</div>
            <p class="text-xs text-slate-600">Darah lengkap untuk transfusi darurat</p>
          </div>
          <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <div class="mb-2 font-semibold text-slate-900">PRC (Packed Red Cell)</div>
            <p class="text-xs text-slate-600">Sel darah merah untuk anemia & operasi</p>
          </div>
          <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <div class="mb-2 font-semibold text-slate-900">TC (Thrombocyte Concentrate)</div>
            <p class="text-xs text-slate-600">Trombosit untuk gangguan pembekuan</p>
          </div>
          <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <div class="mb-2 font-semibold text-slate-900">FFP (Fresh Frozen Plasma)</div>
            <p class="text-xs text-slate-600">Plasma beku untuk gangguan koagulasi</p>
          </div>
          <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <div class="mb-2 font-semibold text-slate-900">AHF (Anti Hemophilic Factor)</div>
            <p class="text-xs text-slate-600">Faktor pembekuan untuk hemofilia</p>
          </div>
          <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <div class="mb-2 font-semibold text-slate-900">LP (Liquid Plasma)</div>
            <p class="text-xs text-slate-600">Plasma cair untuk volume expander</p>
          </div>
        </div>
      </div>
    </section>

    <x-footer bg="bg-white" />
  </div>

  {{-- JS: Enhanced filtering, sorting, and animations --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const input = document.getElementById('q');
      const clear = document.getElementById('clearQ');
      const viewMode = document.getElementById('viewMode');
      const refreshBtn = document.getElementById('refreshStok');

      // Elemen kartu & baris tabel
      const cardEls = Array.from(document.querySelectorAll('#cardGrid .card'));
      const tableRows = Array.from(document.querySelectorAll('#tableBody .row-item'));

      function norm(s) {
        return (s || '').toString().trim().toUpperCase();
      }

      // Filter function dengan mode view
      function applyFilter() {
        const q = norm(input?.value);
        const mode = viewMode?.value || 'all';
        const isGolongan = ['A', 'B', 'AB', 'O'].includes(q);

        // Filter kartu
        cardEls.forEach(el => {
          const gol = norm(el.getAttribute('data-gol'));
          const hit = !q || (isGolongan ? gol === q : false);
          el.style.display = hit ? '' : 'none';
        });
        if (!q || !isGolongan) cardEls.forEach(el => el.style.display = '');

        // Filter tabel dengan mode
        tableRows.forEach(tr => {
          const produk = norm(tr.getAttribute('data-produk') || tr.cells?.[0]?.innerText);
          const total = parseInt(tr.getAttribute('data-total') || '0');
          const isLow = tr.getAttribute('data-is-low') === '1';

          let show = true;

          // Filter by search query
          if (q && !produk.includes(q)) show = false;

          // Filter by view mode
          if (mode === 'available' && total === 0) show = false;
          if (mode === 'low' && !isLow) show = false;

          tr.style.display = show ? '' : 'none';
        });

        // Update visible count
        const visibleRows = tableRows.filter(tr => tr.style.display !== 'none').length;
        console.log(`Showing ${visibleRows} of ${tableRows.length} components`);
      }

      // Quick filter by golongan buttons
      window.filterByGolongan = (gol) => {
        if (input) {
          input.value = gol;
          applyFilter();
          // Highlight active button
          document.querySelectorAll('.filter-btn').forEach(btn => {
            if (btn.textContent.trim() === gol) {
              btn.classList.add('bg-red-600', 'text-white');
              btn.classList.remove('bg-white');
            } else {
              btn.classList.remove('bg-red-600', 'text-white');
              btn.classList.add('bg-white');
            }
          });
        }
      };

      // Table sorting
      let sortDirection = 1;
      window.sortTable = (colIndex) => {
        const tbody = document.getElementById('tableBody');
        const rows = Array.from(tableRows);

        rows.sort((a, b) => {
          let aVal, bVal;
          if (colIndex === 0) {
            aVal = a.getAttribute('data-produk') || '';
            bVal = b.getAttribute('data-produk') || '';
          } else {
            aVal = parseInt(a.getAttribute('data-total') || '0');
            bVal = parseInt(b.getAttribute('data-total') || '0');
          }
          return sortDirection * (aVal > bVal ? 1 : -1);
        });

        sortDirection *= -1;
        rows.forEach(row => tbody.appendChild(row));
      };

      // Refresh data
      refreshBtn?.addEventListener('click', () => {
        refreshBtn.disabled = true;
        refreshBtn.innerHTML = '<span class="inline-block animate-spin">⟳</span> Refreshing...';

        setTimeout(() => {
          window.location.reload();
        }, 500);
      });

      // Event listeners
      input?.addEventListener('input', () => {
        applyFilter();
        // Reset filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
          btn.classList.remove('bg-red-600', 'text-white');
          btn.classList.add('bg-white');
        });
      });

      clear?.addEventListener('click', () => {
        if (input) {
          input.value = '';
          applyFilter();
          document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('bg-red-600', 'text-white');
            btn.classList.add('bg-white');
          });
        }
      });

      viewMode?.addEventListener('change', applyFilter);

      // Counter animation untuk kartu
      const counters = document.querySelectorAll('[data-counter]');
      const easeOut = t => 1 - Math.pow(1 - t, 4);
      const io = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) return;
          const el = entry.target;
          const numeric = (el.textContent || '').replace(/[^\d]/g, '');
          const target = parseInt(numeric, 10) || 0;
          let start = null;

          function step(ts) {
            if (!start) start = ts;
            const p = Math.min(1, (ts - start) / 1200);
            const val = Math.floor(easeOut(p) * target);
            el.textContent = val.toLocaleString('id-ID');
            if (p < 1) requestAnimationFrame(step);
          }
          requestAnimationFrame(step);
          io.unobserve(el);
        });
      }, {
        threshold: 0.5
      });
      counters.forEach(el => io.observe(el));

      // Progress bar animation
      const progressBars = document.querySelectorAll('[data-progress]');
      const progressIo = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) return;
          const el = entry.target;
          const targetWidth = el.getAttribute('data-progress');
          el.style.width = '0%';
          setTimeout(() => {
            el.style.width = targetWidth + '%';
          }, 100);
          progressIo.unobserve(el);
        });
      }, {
        threshold: 0.5
      });
      progressBars.forEach(el => progressIo.observe(el));

      // Update last update time every minute
      setInterval(() => {
        const now = new Date();
        const formatted = now.toLocaleString('id-ID', {
          day: '2-digit',
          month: 'short',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        }) + ' WIB';
        const lastUpdateEl = document.getElementById('lastUpdate');
        if (lastUpdateEl) {
          lastUpdateEl.textContent = formatted;
        }
      }, 60000);

      // Keyboard shortcuts
      document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
          e.preventDefault();
          input?.focus();
        }
        // Escape to clear search
        if (e.key === 'Escape' && input) {
          input.value = '';
          input.blur();
          applyFilter();
        }
      });

      // Tooltip untuk keyboard shortcut
      if (input) {
        const tooltip = document.createElement('div');
        tooltip.className = 'absolute right-28 top-1/2 -translate-y-1/2 text-xs text-slate-400 hidden sm:block';
        tooltip.innerHTML = '<kbd class="rounded bg-slate-100 px-1.5 py-0.5 font-mono text-xs">Ctrl+K</kbd>';
        input.parentElement.appendChild(tooltip);
      }
    });
  </script>
@endsection
