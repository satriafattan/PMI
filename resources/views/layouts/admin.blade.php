<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'Dashboard Admin' }} — {{ config('app.name') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800">
<div class="min-h-screen flex">

  {{-- ============== SIDEBAR ============== --}}
  {{-- Drawer (mobile) + Pinned (≥ md) --}}
  <div
    id="sidebarBackdrop"
    class="fixed inset-0 z-30 hidden bg-black/40 md:hidden"
    aria-hidden="true"
  ></div>

  <aside
    id="sidebar"
    class="fixed z-40 inset-y-0 left-0 w-72 max-w-full -translate-x-full md:translate-x-0 md:static md:z-auto
           bg-slate-900 text-slate-100 flex flex-col transition-transform duration-200 will-change-transform"
    role="navigation"
    aria-label="Sidebar"
  >
    <div class="px-5 py-5 border-b border-slate-800 flex items-center justify-between">
      <div>
        <div class="text-xs md:text-sm uppercase tracking-wide text-slate-400">PMI Lampung</div>
        <div class="text-base md:text-lg font-semibold">Blood Management</div>
      </div>

      {{-- Close button (mobile) --}}
      <button
        id="sidebarCloseBtn"
        class="md:hidden inline-flex items-center justify-center rounded-lg p-2 text-slate-300 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-600"
        aria-label="Tutup menu samping"
      >
        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
      <x-admin.nav-item route="admin.dashboard" icon="M3 6h18M3 12h18M3 18h18">Dashboard</x-admin.nav-item>
      <x-admin.nav-item route="admin.verifikasi.index" icon="M8 7v10l9-5-9-5z">Verifikasi Pemesanan</x-admin.nav-item>
      <x-admin.nav-item route="admin.riwayat.index" icon="M4 6h16M4 10h16M4 14h16M4 18h16">Riwayat Pemesanan</x-admin.nav-item>
      <x-admin.nav-item route="admin.stok-darah.index" icon="M20 13V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7m16 0v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-5m16 0H4">Stok Darah</x-admin.nav-item>
      <x-admin.nav-item route="admin.pemesanan.index" icon="M3 7h18M8 7v13m8-13v13">Detail Darah</x-admin.nav-item>
      <x-admin.nav-item route="admin.laporan.index" icon="M4 6h16v12H4z">Laporan Stok Darah</x-admin.nav-item>
      <x-admin.nav-item route="admin.event-verifikasi.index" icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
    Verifikasi Event </x-admin.nav-item>
    </nav>

    <div class="p-3 border-t border-slate-800">
      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-800 flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12"/>
          </svg>
          Keluar
        </button>
      </form>
    </div>
  </aside>

  {{-- ============== MAIN ============== --}}
  <div class="flex-1 flex flex-col md:ml-0">

    {{-- TOPBAR --}}
    <header class="h-16 bg-white border-b border-slate-200 flex items-center sticky top-0 z-20">
      <div class="px-4 md:px-6 w-full flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
          {{-- Hamburger (mobile) --}}
          <button
            id="sidebarOpenBtn"
            class="md:hidden inline-flex items-center justify-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-300"
            aria-label="Buka menu samping"
            aria-controls="sidebar"
            aria-expanded="false"
          >
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>

          <div class="min-w-0">
            <div class="text-lg md:text-xl font-bold truncate">{{ $title ?? 'Dashboard Admin' }}</div>
            <div class="text-xs md:text-sm text-slate-500 truncate">Sistem Manajemen Stok Darah</div>
          </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-3 flex-wrap justify-end">
          <button class="px-3 py-1.5 rounded-lg border text-xs sm:text-sm hover:bg-slate-50">
            Export Data
          </button>
          <a href="{{ route('admin.rekap-stok.create') }}"
             class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-xs sm:text-sm hover:bg-blue-700">
            Tambah Stok
          </a>
          <div class="size-9 grid place-items-center bg-blue-100 text-blue-700 font-semibold rounded-xl">
            {{ Str::of(auth('admin')->user()->name ?? 'A')->substr(0,1)->upper() }}
          </div>
        </div>
      </div>
    </header>

    {{-- PAGE CONTENT --}}
    <main class="p-4 md:p-6">
      {{ $slot ?? '' }}
      @yield('content')
    </main>
  </div>
</div>

{{-- ===== Drawer JS (tanpa library) ===== --}}
<script>
  (function () {
    const drawer   = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const openBtn  = document.getElementById('sidebarOpenBtn');
    const closeBtn = document.getElementById('sidebarCloseBtn');

    const focusableSelector = 'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])';
    let lastFocusedBeforeOpen = null;

    function isOpen() {
      return !drawer.classList.contains('-translate-x-full');
    }
    function openDrawer() {
      lastFocusedBeforeOpen = document.activeElement;
      drawer.classList.remove('-translate-x-full');
      backdrop.classList.remove('hidden');
      openBtn?.setAttribute('aria-expanded', 'true');

      // focus trap
      const focusables = drawer.querySelectorAll(focusableSelector);
      (focusables[0] || drawer).focus();
      document.addEventListener('keydown', onKeydown);
    }
    function closeDrawer() {
      drawer.classList.add('-translate-x-full');
      backdrop.classList.add('hidden');
      openBtn?.setAttribute('aria-expanded', 'false');

      // restore focus
      lastFocusedBeforeOpen && lastFocusedBeforeOpen.focus();
      document.removeEventListener('keydown', onKeydown);
    }
    function onKeydown(e) {
      if (e.key === 'Escape') { closeDrawer(); }
      if (e.key === 'Tab') {
        // trap focus
        const nodes = drawer.querySelectorAll(focusableSelector);
        if (!nodes.length) return;
        const first = nodes[0];
        const last  = nodes[nodes.length - 1];
        if (e.shiftKey && document.activeElement === first) {
          e.preventDefault(); last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
          e.preventDefault(); first.focus();
        }
      }
    }

    openBtn?.addEventListener('click', openDrawer);
    closeBtn?.addEventListener('click', closeDrawer);
    backdrop?.addEventListener('click', closeDrawer);

    // Close drawer when viewport becomes md and above (prevent stuck state)
    const mq = window.matchMedia('(min-width: 768px)');
    mq.addEventListener?.('change', () => { if (mq.matches) closeDrawer(); });
  })();
</script>

</body>
</html>
