{{-- resources/views/components/navbar.blade.php --}}
<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur-md">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between sm:h-20">

      {{-- Logo --}}
      <a href="/"
         class="flex items-center gap-2 transition-transform hover:scale-105 sm:gap-3">
        <img src="{{ asset('images/LOGO NAV.png') }}"
             alt="Logo PMI"
             class="h-14 w-14 object-contain sm:h-16 sm:w-16 md:h-20 md:w-20">
        <div class="flex flex-col">
          <span class="text-sm font-bold text-slate-900 sm:text-base md:text-lg">Provinsi Lampung</span>
          <span class="hidden text-xs text-slate-500 sm:block">Unit Donor Darah</span>
        </div>
      </a>

      {{-- Menu desktop --}}
      <nav class="hidden items-center gap-1 text-sm lg:flex">
        <a href="{{ url('/') }}"
           class="{{ request()->is('/') ? 'text-red-600' : 'text-slate-700 hover:text-red-600' }} group relative px-3 py-2 font-semibold transition-colors xl:px-4">
          Beranda
          <span
                class="{{ request()->is('/') ? 'scale-x-100' : '' }} absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-red-600 transition-transform group-hover:scale-x-100"></span>
        </a>
        <a href="{{ url('/pemesanan') }}"
           class="{{ request()->is('pemesanan*') ? 'text-red-600' : 'text-slate-700 hover:text-red-600' }} group relative px-3 py-2 font-semibold transition-colors xl:px-4">
          Pemesanan
          <span
                class="{{ request()->is('pemesanan*') ? 'scale-x-100' : '' }} absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-red-600 transition-transform group-hover:scale-x-100"></span>
        </a>
        <a href="{{ url('/stok') }}"
           class="{{ request()->is('stok*') ? 'text-red-600' : 'text-slate-700 hover:text-red-600' }} group relative px-3 py-2 font-semibold transition-colors xl:px-4">
          Stok Darah
          <span
                class="{{ request()->is('stok*') ? 'scale-x-100' : '' }} absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-red-600 transition-transform group-hover:scale-x-100"></span>
        </a>
        <a href="{{ url('/about') }}"
           class="{{ request()->is('about*') ? 'text-red-600' : 'text-slate-700 hover:text-red-600' }} group relative px-3 py-2 font-semibold transition-colors xl:px-4">
          Tentang Kami
          <span
                class="{{ request()->is('about*') ? 'scale-x-100' : '' }} absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-red-600 transition-transform group-hover:scale-x-100"></span>
        </a>
        <a href="{{ url('/jadwal-event') }}"
           class="{{ request()->is('jadwal-event*') ? 'text-red-600' : 'text-slate-700 hover:text-red-600' }} group relative px-3 py-2 font-semibold transition-colors xl:px-4">
          Jadwal Event
          <span
                class="{{ request()->is('jadwal-event*') ? 'scale-x-100' : '' }} absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-red-600 transition-transform group-hover:scale-x-100"></span>
        </a>
        <a href="{{ route('admin.login') }}"
           class="ml-2 inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition-all hover:bg-red-700 hover:shadow-lg xl:px-5 xl:py-2.5">
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
      <button id="navbarMenuBtn"
              type="button"
              aria-label="Toggle Menu"
              class="rounded-lg border border-slate-300 p-2 transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-red-500 lg:hidden">
        <svg id="navbarMenuIcon"
             xmlns="http://www.w3.org/2000/svg"
             class="h-6 w-6 transition-transform duration-300"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path id="navbarMenuIconPath"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16m-16 6h16" />
        </svg>
      </button>
    </div>

    {{-- Menu mobile --}}
    <div id="navbarMobileMenu"
         class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out lg:hidden">
      <nav class="space-y-1 pb-4 pt-2">
        <a href="{{ url('/') }}"
           class="{{ request()->is('/') ? 'bg-red-50 text-red-600 font-semibold border-l-4 border-red-600' : 'text-slate-700 hover:bg-slate-50' }} block rounded-lg px-4 py-2.5 text-sm transition-all">
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
           class="{{ request()->is('pemesanan*') ? 'bg-red-50 text-red-600 font-semibold border-l-4 border-red-600' : 'text-slate-700 hover:bg-slate-50' }} block rounded-lg px-4 py-2.5 text-sm transition-all">
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
           class="{{ request()->is('stok*') ? 'bg-red-50 text-red-600 font-semibold border-l-4 border-red-600' : 'text-slate-700 hover:bg-slate-50' }} block rounded-lg px-4 py-2.5 text-sm transition-all">
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
           class="{{ request()->is('about*') ? 'bg-red-50 text-red-600 font-semibold border-l-4 border-red-600' : 'text-slate-700 hover:bg-slate-50' }} block rounded-lg px-4 py-2.5 text-sm transition-all">
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
           class="{{ request()->is('jadwal-event*') ? 'bg-red-50 text-red-600 font-semibold border-l-4 border-red-600' : 'text-slate-700 hover:bg-slate-50' }} block rounded-lg px-4 py-2.5 text-sm transition-all">
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
           class="mt-2 block rounded-lg bg-red-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-md transition-all hover:bg-red-700">
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

@push('scripts')
  <script>
    (function() {
      // Toggle menu mobile dengan animasi smooth
      const menuBtn = document.getElementById('navbarMenuBtn');
      const mobileMenu = document.getElementById('navbarMobileMenu');
      const menuIcon = document.getElementById('navbarMenuIcon');
      const menuIconPath = document.getElementById('navbarMenuIconPath');

      if (menuBtn && mobileMenu && menuIcon) {
        let isOpen = false;

        menuBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          isOpen = !isOpen;

          if (isOpen) {
            mobileMenu.style.maxHeight = mobileMenu.scrollHeight + 'px';
            menuIcon.style.transform = 'rotate(90deg)';
            menuIconPath.setAttribute('d', 'M6 18L18 6M6 6l12 12'); // X icon
          } else {
            mobileMenu.style.maxHeight = '0';
            menuIcon.style.transform = 'rotate(0deg)';
            menuIconPath.setAttribute('d', 'M4 6h16M4 12h16m-16 6h16'); // Hamburger icon
          }
        });

        // Tutup menu ketika klik di luar
        document.addEventListener('click', (e) => {
          if (isOpen && !menuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
            isOpen = false;
            mobileMenu.style.maxHeight = '0';
            menuIcon.style.transform = 'rotate(0deg)';
            menuIconPath.setAttribute('d', 'M4 6h16M4 12h16m-16 6h16');
          }
        });

        // Tutup menu mobile saat resize ke desktop
        window.addEventListener('resize', () => {
          if (window.innerWidth >= 1024) {
            isOpen = false;
            mobileMenu.style.maxHeight = '0';
            menuIcon.style.transform = 'rotate(0deg)';
            menuIconPath.setAttribute('d', 'M4 6h16M4 12h16m-16 6h16');
          }
        });
      }
    })();
  </script>
@endpush
