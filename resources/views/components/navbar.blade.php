{{-- resources/views/components/navbar.blade.php --}}
<header class="sticky top-0 z-40 border-b border-slate-200 bg-white/80 backdrop-blur">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">

      {{-- Logo --}}
      <a href="/"
         class="flex items-center gap-3">
        <div class="grid h-9 w-9 place-items-center rounded-xl bg-red-600 font-bold text-white">UDD</div>
        <span class="font-bold">PMI Provinsi Lampung</span>
      </a>

      {{-- Menu desktop --}}
      <nav class="hidden items-center gap-8 text-sm md:flex">
        <a href="{{ url('/') }}"
           class="{{ request()->is('/') ? 'text-red-600 font-bold' : 'hover:text-red-600 font-bold' }}">
          Beranda
        </a>
        <a href="{{ url('/pemesanan') }}"
           class="{{ request()->is('pemesanan*') ? 'text-red-600 font-bold' : 'hover:text-red-600 font-bold' }}">
          Pemesanan
        </a>
        <a href="{{ url('/stok') }}"
           class="{{ request()->is('stok*') ? 'text-red-600 font-bold' : 'hover:text-red-600 font-bold' }}">
          Stok darah
        </a>
        <a href="{{ url('/about') }}"
           class="{{ request()->is('about*') ? 'text-red-600 font-bold' : 'hover:text-red-600 font-bold' }}">
          Tentang Kami
        </a>

        <a href="{{ url('/jadwal-event') }}"
           class="{{ request()->is('jadwal-event*') ? 'text-red-600 font-bold' : 'hover:text-red-600 font-bold' }}">
          Penjadwalan Event
        </a>
        <a href="{{ route('admin.login') }}"
           class="rounded-lg bg-red-600 px-4 py-2 text-white shadow hover:bg-red-700">
          Login Admin
        </a>
      </nav>

      {{-- Toggle mobile --}}
      <button id="menuBtn"
              type="button"
              class="rounded-lg border border-slate-300 p-2 hover:bg-slate-50 md:hidden">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-6 w-6"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16m-16 6h16" />
        </svg>
      </button>
    </div>

    {{-- Menu mobile --}}
    <div id="mobileMenu"
         class="hidden md:hidden">
      <nav class="space-y-2 py-4">
        <a href="{{ url('/') }}"
           class="{{ request()->is('/') ? 'bg-red-50 text-red-600 font-medium' : '' }} block rounded-lg px-4 py-2.5 hover:bg-slate-50">
          Beranda
        </a>
        <a href="{{ url('/pemesanan') }}"
           class="{{ request()->is('pemesanan*') ? 'bg-red-50 text-red-600 font-medium' : '' }} block rounded-lg px-4 py-2.5 hover:bg-slate-50">
          Pemesanan
        </a>
        <a href="{{ url('/stok') }}"
           class="{{ request()->is('stok*') ? 'bg-red-50 text-red-600 font-medium' : '' }} block rounded-lg px-4 py-2.5 hover:bg-slate-50">
          Stok darah
        </a>
        <a href="{{ url('/about') }}"
           class="{{ request()->is('about*') ? 'bg-red-50 text-red-600 font-medium' : '' }} block rounded-lg px-4 py-2.5 hover:bg-slate-50">
          Tentang Kami
        </a>
        <a href="{{ url('/jadwal-event') }}"
           class="{{ request()->is('jadwal-event*') ? 'bg-red-50 text-red-600 font-medium' : '' }} block rounded-lg px-4 py-2.5 hover:bg-slate-50">
          Penjadwalan Event
        </a>
        <a href="{{ route('admin.login') }}"
           class="block rounded-lg bg-red-600 px-4 py-2.5 text-center font-medium text-white hover:bg-red-700">
          Login Admin
        </a>
      </nav>
    </div>
  </div>
</header>

<script>
  // Toggle menu mobile
  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');

  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });

  // Tutup menu ketika klik di luar
  document.addEventListener('click', (e) => {
    if (!menuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
      mobileMenu.classList.add('hidden');
    }
  });
</script>
