{{-- resources/views/components/navbar.blade.php --}}
<header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur-md">
  <div class="mx-auto w-full px-6 lg:px-12">
    <div class="flex h-20 items-center justify-between">

      {{-- Logo --}}
      <a href="/"
         class="flex items-center gap-3 transition-transform hover:scale-105">
        <img src="{{ asset('images/LOGO NAV.png') }}"
             alt="Logo PMI"
             class="h-28 w-28 object-contain md:h-28 md:w-28">
        <div class="flex flex-col">
          <span class="text-base font-bold text-slate-900 md:text-lg">Provinsi Lampung</span>
          <span class="hidden text-xs text-slate-500 sm:block">Unit Donor Darah</span>
        </div>
      </a>

      {{-- Menu desktop --}}
      <nav class="hidden items-center gap-1 text-sm md:flex">
        <a href="{{ url('/') }}"
           class="{{ request()->is('/') ? 'text-red-600' : 'text-slate-700 hover:text-red-600' }} group relative px-4 py-2 font-semibold transition-colors">
          Beranda
          <span
                class="{{ request()->is('/') ? 'scale-x-100' : '' }} absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-red-600 transition-transform group-hover:scale-x-100"></span>
        </a>
        <a href="{{ url('/pemesanan') }}"
           class="{{ request()->is('pemesanan*') ? 'text-red-600' : 'text-slate-700 hover:text-red-600' }} group relative px-4 py-2 font-semibold transition-colors">
          Pemesanan
          <span
                class="{{ request()->is('pemesanan*') ? 'scale-x-100' : '' }} absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-red-600 transition-transform group-hover:scale-x-100"></span>
        </a>
        <a href="{{ url('/stok') }}"
           class="{{ request()->is('stok*') ? 'text-red-600' : 'text-slate-700 hover:text-red-600' }} group relative px-4 py-2 font-semibold transition-colors">
          Stok Darah
          <span
                class="{{ request()->is('stok*') ? 'scale-x-100' : '' }} absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-red-600 transition-transform group-hover:scale-x-100"></span>
        </a>
        <a href="{{ url('/about') }}"
           class="{{ request()->is('about*') ? 'text-red-600' : 'text-slate-700 hover:text-red-600' }} group relative px-4 py-2 font-semibold transition-colors">
          Tentang Kami
          <span
                class="{{ request()->is('about*') ? 'scale-x-100' : '' }} absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-red-600 transition-transform group-hover:scale-x-100"></span>
        </a>
        <a href="{{ url('/jadwal-event') }}"
           class="{{ request()->is('jadwal-event*') ? 'text-red-600' : 'text-slate-700 hover:text-red-600' }} group relative px-4 py-2 font-semibold transition-colors">
          Jadwal Event
          <span
                class="{{ request()->is('jadwal-event*') ? 'scale-x-100' : '' }} absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-red-600 transition-transform group-hover:scale-x-100"></span>
        </a>
        <a href="{{ route('admin.login') }}"
           class="ml-2 inline-flex items-center gap-2 rounded-lg bg-red-600 px-5 py-2.5 font-semibold text-white shadow-md transition-all hover:bg-red-700 hover:shadow-lg">
          <svg class="h-4 w-4"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          Login Admin
        </a>
      </nav>

      {{-- Toggle mobile --}}
      <button id="menuBtn"
              type="button"
              class="rounded-lg border border-slate-300 p-2.5 transition-colors hover:bg-slate-50 md:hidden">
        <svg id="menuIcon"
             xmlns="http://www.w3.org/2000/svg"
             class="h-6 w-6 transition-transform"
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
         class="hidden overflow-hidden transition-all duration-300 ease-in-out md:hidden">
      <nav class="space-y-1 py-4">
        <a href="{{ url('/') }}"
           class="{{ request()->is('/') ? 'bg-red-50 text-red-600 font-semibold border-l-4 border-red-600' : 'text-slate-700 hover:bg-slate-50' }} block rounded-lg px-4 py-3 transition-all">
          <div class="flex items-center gap-3">
            <svg class="h-5 w-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Beranda</span>
          </div>
        </a>
        <a href="{{ url('/pemesanan') }}"
           class="{{ request()->is('pemesanan*') ? 'bg-red-50 text-red-600 font-semibold border-l-4 border-red-600' : 'text-slate-700 hover:bg-slate-50' }} block rounded-lg px-4 py-3 transition-all">
          <div class="flex items-center gap-3">
            <svg class="h-5 w-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Pemesanan</span>
          </div>
        </a>
        <a href="{{ url('/stok') }}"
           class="{{ request()->is('stok*') ? 'bg-red-50 text-red-600 font-semibold border-l-4 border-red-600' : 'text-slate-700 hover:bg-slate-50' }} block rounded-lg px-4 py-3 transition-all">
          <div class="flex items-center gap-3">
            <svg class="h-5 w-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <span>Stok Darah</span>
          </div>
        </a>
        <a href="{{ url('/about') }}"
           class="{{ request()->is('about*') ? 'bg-red-50 text-red-600 font-semibold border-l-4 border-red-600' : 'text-slate-700 hover:bg-slate-50' }} block rounded-lg px-4 py-3 transition-all">
          <div class="flex items-center gap-3">
            <svg class="h-5 w-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Tentang Kami</span>
          </div>
        </a>
        <a href="{{ url('/jadwal-event') }}"
           class="{{ request()->is('jadwal-event*') ? 'bg-red-50 text-red-600 font-semibold border-l-4 border-red-600' : 'text-slate-700 hover:bg-slate-50' }} block rounded-lg px-4 py-3 transition-all">
          <div class="flex items-center gap-3">
            <svg class="h-5 w-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Jadwal Event</span>
          </div>
        </a>
        <a href="{{ route('admin.login') }}"
           class="mt-2 block rounded-lg bg-red-600 px-4 py-3 text-center font-semibold text-white shadow-md transition-all hover:bg-red-700">
          <div class="flex items-center justify-center gap-2">
            <svg class="h-5 w-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Login Admin</span>
          </div>
        </a>
      </nav>
    </div>
  </div>
</header>

<script>
  // Toggle menu mobile dengan animasi smooth
  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  const menuIcon = document.getElementById('menuIcon');

  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');

    // Rotate icon saat menu terbuka
    if (!mobileMenu.classList.contains('hidden')) {
      menuIcon.style.transform = 'rotate(90deg)';
    } else {
      menuIcon.style.transform = 'rotate(0deg)';
    }
  });

  // Tutup menu ketika klik di luar
  document.addEventListener('click', (e) => {
    if (!menuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
      mobileMenu.classList.add('hidden');
      menuIcon.style.transform = 'rotate(0deg)';
    }
  });

  // Tutup menu mobile saat resize ke desktop
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 768) {
      mobileMenu.classList.add('hidden');
      menuIcon.style.transform = 'rotate(0deg)';
    }
  });
</script>
