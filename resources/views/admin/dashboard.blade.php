@extends('layouts.admin')

@php
  // helper badge status stok
  function badgeStatus($total) {
    if ($total >= 80) return ['Aman','bg-emerald-100 text-emerald-700'];
    if ($total >= 40) return ['Perhatian','bg-amber-100 text-amber-700'];
    return ['Kritis','bg-rose-100 text-rose-700'];
  }
@endphp

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold">Stok Darah</h1>
    <p class="text-slate-500">Monitoring real-time stok darah per golongan</p>
  </div>

  {{-- KARTU STOK PER GOLONGAN --}}
  <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
    @foreach (['A','AB','B','O'] as $g)
      @php [$label,$cls] = badgeStatus($stats['stok'][$g]); @endphp
      <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
          <span class="size-8 grid place-items-center rounded-xl bg-slate-100 font-semibold">{{ $g }}</span>
          <span class="text-xs px-2 py-1 rounded-lg {{ $cls }}">{{ $label }}</span>
        </div>
        <div class="text-4xl font-bold leading-none">{{ $stats['stok'][$g] }}</div>
        <div class="text-slate-500 text-sm mt-1">Unit tersedia</div>
      </div>
    @endforeach
  </div>

  {{-- DARAH MASUK & KELUAR --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
      <div class="flex items-center gap-2 mb-1">
        <div class="size-7 grid place-items-center rounded-lg bg-emerald-50 text-emerald-700">
          {{-- icon arrow-down-circle --}}
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3v14m0 0l-4-4m4 4l4-4"/><circle cx="12" cy="12" r="9"/></svg>
        </div>
        <h3 class="font-semibold">Darah Masuk</h3>
      </div>
      <div class="text-4xl font-bold leading-none">{{ $stats['masuk']['jumlah'] }}</div>
      @if(!is_null($stats['masuk']['trend']))
        @php $t=$stats['masuk']['trend']; @endphp
        <div class="text-xs mt-2 {{ $t>=0 ? 'text-emerald-600' : 'text-rose-600' }}">
          {{ $t>=0 ? '↑' : '↓' }} {{ $t }}% dari bulan lalu
        </div>
      @endif
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
      <div class="flex items-center gap-2 mb-1">
        <div class="size-7 grid place-items-center rounded-lg bg-rose-50 text-rose-700">
          {{-- icon arrow-up-circle --}}
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 21V7m0 0l4 4m-4-4l-4 4"/><circle cx="12" cy="12" r="9"/></svg>
        </div>
        <h3 class="font-semibold">Darah Keluar</h3>
      </div>
      <div class="text-4xl font-bold leading-none">{{ $stats['keluar']['jumlah'] }}</div>
      @if(!is_null($stats['keluar']['trend']))
        @php $t=$stats['keluar']['trend']; @endphp
        <div class="text-xs mt-2 {{ $t<=0 ? 'text-emerald-600' : 'text-rose-600' }}">
          {{ $t>=0 ? '↑' : '↓' }} {{ $t }}% dari bulan lalu
        </div>
      @endif
    </div>
  </div>
@endsection
