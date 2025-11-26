@extends('layouts.admin')

@php
  use App\Helpers\StokHelper;
@endphp

@section('content')
  <div class="mb-4">
    <h1 class="text-xl font-bold md:text-2xl">Stok Darah</h1>
    <p class="text-sm text-slate-500">Monitoring real-time stok darah per golongan</p>
  </div>

  {{-- KARTU STOK PER GOLONGAN --}}
  <div class="mb-4 grid grid-cols-2 gap-3 md:grid-cols-4">
    @foreach (['A', 'AB', 'B', 'O'] as $g)
      @php [$label,$cls] = StokHelper::badgeStatus($stats['stok'][$g]); @endphp
      <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm md:p-4">
        <div class="mb-2 flex items-center justify-between">
          <span
                class="grid size-7 place-items-center rounded-lg bg-slate-100 text-sm font-semibold md:size-8">{{ $g }}</span>
          <span class="{{ $cls }} rounded px-1.5 py-0.5 text-xs md:px-2 md:py-1">{{ $label }}</span>
        </div>
        <div class="text-2xl font-bold leading-none md:text-3xl">{{ $stats['stok'][$g] }}</div>
        <div class="mt-1 text-xs text-slate-500">Unit tersedia</div>
      </div>
    @endforeach
  </div>

  {{-- DARAH MASUK & KELUAR --}}
  <div class="mb-4 grid grid-cols-1 gap-3 md:grid-cols-2">
    <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm md:p-4">
      <div class="mb-1 flex items-center gap-2">
        <div class="grid size-6 place-items-center rounded-lg bg-emerald-50 text-emerald-700">
          {{-- icon arrow-down-circle --}}
          <svg xmlns="http://www.w3.org/2000/svg"
               viewBox="0 0 24 24"
               class="size-4"
               fill="none"
               stroke="currentColor"
               stroke-width="1.6">
            <path d="M12 3v14m0 0l-4-4m4 4l4-4" />
            <circle cx="12"
                    cy="12"
                    r="9" />
          </svg>
        </div>
        <h3 class="text-sm font-semibold">Darah Masuk</h3>
      </div>
      <div class="text-2xl font-bold leading-none md:text-3xl">{{ $stats['masuk']['jumlah'] }}</div>
      @if (!is_null($stats['masuk']['trend']))
        @php $t=$stats['masuk']['trend']; @endphp
        <div class="{{ $t >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-1.5 text-xs">
          {{ $t >= 0 ? '↑' : '↓' }} {{ $t }}% dari bulan lalu
        </div>
      @endif
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm md:p-4">
      <div class="mb-1 flex items-center gap-2">
        <div class="grid size-6 place-items-center rounded-lg bg-rose-50 text-rose-700">
          {{-- icon arrow-up-circle --}}
          <svg xmlns="http://www.w3.org/2000/svg"
               viewBox="0 0 24 24"
               class="size-4"
               fill="none"
               stroke="currentColor"
               stroke-width="1.6">
            <path d="M12 21V7m0 0l4 4m-4-4l-4 4" />
            <circle cx="12"
                    cy="12"
                    r="9" />
          </svg>
        </div>
        <h3 class="text-sm font-semibold">Darah Keluar</h3>
      </div>
      <div class="text-2xl font-bold leading-none md:text-3xl">{{ $stats['keluar']['jumlah'] }}</div>
      @if (!is_null($stats['keluar']['trend']))
        @php $t=$stats['keluar']['trend']; @endphp
        <div class="{{ $t <= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-1.5 text-xs">
          {{ $t >= 0 ? '↑' : '↓' }} {{ $t }}% dari bulan lalu
        </div>
      @endif
    </div>
  </div>

  {{-- GRAFIK STOK PRODUK (FULL WIDTH) --}}
  <div class="mb-4">
    <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm md:p-4">
      <h3 class="mb-3 text-sm font-semibold">Grafik Stok per Produk</h3>
      <canvas id="stokChart"
              height="100"></canvas>
    </div>
  </div>

  {{-- STOCK ALERTS & TREND PEMESANAN --}}
  <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
    {{-- Stock Alerts --}}
    <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm md:p-4">
      <div class="mb-3 flex items-center justify-between">
        <h3 class="text-sm font-semibold">⚠️ Stock Alerts (< 30
            unit)</h3>
            <span class="rounded-lg bg-red-100 px-2 py-1 text-xs text-red-700">
              {{ count($stats['stock_alerts'] ?? []) }} item
            </span>
      </div>
      <div class="max-h-[260px] space-y-2 overflow-y-auto pr-2"
           style="scrollbar-width: thin; scrollbar-color: rgb(203 213 225) transparent;">
        @forelse($stats['stock_alerts'] ?? [] as $alert)
          <div class="flex items-center justify-between rounded-lg bg-red-50 p-2.5">
            <div class="flex items-center gap-2">
              <div
                   class="flex size-7 items-center justify-center rounded-lg bg-red-100 text-xs font-semibold text-red-700">
                {{ $alert->gol_darah }}
              </div>
              <div>
                <div class="text-sm font-medium">{{ $alert->gol_darah }}{{ $alert->rhesus }}</div>
                <div class="text-xs text-slate-500">Stok Rendah</div>
              </div>
            </div>
            <div class="text-right">
              <div class="font-bold text-red-600">{{ $alert->total }}</div>
              <div class="text-xs text-slate-500">unit</div>
            </div>
          </div>
        @empty
          <div class="rounded-lg bg-emerald-50 p-3 text-center text-sm text-emerald-700">
            ✓ Semua stok dalam kondisi aman
          </div>
        @endforelse
      </div>
    </div>

    {{-- Trend Pemesanan 6 Bulan --}}
    <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm md:p-4">
      <h3 class="mb-3 text-sm font-semibold">Trend Pemesanan (6 Bulan)</h3>
      <canvas id="trendChart"
              height="180"></canvas>
    </div>
  </div>

  {{-- Script untuk Chart.js --}}
  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Debug data
        console.log('Stok Produk:', @json($stats['stok_produk'] ?? []));
        console.log('Trend Pemesanan:', @json($stats['trend_pemesanan'] ?? []));
        console.log('Status Distribution:', @json($stats['status_distribution'] ?? []));

        // 1. Stok per Produk (Bar Chart)
        const ctx = document.getElementById('stokChart').getContext('2d');
        const stokData = @json($stats['stok_produk'] ?? []);

        // Cek apakah ada data (selain 0 semua)
        const hasData = stokData.some(val => val > 0);

        if (hasData) {
          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['WB', 'PRC', 'TC', 'FFP', 'CRYO', 'LP', 'TCA', 'CP'],
              datasets: [{
                label: 'Stok Tersedia',
                data: stokData,
                backgroundColor: [
                  'rgba(59, 130, 246, 0.5)',
                  'rgba(16, 185, 129, 0.5)',
                  'rgba(245, 158, 11, 0.5)',
                  'rgba(99, 102, 241, 0.5)',
                  'rgba(236, 72, 153, 0.5)',
                  'rgba(124, 58, 237, 0.5)'
                ],
                borderColor: [
                  'rgb(59, 130, 246)',
                  'rgb(16, 185, 129)',
                  'rgb(245, 158, 11)',
                  'rgb(99, 102, 241)',
                  'rgb(236, 72, 153)',
                  'rgb(124, 58, 237)'
                ],
                borderWidth: 1
              }]
            },
            options: {
              responsive: true,
              scales: {
                y: {
                  beginAtZero: true,
                  grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                  }
                },
                x: {
                  grid: {
                    display: false
                  }
                }
              },
              plugins: {
                legend: {
                  display: false
                }
              }
            }
          });
        } else {
          // Tampilkan pesan jika tidak ada data
          ctx.canvas.parentElement.innerHTML =
            '<div class="flex items-center justify-center h-full text-slate-400 text-sm">Tidak ada data stok produk</div>';
        }

        // 2. Trend Pemesanan (Line Chart)
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const trendData = @json($stats['trend_pemesanan'] ?? []);

        if (trendData && trendData.length > 0) {
          new Chart(trendCtx, {
            type: 'line',
            data: {
              labels: trendData.map(t => t.month),
              datasets: [{
                label: 'Jumlah Pemesanan',
                data: trendData.map(t => t.count),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
              }]
            },
            options: {
              responsive: true,
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    precision: 0
                  }
                }
              },
              plugins: {
                legend: {
                  display: false
                }
              }
            }
          });
        } else {
          // Tampilkan pesan jika tidak ada data
          trendCtx.canvas.parentElement.innerHTML =
            '<div class="flex items-center justify-center h-full text-slate-400 text-sm">Tidak ada data pemesanan</div>';
        }
      });
    </script>
  @endpush

  {{-- Custom scrollbar untuk Stock Alerts --}}
  <style>
    .overflow-y-auto::-webkit-scrollbar {
      width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 10px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 10px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
  </style>
@endsection
