@extends('layouts.admin')

@php
  use App\Helpers\StokHelper;
@endphp

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold">Stok Darah</h1>
    <p class="text-slate-500">Monitoring real-time stok darah per golongan</p>
  </div>

  {{-- KARTU STOK PER GOLONGAN --}}
  <div class="mb-6 grid grid-cols-1 gap-5 md:grid-cols-4">
    @foreach (['A', 'AB', 'B', 'O'] as $g)
      @php [$label,$cls] = StokHelper::badgeStatus($stats['stok'][$g]); @endphp
      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-3 flex items-center justify-between">
          <span class="grid size-8 place-items-center rounded-xl bg-slate-100 font-semibold">{{ $g }}</span>
          <span class="{{ $cls }} rounded-lg px-2 py-1 text-xs">{{ $label }}</span>
        </div>
        <div class="text-4xl font-bold leading-none">{{ $stats['stok'][$g] }}</div>
        <div class="mt-1 text-sm text-slate-500">Unit tersedia</div>
      </div>
    @endforeach
  </div>

  {{-- DARAH MASUK & KELUAR --}}
  <div class="mb-6 grid grid-cols-1 gap-5 md:grid-cols-2">
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="mb-1 flex items-center gap-2">
        <div class="grid size-7 place-items-center rounded-lg bg-emerald-50 text-emerald-700">
          {{-- icon arrow-down-circle --}}
          <svg xmlns="http://www.w3.org/2000/svg"
               viewBox="0 0 24 24"
               class="size-5"
               fill="none"
               stroke="currentColor"
               stroke-width="1.6">
            <path d="M12 3v14m0 0l-4-4m4 4l4-4" />
            <circle cx="12"
                    cy="12"
                    r="9" />
          </svg>
        </div>
        <h3 class="font-semibold">Darah Masuk</h3>
      </div>
      <div class="text-4xl font-bold leading-none">{{ $stats['masuk']['jumlah'] }}</div>
      @if (!is_null($stats['masuk']['trend']))
        @php $t=$stats['masuk']['trend']; @endphp
        <div class="{{ $t >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-2 text-xs">
          {{ $t >= 0 ? '↑' : '↓' }} {{ $t }}% dari bulan lalu
        </div>
      @endif
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="mb-1 flex items-center gap-2">
        <div class="grid size-7 place-items-center rounded-lg bg-rose-50 text-rose-700">
          {{-- icon arrow-up-circle --}}
          <svg xmlns="http://www.w3.org/2000/svg"
               viewBox="0 0 24 24"
               class="size-5"
               fill="none"
               stroke="currentColor"
               stroke-width="1.6">
            <path d="M12 21V7m0 0l4 4m-4-4l-4 4" />
            <circle cx="12"
                    cy="12"
                    r="9" />
          </svg>
        </div>
        <h3 class="font-semibold">Darah Keluar</h3>
      </div>
      <div class="text-4xl font-bold leading-none">{{ $stats['keluar']['jumlah'] }}</div>
      @if (!is_null($stats['keluar']['trend']))
        @php $t=$stats['keluar']['trend']; @endphp
        <div class="{{ $t <= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-2 text-xs">
          {{ $t >= 0 ? '↑' : '↓' }} {{ $t }}% dari bulan lalu
        </div>
      @endif
    </div>
  </div>

  {{-- GRAFIK DAN STATISTIK --}}
  <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
    {{-- Grafik Stok per Produk --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:col-span-2">
      <h3 class="mb-4 font-semibold">Grafik Stok per Produk</h3>
      <canvas id="stokChart"
              height="200"></canvas>
    </div>

    {{-- Statistik Cepat --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <h3 class="mb-4 font-semibold">Statistik Cepat</h3>
      <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
          <span class="text-slate-600">Total Permintaan</span>
          <span class="font-medium">{{ $stats['permintaan']['total'] ?? 0 }}</span>
        </div>
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
          <span class="text-slate-600">Permintaan Diproses</span>
          <span class="font-medium">{{ $stats['permintaan']['diproses'] ?? 0 }}</span>
        </div>
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
          <span class="text-slate-600">Stok Kritis</span>
          <span class="font-medium text-rose-600">{{ $stats['stok_kritis'] ?? 0 }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-slate-600">Total Stok</span>
          <span class="font-medium">{{ $stats['total_stok'] ?? 0 }}</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Script untuk Chart.js --}}
  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('stokChart').getContext('2d');
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: ['WB', 'PRC', 'TC', 'FFP', 'AHF', 'LP'],
            datasets: [{
              label: 'Stok Tersedia',
              data: {{ json_encode($stats['stok_produk'] ?? []) }},
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
      });
    </script>
  @endpush
@endsection
