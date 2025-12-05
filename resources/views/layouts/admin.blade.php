<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport"
        content="width=device-width, initial-scale=1">
  <meta name="csrf-token"
        content="{{ csrf_token() }}">
  <meta http-equiv="Cache-Control"
        content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma"
        content="no-cache">
  <meta http-equiv="Expires"
        content="0">
  <title>{{ $title ?? 'Dashboard Admin' }} — {{ config('app.name') }}</title>

  {{-- Favicon --}}
  <link rel="icon"
        type="image/x-icon"
        href="{{ asset('images/simphony-logo.ico') }}">
  <link rel="shortcut icon"
        type="image/x-icon"
        href="{{ asset('images/simphony-logo.ico') }}">
  <link rel="apple-touch-icon"
        href="{{ asset('images/simphony-logo.png') }}">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script defer
          src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
  <script defer
          src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    /* Custom Scrollbar for Webkit browsers */
    .overflow-y-auto::-webkit-scrollbar {
      width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
      background: transparent;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
      background-color: rgb(203 213 225);
      border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
      background-color: rgb(148 163 184);
    }
  </style>
</head>

<body class="bg-slate-50 text-slate-800">
  <div class="flex min-h-screen">

    {{-- ============== SIDEBAR ============== --}}
    {{-- Drawer (mobile) + Static (≥ md) --}}
    <div id="sidebarBackdrop"
         class="fixed inset-0 z-30 hidden bg-black/40 md:hidden"
         aria-hidden="true"></div>

    {{-- Sidebar Desktop --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col bg-slate-900 text-slate-100 transition-transform duration-200 md:sticky md:top-0 md:z-auto md:h-screen md:translate-x-0 md:transition-none"
           role="navigation"
           aria-label="Sidebar">
      <div class="flex shrink-0 items-center justify-between border-b border-slate-800 px-5 py-5">
        <div>
          <div class="text-xs uppercase tracking-wide text-slate-400 md:text-sm">SIMPHONY</div>
          <div class="text-base font-semibold md:text-lg">Dashboard Admin</div>
        </div>

        {{-- Tombol Close (hanya tampil di mobile) --}}
        <button id="sidebarCloseBtn"
                class="inline-flex items-center justify-center rounded-lg p-2 text-slate-300 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-600 md:hidden"
                aria-label="Tutup menu samping">
          <svg class="size-5"
               viewBox="0 0 24 24"
               fill="none"
               stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="1.8"
                  d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <nav class="flex-1 space-y-1 overflow-y-auto p-3">
        <x-admin.nav-item route="admin.dashboard"
                          icon="M3 6h18M3 12h18M3 18h18">Dashboard</x-admin.nav-item>
        <x-admin.nav-item route="admin.verifikasi.index"
                          icon="M8 7v10l9-5-9-5z">Verifikasi Pemesanan</x-admin.nav-item>
        <x-admin.nav-item route="admin.riwayat.index"
                          icon="M4 6h16M4 10h16M4 14h16M4 18h16">Riwayat Pemesanan</x-admin.nav-item>
        <x-admin.nav-item route="admin.stok-darah.index"
                          icon="M20 13V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7m16 0v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-5m16 0H4">Stok
          Darah</x-admin.nav-item>

        <x-admin.nav-dropdown icon="M3 7h18M8 7v13m8-13v13"
                              label="Detail Darah"
                              :items="[
                                  ['route' => 'admin.detail-darah.tersedia', 'label' => 'Darah Tersedia'],
                                  ['route' => 'admin.detail-darah.keluar', 'label' => 'Darah Keluar'],
                                  ['route' => 'admin.detail-darah.kadaluwarsa', 'label' => 'Darah Kadaluwarsa'],
                              ]" />

        <x-admin.nav-item route="admin.laporan.index"
                          icon="M4 6h16v12H4z">Laporan Pemesanan Darah</x-admin.nav-item>
        <x-admin.nav-item route="admin.event-verifikasi.index"
                          icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
          Verifikasi Event </x-admin.nav-item>

        @if (auth('admin')->user()->isSuperAdmin())
          <x-admin.nav-item route="admin.admins.index"
                            icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
            Manajemen Admin</x-admin.nav-item>
        @endif
      </nav>

      <div class="shrink-0 border-t border-slate-800 p-3">
        <form method="POST"
              action="{{ route('admin.logout') }}">
          @csrf
          <button class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left hover:bg-slate-800">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="size-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12" />
            </svg>
            Keluar
          </button>
        </form>
      </div>
    </aside>

    {{-- ============== MAIN ============== --}}
    <div class="flex flex-1 flex-col">

      {{-- TOPBAR --}}
      <header class="sticky top-0 z-20 flex h-14 items-center border-b border-slate-200 bg-white md:h-16">
        <div class="flex w-full items-center justify-between gap-3 px-3 md:px-4">
          <div class="flex min-w-0 items-center gap-3">
            {{-- Hamburger (mobile) --}}
            <button id="sidebarOpenBtn"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-300 md:hidden"
                    aria-label="Buka menu samping"
                    aria-controls="sidebar"
                    aria-expanded="false">
              <svg class="size-5"
                   viewBox="0 0 24 24"
                   fill="none"
                   stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>

            <div class="min-w-0">
              <div class="truncate text-base font-bold md:text-lg">{{ $title ?? 'Dashboard Admin' }}</div>
              <div class="hidden truncate text-xs text-slate-500 sm:block">SIMPHONY - Sistem Informasi Pemesanan &
                Inventori
              </div>
            </div>
          </div>
          <div class="flex flex-wrap items-center justify-end gap-2 sm:gap-3">
            {{-- Notification Bell --}}
            <div class="relative">
              <button id="notificationBell"
                      class="relative grid size-9 place-items-center rounded-xl text-slate-600 transition hover:bg-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="size-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                {{-- Badge counter --}}
                <span id="notificationBadge"
                      style="display: none;"
                      class="absolute -right-1 -top-1 grid size-5 place-items-center rounded-full bg-red-600 text-xs font-bold text-white">
                  0
                </span>
              </button>

              {{-- Dropdown Notifikasi --}}
              <div id="notificationDropdown"
                   class="absolute right-0 z-50 mt-2 hidden max-h-96 w-80 overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 p-3">
                  <h3 class="text-sm font-semibold">Pemesanan Pending</h3>
                  <a href="{{ route('admin.verifikasi.index') }}"
                     class="text-xs text-blue-600 hover:underline">Lihat Semua</a>
                </div>
                <div id="notificationList"
                     class="divide-y divide-slate-100">
                  {{-- Will be populated by JavaScript --}}
                  <div class="p-4 text-center text-sm text-slate-400">
                    Tidak ada notifikasi baru
                  </div>
                </div>
              </div>
            </div>

            {{-- User Avatar & Dropdown --}}
            <div class="relative">
              <button id="userMenuButton"
                      class="grid size-9 place-items-center rounded-xl bg-blue-100 font-semibold text-blue-700 transition hover:bg-blue-200">
                {{ Str::of(auth('admin')->user()->name ?? 'A')->substr(0, 1)->upper() }}
              </button>

              {{-- User Dropdown Menu --}}
              <div id="userMenuDropdown"
                   class="absolute right-0 z-50 mt-2 hidden w-56 rounded-xl border border-slate-200 bg-white shadow-2xl">
                {{-- User Info --}}
                <div class="border-b border-slate-100 p-3">
                  <p class="truncate font-semibold text-slate-800">{{ auth('admin')->user()->name }}</p>
                  <p class="truncate text-xs text-slate-500">{{ auth('admin')->user()->email }}</p>
                </div>

                {{-- Menu Items --}}
                <div class="p-2">
                  <a href="{{ route('admin.profile.index') }}"
                     class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100">
                    <svg class="size-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor">
                      <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil Saya
                  </a>
                </div>
              </div>
            </div>
          </div>
      </header>

      {{-- PAGE CONTENT --}}
      <main class="p-3 md:p-4">
        {{ $slot ?? '' }}
        @yield('content')
      </main>
    </div>
  </div>

  {{-- ===== Drawer JS (tanpa library) ===== --}}
  <script>
    (function() {
      const drawer = document.getElementById('sidebar');
      const backdrop = document.getElementById('sidebarBackdrop');
      const openBtn = document.getElementById('sidebarOpenBtn');
      const closeBtn = document.getElementById('sidebarCloseBtn');

      const focusableSelector =
        'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])';
      let lastFocusedBeforeOpen = null;

      function isOpen() {
        return drawer && !drawer.classList.contains('-translate-x-full');
      }

      function openDrawer() {
        if (!drawer) return;
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
        if (!drawer) return;
        drawer.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
        openBtn?.setAttribute('aria-expanded', 'false');

        // restore focus
        lastFocusedBeforeOpen && lastFocusedBeforeOpen.focus();
        document.removeEventListener('keydown', onKeydown);
      }

      function onKeydown(e) {
        if (e.key === 'Escape') {
          closeDrawer();
        }
        if (e.key === 'Tab') {
          // trap focus
          const nodes = drawer.querySelectorAll(focusableSelector);
          if (!nodes.length) return;
          const first = nodes[0];
          const last = nodes[nodes.length - 1];
          if (e.shiftKey && document.activeElement === first) {
            e.preventDefault();
            last.focus();
          } else if (!e.shiftKey && document.activeElement === last) {
            e.preventDefault();
            first.focus();
          }
        }
      }

      openBtn?.addEventListener('click', openDrawer);
      closeBtn?.addEventListener('click', closeDrawer);
      backdrop?.addEventListener('click', closeDrawer);

      // Close drawer when viewport becomes md and above (prevent stuck state)
      const mq = window.matchMedia('(min-width: 768px)');
      mq.addEventListener?.('change', () => {
        if (mq.matches) closeDrawer();
      });
    })();
  </script>

  {{-- ===== Notification System with Polling ===== --}}
  <script>
    (function() {
      const bell = document.getElementById('notificationBell');
      const dropdown = document.getElementById('notificationDropdown');
      const badge = document.getElementById('notificationBadge');
      const list = document.getElementById('notificationList');

      let isDropdownOpen = false;
      let lastCount = 0;
      let hasPlayedSound = false;

      // Sound alert function
      function playNotificationSound() {
        if (!hasPlayedSound) {
          // Menggunakan Web Audio API untuk beep sound
          const audioContext = new(window.AudioContext || window.webkitAudioContext)();
          const oscillator = audioContext.createOscillator();
          const gainNode = audioContext.createGain();

          oscillator.connect(gainNode);
          gainNode.connect(audioContext.destination);

          oscillator.frequency.value = 800; // Frequency (Hz)
          oscillator.type = 'sine';

          gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
          gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

          oscillator.start(audioContext.currentTime);
          oscillator.stop(audioContext.currentTime + 0.5);

          hasPlayedSound = true;
          // Reset after 30 seconds
          setTimeout(() => {
            hasPlayedSound = false;
          }, 30000);
        }
      }

      // Toggle dropdown
      bell?.addEventListener('click', function(e) {
        e.stopPropagation();
        isDropdownOpen = !isDropdownOpen;
        dropdown.classList.toggle('hidden', !isDropdownOpen);
      });

      // Close dropdown when clicking outside
      document.addEventListener('click', function(e) {
        if (isDropdownOpen && !dropdown.contains(e.target)) {
          isDropdownOpen = false;
          dropdown.classList.add('hidden');
        }
      });

      // Fetch notifications via polling
      function fetchNotifications() {
        fetch('{{ route('admin.api.notifications') }}')
          .then(response => response.json())
          .then(data => {
            const count = data.count || 0;
            const orders = data.latest_orders || [];
            const hasUrgent = data.has_urgent || false;

            // Update badge
            if (count > 0) {
              badge.textContent = count > 99 ? '99+' : count;
              badge.style.display = 'grid';

              // Play sound on new notifications (only if count increased)
              if (count > lastCount && hasUrgent) {
                playNotificationSound();

                // Browser notification (jika diizinkan)
                if ('Notification' in window && Notification.permission === 'granted') {
                  new Notification('Pemesanan Urgent!', {
                    body: `Ada ${count} pemesanan pending, termasuk pemesanan urgent (≥10 kantong)`,
                    icon: '/images/logo.png',
                    tag: 'pmi-urgent',
                    requireInteraction: true
                  });
                }
              }

              lastCount = count;
            } else {
              badge.style.display = 'none';
              lastCount = 0;
            }

            // Update dropdown list
            if (orders.length > 0) {
              list.innerHTML = orders.map(order => `
              <a href="{{ route('admin.verifikasi.index') }}?q=${order.nama_pasien}" 
                 class="block p-3 hover:bg-slate-50 transition">
                <div class="flex items-start justify-between gap-2">
                  <div class="flex-1 min-w-0">
                    <div class="font-medium text-sm truncate">${order.nama_pasien}</div>
                    <div class="text-xs text-slate-500 truncate">${order.rs_pemesan}</div>
                    <div class="mt-1 flex items-center gap-2 text-xs">
                      <span class="px-2 py-0.5 rounded ${order.is_urgent ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'}">
                        ${order.gol_darah} - ${order.produk}
                      </span>
                      <span class="text-slate-600">${order.jumlah_kantong} kantong</span>
                    </div>
                  </div>
                  <div class="text-xs text-slate-400 whitespace-nowrap">${order.created_at}</div>
                </div>
                ${order.is_urgent ? '<div class="mt-1 text-xs text-red-600 font-medium">🔴 URGENT</div>' : ''}
              </a>
            `).join('');
            } else {
              list.innerHTML = '<div class="p-4 text-center text-slate-400 text-sm">Tidak ada notifikasi baru</div>';
            }
          })
          .catch(error => {
            console.error('Error fetching notifications:', error);
          });
      }

      // Request notification permission on load
      if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
      }

      // Initial fetch
      fetchNotifications();

      // Poll every 15 seconds
      setInterval(fetchNotifications, 15000);
    })();

    // ===== Prevent Back After Logout =====
    (function() {
      // Prevent browser back button after logout
      window.history.pushState(null, "", window.location.href);
      window.onpopstate = function() {
        window.history.pushState(null, "", window.location.href);
      };

      // Check if session is valid on page load/focus
      window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
          // Page was loaded from cache, verify session
          fetch('{{ route('admin.dashboard') }}', {
              method: 'GET',
              headers: {
                'X-Requested-With': 'XMLHttpRequest'
              }
            })
            .then(response => {
              if (response.redirected || response.status === 401) {
                window.location.href = '{{ route('admin.login') }}';
              }
            })
            .catch(() => {
              window.location.href = '{{ route('admin.login') }}';
            });
        }
      });
    })();

    // ===== User Menu Dropdown =====
    (function() {
      const userMenuButton = document.getElementById('userMenuButton');
      const userMenuDropdown = document.getElementById('userMenuDropdown');
      let isUserMenuOpen = false;

      // Toggle dropdown
      userMenuButton.addEventListener('click', function(e) {
        e.stopPropagation();
        isUserMenuOpen = !isUserMenuOpen;
        userMenuDropdown.classList.toggle('hidden', !isUserMenuOpen);
      });

      // Close dropdown when clicking outside
      document.addEventListener('click', function(e) {
        if (isUserMenuOpen && !userMenuDropdown.contains(e.target) && e.target !== userMenuButton) {
          isUserMenuOpen = false;
          userMenuDropdown.classList.add('hidden');
        }
      });
    })();
  </script>

  {{-- Stack untuk scripts tambahan dari child views --}}
  @stack('scripts')

</body>

</html>
