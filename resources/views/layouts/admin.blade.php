<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Dashboard Admin' }} — {{ config('app.name') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800">
<div class="min-h-screen flex">

  {{-- SIDEBAR --}}
  <aside class="w-64 bg-slate-900 text-slate-100 flex flex-col">
    <div class="px-5 py-5 border-b border-slate-800">
      <div class="text-sm uppercase tracking-wide text-slate-400">PMI Lampung</div>
      <div class="text-lg font-semibold">Blood Management</div>
    </div>

    <nav class="flex-1 p-3 space-y-1">
      <x-admin.nav-item route="admin.dashboard" icon="M3 6h18M3 12h18M3 18h18">Dashboard</x-admin.nav-item>
      <x-admin.nav-item route="admin.verifikasi.index" icon="M8 7v10l9-5-9-5z">Verifikasi Pemesanan</x-admin.nav-item>
      <x-admin.nav-item route="admin.riwayat.index" icon="M4 6h16M4 10h16M4 14h16M4 18h16">Riwayat Pemesanan</x-admin.nav-item>
      <x-admin.nav-item route="admin.stok-darah.index" icon="M20 13V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7m16 0v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-5m16 0H4">Stok Darah</x-admin.nav-item>
      <x-admin.nav-item route="admin.pemesanan.index" icon="M3 7h18M8 7v13m8-13v13">Detail Darah</x-admin.nav-item>
      <x-admin.nav-item route="admin.rekap-stok.index" icon="M4 6h16v12H4z">Rekapitulasi Stok Darah</x-admin.nav-item>
    </nav>

    <div class="p-3 border-t border-slate-800">
      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-800 flex items-center gap-3">
          {{-- icon logout --}}
          <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12"/></svg>
          Keluar
        </button>
      </form>
    </div>
  </aside>

  {{-- MAIN --}}
  <div class="flex-1 flex flex-col">
    {{-- TOPBAR --}}
    <header class="h-16 bg-white border-b border-slate-200 flex items-center">
      <div class="px-6 w-full flex items-center justify-between">
        <div>
          <div class="text-xl font-bold">Dashboard Admin</div>
          <div class="text-sm text-slate-500">Sistem Manajemen Stok Darah</div>
        </div>
        <div class="flex items-center gap-3">
          <button class="px-3 py-1.5 rounded-lg border text-sm hover:bg-slate-50">Export Data</button>
          <a href="{{ route('admin.rekap-stok.create') }}" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700">Tambah Stok</a>
          <div class="size-9 grid place-items-center bg-blue-100 text-blue-700 font-semibold rounded-xl">
            {{ Str::of(auth('admin')->user()->name ?? 'A')->substr(0,1)->upper() }}
          </div>
        </div>
      </div>
    </header>

    {{-- PAGE CONTENT --}}
    <main class="p-6">
      {{ $slot ?? '' }}
      @yield('content')
    </main>
  </div>
</div>

</body>
</html>
