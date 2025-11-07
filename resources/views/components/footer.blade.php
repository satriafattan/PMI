@props([
    'bg' => 'bg-white',
    'bleed' => false,
    'links' => [
        ['label' => 'Beranda', 'href' => url('/')],
        ['label' => 'Pemesanan', 'href' => url('/pemesanan')],
        ['label' => 'Stok Darah', 'href' => url('/stok')],
        ['label' => 'Tentang Kami', 'href' => url('/about')],
        ['label' => 'Penjadwalan Event', 'href' => url('/jadwal-event')],
        ['label' => 'Kontak', 'href' => 'https://wa.me/628987311125'],
    ],
    'emergencyContact' => '119',
    'address' => 'Jl. Dr. Sam Ratulangi No.105, Penengahan, Kec. Tj. Karang Pusat, Kota Bandar Lampung',
    'operationalHours' => [
        'weekday' => '08:00 - 17:00',
        'weekend' => '08:00 - 15:00',
    ],
    'socialMedia' => [
        ['platform' => 'Instagram', 'icon' => 'fab fa-instagram', 'href' => '#'],
        ['platform' => 'Facebook', 'icon' => 'fab fa-facebook', 'href' => '#'],
        ['platform' => 'Twitter', 'icon' => 'fab fa-twitter', 'href' => '#'],
    ],
])

@php
  $bleedClass = $bleed ? 'relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen' : '';
@endphp

{{-- FOOTER – Enhanced minimal style, full width --}}
{{-- Enhanced Footer with More Information --}}
<footer class="{{ $bg }} {{ $bleedClass }} w-full border-t border-slate-200 shadow-inner">
  <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-12 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
      {{-- Column 1: Main Info --}}
      <div class="flex flex-col gap-4">
        <a href="{{ url('/') }}"
           class="flex items-center gap-3 text-slate-800 transition-opacity duration-200 hover:opacity-80">
          <img src="{{ asset('images/LOGO NAV.png') }}"
               alt="Logo PMI"
               class="h-32 w-32 object-contain">
          <span class="text-lg font-semibold tracking-tight">PMI Provinsi Lampung</span>
        </a>
        <p class="mt-2 text-sm text-slate-600">Unit Donor Darah PMI Provinsi Lampung berkomitmen untuk memberikan
          pelayanan terbaik dalam penyediaan darah yang aman dan berkualitas.</p>
      </div>

      {{-- Column 2: Quick Links --}}
      <div class="flex flex-col gap-4">
        <h3 class="text-lg font-semibold text-slate-800">Menu Utama</h3>
        <nav class="flex flex-col gap-2">
          @foreach ($links as $link)
            <a href="{{ $link['href'] }}"
               class="text-sm text-slate-600 transition-colors duration-200 hover:text-red-600">
              {{ $link['label'] }}
            </a>
          @endforeach
        </nav>
      </div>

      {{-- Column 3: Contact Info --}}
      <div class="flex flex-col gap-4">
        <h3 class="text-lg font-semibold text-slate-800">Informasi Kontak</h3>
        <div class="flex flex-col gap-3 text-sm text-slate-600">
          <p class="flex items-start gap-2">
            <svg class="mt-0.5 h-5 w-5 text-red-600"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ $address }}
          </p>
          <p class="flex items-center gap-2">
            <svg class="h-5 w-5 text-red-600"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            Emergency: {{ $emergencyContact }}
          </p>
        </div>
      </div>

      {{-- Column 4: Operation Hours & Social Media --}}
      <div class="flex flex-col gap-4">
        <h3 class="text-lg font-semibold text-slate-800">Jam Operasional</h3>
        <div class="flex flex-col gap-2 text-sm text-slate-600">
          <p>Senin - Jumat: {{ $operationalHours['weekday'] }}</p>
          <p>Sabtu - Minggu: {{ $operationalHours['weekend'] }}</p>
        </div>

        <h3 class="mt-4 text-lg font-semibold text-slate-800">Media Sosial</h3>
        <div class="flex gap-4">
          @foreach ($socialMedia as $social)
            <a href="{{ $social['href'] }}"
               class="text-slate-600 transition-colors duration-200 hover:text-red-600">
              <i class="{{ $social['icon'] }} text-xl"></i>
            </a>
          @endforeach
        </div>
      </div>
    </div>

    <div class="h-px w-full bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>

    {{-- Copyright & Additional Info --}}
    <div class="mt-8 flex flex-col items-center justify-between gap-4 text-sm text-slate-500 md:flex-row">
      <p class="text-center md:text-left">© {{ date('Y') }} UDD PMI Provinsi Lampung. All rights reserved.</p>
      <div class="flex gap-6">
        <a href="#"
           class="transition-colors duration-200 hover:text-red-600">Privacy Policy</a>
        <a href="#"
           class="transition-colors duration-200 hover:text-red-600">Terms of Service</a>
      </div>
    </div>
  </div>
</footer>
