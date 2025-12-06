{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('title', 'SIMPHONY – Sistem Informasi Pemesanan dan Inventori')

@push('styles')
  <style>
    /* Hero Image Gallery Styles */
    .hero-gallery {
      position: relative;
    }

    .hero-image {
      opacity: 0;
      transition: opacity 0.5s ease-in-out;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }

    .hero-image.active {
      opacity: 1;
      position: relative;
    }

    .gallery-nav-btn {
      width: 35px;
      height: 35px;
      background: rgba(220, 38, 38, 0.95);
      border-radius: 50%;
      transition: all 0.3s ease;
      z-index: 10;
    }

    @media (min-width: 640px) {
      .gallery-nav-btn {
        width: 45px;
        height: 45px;
      }
    }

    @media (min-width: 1024px) {
      .gallery-nav-btn {
        width: 50px;
        height: 50px;
      }
    }

    .gallery-nav-btn:hover {
      background: rgba(220, 38, 38, 1);
      transform: scale(1.1);
      box-shadow: 0 8px 16px rgba(220, 38, 38, 0.4);
    }

    .gallery-dot {
      width: 8px;
      height: 8px;
      background: white;
      opacity: 0.6;
      border-radius: 50%;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    @media (min-width: 640px) {
      .gallery-dot {
        width: 10px;
        height: 10px;
      }
    }

    .gallery-dot.active {
      opacity: 1;
      width: 24px;
      border-radius: 6px;
    }

    @media (min-width: 640px) {
      .gallery-dot.active {
        width: 32px;
      }
    }
  </style>
@endpush

@section('content')
  <x-navbar />

  {{-- HERO --}}
  <section id="beranda"
           class="relative overflow-hidden bg-gradient-to-br from-red-700 via-red-600 to-rose-500">
    {{-- Animated background elements --}}
    <div class="pointer-events-none absolute inset-0">
      <div class="absolute -left-24 -top-24 h-96 w-96 animate-pulse rounded-full bg-white/10 blur-3xl"></div>
      <div class="absolute -bottom-32 -right-32 h-96 w-96 animate-pulse rounded-full bg-rose-300/20 blur-3xl"
           style="animation-delay: 1s;"></div>
      <div class="absolute left-1/2 top-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2 animate-pulse rounded-full bg-white/5 blur-3xl"
           style="animation-delay: 2s;"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-12 lg:px-8 lg:py-16">
      <div class="grid items-center gap-6 lg:grid-cols-2 lg:gap-10">
        {{-- Left Content --}}
        <div class="order-2 text-white lg:order-1">
          <h1 class="text-2xl font-extrabold leading-tight tracking-tight sm:text-3xl md:text-4xl lg:text-5xl">
            Selamatkan Nyawa
            <span class="block bg-gradient-to-r from-white to-red-100 bg-clip-text text-transparent">Bersama Kami</span>
          </h1>

          <p class="mt-3 text-sm leading-relaxed text-white/90 sm:mt-4 sm:text-base lg:text-lg">
            Bergabunglah dengan misi mulia untuk menyediakan darah berkualitas bagi yang membutuhkan.
            <strong class="font-semibold text-white">Setiap kantong darah dapat menyelamatkan hingga 3 nyawa.</strong>
          </p>

          {{-- CTA Buttons --}}
          <div class="mt-4 flex flex-col gap-2 sm:mt-6 sm:flex-row sm:flex-wrap sm:gap-3">
            <a href="{{ url('/pemesanan') }}"
               class="group inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-red-700 shadow-xl transition hover:scale-105 hover:bg-red-50 hover:shadow-2xl sm:px-5 sm:py-3">
              <svg class="h-4 w-4 transition group-hover:rotate-12"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              Ajukan Permintaan Darah
            </a>
            <a href="{{ url('/about') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-white/30 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/10 sm:px-5 sm:py-3">
              Pelajari Lebih Lanjut
              <svg class="h-4 w-4"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
        </div>

        {{-- Right Content - Hero Image Gallery --}}
        <div class="relative order-1 lg:order-2">
          <div
               class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-white/20 to-rose-300/20 blur-xl sm:-inset-2 sm:blur-2xl lg:-inset-4">
          </div>
          <div
               class="relative overflow-hidden rounded-xl bg-white/20 shadow-2xl ring-1 ring-white/30 backdrop-blur-xl sm:rounded-2xl lg:rounded-3xl">
            <div class="hero-gallery relative aspect-[16/10] sm:aspect-[4/3]">
              {{-- Images --}}
              <img src="{{ asset('images/hero-banner.jpg') }}"
                   alt="SIMPHONY - Donor Darah"
                   class="hero-image active h-full w-full object-cover"
                   data-index="0">
              <img src="{{ asset('images/hero-banner2.jpg') }}"
                   alt="SIMPHONY - Pelayanan Kesehatan"
                   class="hero-image h-full w-full object-cover"
                   data-index="1">
              <img src="{{ asset('images/hero-banner3.jpg') }}"
                   alt="SIMPHONY - Kegiatan Donor"
                   class="hero-image h-full w-full object-cover"
                   data-index="2">
              <img src="{{ asset('images/Card.jpg') }}"
                   alt="SIMPHONY - Unit Donor Darah"
                   class="hero-image h-full w-full object-cover"
                   data-index="3">

              {{-- Navigation Buttons --}}
              <button id="prevBtn"
                      class="gallery-nav-btn absolute left-2 top-1/2 flex -translate-y-1/2 items-center justify-center text-white sm:left-4"
                      aria-label="Previous Image">
                <svg class="h-5 w-5 sm:h-6 sm:w-6"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
              </button>
              <button id="nextBtn"
                      class="gallery-nav-btn absolute right-2 top-1/2 flex -translate-y-1/2 items-center justify-center text-white sm:right-4"
                      aria-label="Next Image">
                <svg class="h-5 w-5 sm:h-6 sm:w-6"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7" />
                </svg>
              </button>

              {{-- Pagination Dots --}}
              <div class="absolute bottom-3 left-1/2 z-10 flex -translate-x-1/2 gap-2 sm:bottom-4 sm:gap-3">
                <button class="gallery-dot active"
                        data-index="0"
                        aria-label="Show image 1"></button>
                <button class="gallery-dot"
                        data-index="1"
                        aria-label="Show image 2"></button>
                <button class="gallery-dot"
                        data-index="2"
                        aria-label="Show image 3"></button>
                <button class="gallery-dot"
                        data-index="3"
                        aria-label="Show image 4"></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- STOK PRC - Enhanced Design --}}
  <section id="stok"
           class="relative bg-gradient-to-b from-white to-slate-50 py-12 sm:py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      {{-- Section Header --}}
      <div class="mb-8 flex flex-col items-start justify-between gap-4 sm:mb-10 sm:flex-row sm:items-end">
        <div class="max-w-2xl">
          <div
               class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 sm:px-4 sm:py-1.5 sm:text-sm">
            <span class="relative flex h-2 w-2">
              <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex h-2 w-2 rounded-full bg-red-600"></span>
            </span>
            Live Update
          </div>
          <h2 class="mt-2 text-2xl font-extrabold text-slate-900 sm:mt-3 sm:text-3xl lg:text-4xl">
            Persediaan Darah Real-time
          </h2>
          <p class="mt-1.5 text-sm text-slate-600 sm:mt-2 sm:text-base">
            Pantau ketersediaan stok darah per golongan
          </p>
        </div>
        <a href="{{ url('/stok') }}"
           class="group inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-red-600 bg-white px-4 py-2 text-sm font-semibold text-red-600 shadow-sm transition hover:bg-red-600 hover:text-white hover:shadow-xl sm:w-auto sm:px-5 sm:py-2.5">
          Lihat Detail Lengkap
          <svg class="h-4 w-4 transition group-hover:translate-x-1"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 7l5 5m0 0l-5 5m5-5H6" />
          </svg>
        </a>
      </div>

      {{-- Stock Cards --}}
      @php
        use App\Helpers\StokHelper;
        $stok = [
            ['gol' => 'A', 'jumlah' => $stokA, 'rhesus' => '+'],
            ['gol' => 'B', 'jumlah' => $stokB, 'rhesus' => '+'],
            ['gol' => 'O', 'jumlah' => $stokO, 'rhesus' => '+'],
            ['gol' => 'AB', 'jumlah' => $stokAB, 'rhesus' => '+'],
        ];
        $totalStok = array_sum(array_column($stok, 'jumlah'));
      @endphp

      {{-- Total Overview --}}
      <div
           class="mb-6 rounded-xl border border-slate-200 bg-gradient-to-r from-red-50 to-rose-50 p-4 shadow-sm sm:mb-8 sm:rounded-2xl sm:p-6">
        <div class="flex flex-col items-center justify-between gap-3 sm:flex-row sm:gap-4">
          <div class="flex items-center gap-3 sm:gap-4">
            <div
                 class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-red-50 to-rose-100 shadow-lg sm:h-16 sm:w-16 sm:rounded-2xl">
              <svg class="h-7 w-7 text-red-600 sm:h-9 sm:w-9"
                   fill="currentColor"
                   viewBox="0 0 24 24">
                <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z" />
              </svg>
            </div>
            <div class="text-center sm:text-left">
              <div class="text-xs font-medium text-slate-600 sm:text-sm">Total Stok Tersedia (PRC)</div>
              <div class="text-2xl font-extrabold text-slate-900 sm:text-3xl lg:text-4xl"
                   data-counter>{{ number_format($totalStok) }}</div>
            </div>
          </div>
          <div class="flex items-center gap-2 text-xs sm:text-sm">
            <span class="text-slate-500">Terakhir diperbarui:</span>
            <span id="lastUpdated"
                  class="font-semibold text-slate-900">{{ $lastUpdated }}</span>
          </div>
        </div>
      </div>

      {{-- Blood Type Grid --}}
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">
        @foreach ($stok as $it)
          @php
            [$label, $cls] = StokHelper::badgeStatus($it['jumlah']);
            $statusColor = $label === 'Aman' ? 'emerald' : ($label === 'Perhatian' ? 'amber' : 'red');
            $percentage = min(100, ($it['jumlah'] / 1500) * 100);
          @endphp

          <div class="group relative"
               data-golongan="{{ $it['gol'] }}">
            {{-- Glow effect --}}
            <div
                 class="from-{{ $statusColor }}-500/30 to-{{ $statusColor }}-600/20 absolute -inset-0.5 rounded-2xl bg-gradient-to-br opacity-0 blur transition duration-300 group-hover:opacity-100 sm:rounded-3xl">
            </div>

            <div
                 class="group-hover:border-{{ $statusColor }}-200 relative h-full rounded-2xl border-2 border-slate-200 bg-white p-4 shadow-lg transition duration-300 group-hover:shadow-2xl sm:rounded-3xl sm:p-6">
              {{-- Header --}}
              <div class="flex items-start justify-between">
                <div class="flex items-center gap-2 sm:gap-3">
                  <div
                       class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-red-50 to-rose-100 shadow-lg sm:h-14 sm:w-14 sm:rounded-2xl">
                    <svg class="h-7 w-7 text-red-600 sm:h-8 sm:w-8"
                         fill="currentColor"
                         viewBox="0 0 24 24">
                      <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z" />
                    </svg>
                  </div>
                  <div>
                    <div class="rounded-lg bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-700 sm:px-2.5 sm:py-1">
                      PRC</div>
                    <div class="mt-0.5 text-xs text-slate-500 sm:mt-1">Packed Red Cell</div>
                  </div>
                </div>
                <span data-status
                      class="bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 ring-{{ $statusColor }}-200 rounded-full px-2 py-1 text-xs font-bold ring-1 sm:px-3 sm:py-1.5">
                  {{ $label }}
                </span>
              </div>

              {{-- Counter & Blood Type --}}
              <div class="mt-4 flex items-end justify-between sm:mt-6">
                <div class="flex-1">
                  <div class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl"
                       data-counter>
                    {{ number_format($it['jumlah'], 0, ',', '.') }}
                  </div>
                  <div class="mt-0.5 text-xs font-medium text-slate-500 sm:mt-1 sm:text-sm">Unit tersedia</div>
                </div>
                <div class="flex flex-col items-center">
                  <div class="flex items-baseline">
                    <span class="text-3xl font-black text-red-600 sm:text-4xl">{{ $it['gol'] }}</span>
                    <span class="ml-0.5 text-xl font-bold text-red-500 sm:ml-1 sm:text-2xl">{{ $it['rhesus'] }}</span>
                  </div>
                  <div class="mt-0.5 text-xs font-medium text-slate-400 sm:mt-1">Golongan</div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Legend --}}
      <div
           class="mt-8 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex items-center gap-2 text-sm text-slate-600">
          <span class="font-semibold">Status Ketersediaan:</span>
        </div>
        <div class="flex flex-wrap items-center gap-3 text-xs">
          <span
                class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 font-semibold text-emerald-700 ring-1 ring-emerald-200">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            Aman (≥50)
          </span>
          <span
                class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1.5 font-semibold text-amber-700 ring-1 ring-amber-200">
            <span class="h-2 w-2 rounded-full bg-amber-500"></span>
            Perhatian (10-49)
          </span>
          <span
                class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1.5 font-semibold text-red-700 ring-1 ring-red-200">
            <span class="h-2 w-2 rounded-full bg-red-500"></span>
            Kritis (1-9)
          </span>
          <span
                class="inline-flex items-center gap-2 rounded-full bg-slate-50 px-3 py-1.5 font-semibold text-slate-700 ring-1 ring-slate-200">
            <span class="h-2 w-2 rounded-full bg-slate-500"></span>
            Habis (0)
          </span>
        </div>
      </div>
    </div>
  </section>

  {{-- MENGAPA MEMILIH - Enhanced Design --}}
  <section id="about"
           class="relative overflow-hidden bg-slate-50 py-12 sm:py-16 lg:py-20">
    {{-- Background Pattern --}}
    <div class="pointer-events-none absolute inset-0 opacity-40">
      <div class="absolute right-0 top-0 h-96 w-96 rounded-full bg-red-100 blur-3xl"></div>
      <div class="absolute bottom-0 left-0 h-96 w-96 rounded-full bg-rose-100 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid items-center gap-6 sm:gap-8 lg:grid-cols-2 lg:gap-12">
        {{-- Left Content --}}
        <div class="lg:pt-0">
          <h2 class="text-2xl font-extrabold text-slate-900 sm:text-3xl lg:text-4xl">
            Mengapa Memilih
            <span class="bg-gradient-to-r from-red-600 to-rose-500 bg-clip-text text-transparent">SIMPHONY</span>
          </h2>

          <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:mt-3 sm:text-base">
            Sistem Informasi Pemesanan dan Inventori yang dirancang untuk menyelaraskan proses pemesanan darah,
            pengelolaan stok, dan verifikasi dengan standar internasional untuk keselamatan Anda.
          </p>

          <ul class="mt-4 space-y-3 sm:mt-6 sm:space-y-4">
            <li class="group flex items-start gap-2 sm:gap-3">
              <div
                   class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-red-600 to-red-500 text-white shadow-lg transition group-hover:scale-110 sm:h-10 sm:w-10 sm:rounded-2xl">
                <svg class="h-4 w-4 sm:h-5 sm:w-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
              </div>
              <div class="flex-1">
                <h3 class="text-sm font-bold text-slate-900 sm:text-base">Standar Keamanan Tinggi</h3>
                <p class="mt-0.5 text-xs text-slate-600 sm:mt-1 sm:text-sm">
                  Seluruh proses screening dan pengolahan darah mengikuti protokol WHO dan standar internasional untuk
                  menjamin keamanan maksimal.
                </p>
              </div>
            </li>

            <li class="group flex items-start gap-2 sm:gap-3">
              <div
                   class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-blue-500 text-white shadow-lg transition group-hover:scale-110 sm:h-10 sm:w-10 sm:rounded-2xl">
                <svg class="h-4 w-4 sm:h-5 sm:w-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <div class="flex-1">
                <h3 class="text-sm font-bold text-slate-900 sm:text-base">Teknologi Terdepan</h3>
                <p class="mt-0.5 text-xs text-slate-600 sm:mt-1 sm:text-sm">
                  Sistem manajemen digital dan peralatan medis terkini untuk keandalan dan traceability yang
                  sempurna.
                </p>
              </div>
            </li>

            <li class="group flex items-start gap-2 sm:gap-3">
              <div
                   class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-green-600 to-green-500 text-white shadow-lg transition group-hover:scale-110 sm:h-10 sm:w-10 sm:rounded-2xl">
                <svg class="h-4 w-4 sm:h-5 sm:w-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="flex-1">
                <h3 class="text-sm font-bold text-slate-900 sm:text-base">Layanan 24/7</h3>
                <p class="mt-0.5 text-xs text-slate-600 sm:mt-1 sm:text-sm">
                  Tim medis profesional dan berpengalaman siap melayani kebutuhan darurat Anda kapan saja, tanpa henti.
                </p>
              </div>
            </li>
          </ul>
        </div>

        {{-- Right Content - Image & Stats --}}
        <div class="relative">
          {{-- Main Image Card --}}
          <div class="relative">
            <div
                 class="absolute -inset-2 rounded-2xl bg-gradient-to-r from-red-200/50 to-rose-200/50 blur-2xl sm:-inset-4 sm:rounded-3xl">
            </div>
            <div class="relative overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200 sm:rounded-3xl">
              <div class="aspect-[4/3] bg-gradient-to-br from-slate-50 to-slate-100">
                {{-- Google Maps Embed - SIMPHONY Unit Donor Darah --}}
                <iframe src="https://maps.google.com/maps?q=UDD+PMI+Provinsi+Lampung,Jl.+Dr.+Sam+Ratulangi+No.105,+Penengahan,+Kec.+Tj.+Karang+Pusat,+Kota+Bandar+Lampung&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="h-full w-full">
                </iframe>
              </div>

              {{-- Stats Overlay --}}
              <div class="border-t border-slate-100 bg-white p-4 sm:p-6">
                <div class="grid grid-cols-3 gap-2 text-center sm:gap-4">
                  <div>
                    <div class="text-xl font-bold text-red-600 sm:text-2xl">15+</div>
                    <div class="mt-0.5 text-xs text-slate-600 sm:mt-1">Tahun Pengalaman</div>
                  </div>
                  <div>
                    <div class="text-xl font-bold text-red-600 sm:text-2xl">50K+</div>
                    <div class="mt-0.5 text-xs text-slate-600 sm:mt-1">Darah Terkelola</div>
                  </div>
                  <div>
                    <div class="text-xl font-bold text-red-600 sm:text-2xl">100%</div>
                    <div class="mt-0.5 text-xs text-slate-600 sm:mt-1">Tersertifikasi</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Enhanced JavaScript with smooth animations --}}
  @push('scripts')
    <script>
      // Hero Image Gallery
      document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('.hero-image');
        const dots = document.querySelectorAll('.gallery-dot');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        let currentIndex = 0;

        function showImage(index) {
          // Remove active class from all images and dots
          images.forEach(img => img.classList.remove('active'));
          dots.forEach(dot => dot.classList.remove('active'));

          // Add active class to current image and dot
          images[index].classList.add('active');
          dots[index].classList.add('active');
          currentIndex = index;
        }

        // Next button
        if (nextBtn) {
          nextBtn.addEventListener('click', () => {
            const nextIndex = (currentIndex + 1) % images.length;
            showImage(nextIndex);
          });
        }

        // Previous button
        if (prevBtn) {
          prevBtn.addEventListener('click', () => {
            const prevIndex = (currentIndex - 1 + images.length) % images.length;
            showImage(prevIndex);
          });
        }

        // Dots navigation
        dots.forEach(dot => {
          dot.addEventListener('click', () => {
            const index = parseInt(dot.getAttribute('data-index'));
            showImage(index);
          });
        });
      });

      // Stock update function
      async function updateStokDarah() {
        try {
          const response = await fetch('/api/stok-golongan');

          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }

          const data = await response.json();

          // Update each blood type stock with animation
          Object.entries(data.stok).forEach(([gol, jumlah]) => {
            const card = document.querySelector(`[data-golongan="${gol}"]`);
            if (card) {
              const counter = card.querySelector('[data-counter]');
              const status = card.querySelector('[data-status]');

              // Animate counter
              if (counter) {
                const currentValue = parseInt(counter.textContent.replace(/\D/g, '')) || 0;
                animateValue(counter, currentValue, jumlah, 1000);
              }

              // Update status badge
              if (status) {
                const newStatus = jumlah === 0 ? ['Habis', 'slate'] :
                  jumlah < 10 ? ['Kritis', 'red'] :
                  jumlah < 50 ? ['Perhatian', 'amber'] : ['Aman', 'emerald'];
                status.textContent = newStatus[0];
                status.className =
                  `rounded-full px-2 sm:px-3 py-1 sm:py-1.5 text-xs font-bold ring-1 bg-${newStatus[1]}-100 text-${newStatus[1]}-700 ring-${newStatus[1]}-200`;
              }
            }
          });

          // Update last updated time
          const lastUpdated = document.getElementById('lastUpdated');
          if (lastUpdated && data.lastUpdated) {
            lastUpdated.textContent = data.lastUpdated;
          }

        } catch (error) {
          console.error('Error updating stock:', error);
        }
      }

      // Animate number counter
      function animateValue(element, start, end, duration) {
        if (!element) return;

        const range = end - start;
        const increment = range / (duration / 16);
        let current = start;

        const timer = setInterval(() => {
          current += increment;
          if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
            element.textContent = new Intl.NumberFormat('id-ID').format(end);
            clearInterval(timer);
          } else {
            element.textContent = new Intl.NumberFormat('id-ID').format(Math.floor(current));
          }
        }, 16);
      }

      // Stock and animations initialization
      document.addEventListener('DOMContentLoaded', () => {
        // Initial stock update
        updateStokDarah();

        // Counter animation with Intersection Observer
        const counters = document.querySelectorAll('[data-counter]');
        const easeOutQuart = t => 1 - Math.pow(1 - t, 4);

        const counterObserver = new IntersectionObserver(entries => {
          entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const numeric = el.textContent.replace(/[^0-9]/g, '');
            const target = parseInt(numeric, 10) || 0;

            if (target === 0) return;

            let start = null;

            const animate = (timestamp) => {
              if (!start) start = timestamp;
              const progress = Math.min(1, (timestamp - start) / 1200);
              const value = Math.floor(easeOutQuart(progress) * target);
              el.textContent = value.toLocaleString('id-ID');
              if (progress < 1) requestAnimationFrame(animate);
            };

            requestAnimationFrame(animate);
            counterObserver.unobserve(el);
          });
        }, {
          threshold: 0.5
        });

        counters.forEach(el => counterObserver.observe(el));

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
          anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
              e.preventDefault();
              const target = document.querySelector(href);
              if (target) {
                target.scrollIntoView({
                  behavior: 'smooth',
                  block: 'start'
                });
              }
            }
          });
        });

        // Add parallax effect to hero background
        let ticking = false;
        window.addEventListener('scroll', () => {
          if (!ticking) {
            window.requestAnimationFrame(() => {
              const scrolled = window.pageYOffset;
              const parallax = document.querySelector('#beranda .pointer-events-none');
              if (parallax && scrolled < 800) {
                parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
              }
              ticking = false;
            });
            ticking = true;
          }
        });

        // Add fade-in animation for sections
        const sections = document.querySelectorAll('section');
        const sectionObserver = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.style.opacity = '1';
              entry.target.style.transform = 'translateY(0)';
            }
          });
        }, {
          threshold: 0.1
        });

        sections.forEach(section => {
          section.style.opacity = '0';
          section.style.transform = 'translateY(20px)';
          section.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
          sectionObserver.observe(section);
        });
      });
    </script>
  @endpush

  <x-footer bg="bg-slate-50" />
@endsection
