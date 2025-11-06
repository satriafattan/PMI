{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('title', 'UDD PMI Provinsi Lampung – Pemesanan Darah')

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

    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
      <div class="grid items-center gap-12 lg:grid-cols-2">
        {{-- Left Content --}}
        <div class="text-white">
          <div
               class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-medium ring-1 ring-white/20 backdrop-blur-sm">
            <span class="relative flex h-2 w-2">
              <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-75"></span>
              <span class="relative inline-flex h-2 w-2 rounded-full bg-white"></span>
            </span>
            Sistem Real-time 24/7
          </div>

          <h1 class="mt-6 text-5xl font-extrabold leading-tight tracking-tight sm:text-6xl lg:text-7xl">
            Selamatkan Nyawa
            <span class="block bg-gradient-to-r from-white to-red-100 bg-clip-text text-transparent">Bersama Kami</span>
          </h1>

          <p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/90 sm:text-xl">
            Bergabunglah dengan misi mulia untuk menyediakan darah berkualitas bagi yang membutuhkan.
            <strong class="font-semibold text-white">Setiap tetes darah dapat menyelamatkan hingga 3 nyawa.</strong>
          </p>

          {{-- CTA Buttons --}}
          <div class="mt-8 flex flex-wrap gap-4">
            <a href="{{ url('/pemesanan') }}"
               class="group inline-flex items-center gap-2 rounded-xl bg-white px-6 py-4 font-semibold text-red-700 shadow-xl transition hover:scale-105 hover:bg-red-50 hover:shadow-2xl">
              <svg class="h-5 w-5 transition group-hover:rotate-12"
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
            <a href="#about"
               class="inline-flex items-center gap-2 rounded-xl border-2 border-white/30 bg-white/5 px-6 py-4 font-semibold text-white backdrop-blur-sm transition hover:bg-white/10">
              Pelajari Lebih Lanjut
              <svg class="h-5 w-5"
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

          {{-- Stats --}}
          <div class="mt-12 grid grid-cols-3 gap-6 sm:gap-8">
            <div class="group">
              <div
                   class="mb-3 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-2xl ring-1 ring-white/20 backdrop-blur-sm transition group-hover:scale-110 group-hover:bg-white/20">
                🩸
              </div>
              <div class="text-3xl font-extrabold tracking-tight">15,000+</div>
              <div class="mt-1 text-sm text-white/80">Donor Aktif</div>
            </div>
            <div class="group">
              <div
                   class="mb-3 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-2xl ring-1 ring-white/20 backdrop-blur-sm transition group-hover:scale-110 group-hover:bg-white/20">
                ❤️
              </div>
              <div class="text-3xl font-extrabold tracking-tight">50,000+</div>
              <div class="mt-1 text-sm text-white/80">Nyawa Terselamatkan</div>
            </div>
            <div class="group">
              <div
                   class="mb-3 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-2xl ring-1 ring-white/20 backdrop-blur-sm transition group-hover:scale-110 group-hover:bg-white/20">
                ⏱️
              </div>
              <div class="text-3xl font-extrabold tracking-tight">24/7</div>
              <div class="mt-1 text-sm text-white/80">Layanan Darurat</div>
            </div>
          </div>
        </div>

        {{-- Right Content - Hero Image --}}
        <div class="relative">
          <div class="absolute -inset-4 rounded-3xl bg-gradient-to-r from-white/20 to-rose-300/20 blur-2xl"></div>
          <div class="relative">
            <div class="overflow-hidden rounded-3xl bg-white/20 shadow-2xl ring-1 ring-white/30 backdrop-blur-xl">
              <div class="aspect-[4/3] bg-gradient-to-br from-red-100/20 to-rose-200/20 p-8">
                {{-- Medical illustration placeholder --}}
                <div class="grid h-full w-full place-items-center text-center">
                  <div>
                    <svg class="mx-auto h-32 w-32 text-white/90"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                      <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <div class="mt-4 text-2xl font-bold text-white">Profesional Medis Terpercaya</div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Floating Card --}}
            <div class="absolute -bottom-6 -left-6 rounded-2xl bg-white p-5 shadow-2xl ring-1 ring-slate-200">
              <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100">
                  <svg class="h-6 w-6 text-green-600"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <div>
                  <div class="text-xs text-slate-500">Estimasi Proses</div>
                  <div class="font-bold text-slate-900">&lt; 10 Menit</div>
                </div>
              </div>
            </div>

            {{-- Floating Badge --}}
            <div class="absolute -right-4 top-12 rounded-xl bg-white px-4 py-3 shadow-xl ring-1 ring-slate-200">
              <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                <span class="relative flex h-3 w-3">
                  <span
                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex h-3 w-3 rounded-full bg-green-500"></span>
                </span>
                Online Now
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
      <a href="#stok"
         class="flex flex-col items-center gap-2 text-white/70 transition hover:text-white">
        <span class="text-xs font-medium">Scroll untuk melihat stok</span>
        <svg class="h-6 w-6"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
      </a>
    </div>
  </section>

  {{-- STOK PRC - Enhanced Design --}}
  <section id="stok"
           class="relative bg-gradient-to-b from-white to-slate-50 py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      {{-- Section Header --}}
      <div class="mb-12 flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-end">
        <div class="max-w-2xl">
          <div
               class="inline-flex items-center gap-2 rounded-full bg-red-100 px-4 py-1.5 text-sm font-semibold text-red-700">
            <span class="relative flex h-2 w-2">
              <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex h-2 w-2 rounded-full bg-red-600"></span>
            </span>
            Live Update
          </div>
          <h2 class="mt-4 text-4xl font-extrabold text-slate-900 sm:text-5xl">
            Persediaan Darah Real-time
          </h2>
          <p class="mt-3 text-lg text-slate-600">
            Pantau ketersediaan stok darah per golongan dengan update otomatis setiap 30 detik
          </p>
        </div>
        <a href="{{ url('/stok') }}"
           class="group inline-flex items-center gap-2 rounded-xl border-2 border-red-600 bg-white px-6 py-3 font-semibold text-red-600 shadow-sm transition hover:bg-red-600 hover:text-white hover:shadow-xl">
          Lihat Detail Lengkap
          <svg class="h-5 w-5 transition group-hover:translate-x-1"
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
      <div class="mb-8 rounded-2xl border border-slate-200 bg-gradient-to-r from-red-50 to-rose-50 p-6 shadow-sm">
        <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
          <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-red-600 text-3xl text-white shadow-lg">
              🩸
            </div>
            <div>
              <div class="text-sm font-medium text-slate-600">Total Stok Tersedia (PRC)</div>
              <div class="text-4xl font-extrabold text-slate-900"
                   data-counter>{{ number_format($totalStok) }}</div>
            </div>
          </div>
          <div class="flex items-center gap-2 text-sm">
            <span class="text-slate-500">Terakhir diperbarui:</span>
            <span id="lastUpdated"
                  class="font-semibold text-slate-900">{{ $lastUpdated }}</span>
          </div>
        </div>
      </div>

      {{-- Blood Type Grid --}}
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($stok as $it)
          @php
            [$label, $cls] = StokHelper::badgeStatus($it['jumlah']);
            $statusColor = $label === 'Aman' ? 'emerald' : ($label === 'Waspada' ? 'amber' : 'red');
            $percentage = min(100, ($it['jumlah'] / 1500) * 100);
          @endphp

          <div class="group relative"
               data-golongan="{{ $it['gol'] }}">
            {{-- Glow effect --}}
            <div
                 class="from-{{ $statusColor }}-500/30 to-{{ $statusColor }}-600/20 absolute -inset-0.5 rounded-3xl bg-gradient-to-br opacity-0 blur transition duration-300 group-hover:opacity-100">
            </div>

            <div
                 class="group-hover:border-{{ $statusColor }}-200 relative h-full rounded-3xl border-2 border-slate-200 bg-white p-6 shadow-lg transition duration-300 group-hover:shadow-2xl">
              {{-- Header --}}
              <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                  <div
                       class="grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-red-600 to-red-500 text-2xl text-white shadow-lg ring-4 ring-white">
                    🩸
                  </div>
                  <div>
                    <div class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700">PRC</div>
                    <div class="mt-1 text-xs text-slate-500">Packed Red Cell</div>
                  </div>
                </div>
                <span data-status
                      class="bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 ring-{{ $statusColor }}-200 rounded-full px-3 py-1.5 text-xs font-bold ring-1">
                  {{ $label }}
                </span>
              </div>

              {{-- Counter & Blood Type --}}
              <div class="mt-6 flex items-end justify-between">
                <div class="flex-1">
                  <div class="text-5xl font-extrabold tracking-tight text-slate-900"
                       data-counter>
                    {{ number_format($it['jumlah'], 0, ',', '.') }}
                  </div>
                  <div class="mt-1 text-sm font-medium text-slate-500">Unit tersedia</div>
                </div>
                <div class="flex flex-col items-center">
                  <div class="flex items-baseline">
                    <span class="text-4xl font-black text-red-600">{{ $it['gol'] }}</span>
                    <span class="ml-1 text-2xl font-bold text-red-500">{{ $it['rhesus'] }}</span>
                  </div>
                  <div class="mt-1 text-xs font-medium text-slate-400">Golongan</div>
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
            Aman (≥1000)
          </span>
          <span
                class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1.5 font-semibold text-amber-700 ring-1 ring-amber-200">
            <span class="h-2 w-2 rounded-full bg-amber-500"></span>
            Waspada (300-999)
          </span>
          <span
                class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1.5 font-semibold text-red-700 ring-1 ring-red-200">
            <span class="h-2 w-2 rounded-full bg-red-500"></span>
            Kritis (&lt;300)
          </span>
        </div>
      </div>
    </div>
  </section>

  {{-- MENGAPA MEMILIH - Enhanced Design --}}
  <section id="about"
           class="relative overflow-hidden bg-slate-50 py-20 sm:py-28">
    {{-- Background Pattern --}}
    <div class="pointer-events-none absolute inset-0 opacity-40">
      <div class="absolute right-0 top-0 h-96 w-96 rounded-full bg-red-100 blur-3xl"></div>
      <div class="absolute bottom-0 left-0 h-96 w-96 rounded-full bg-rose-100 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
        {{-- Left Content --}}
        <div>
          <div
               class="inline-flex items-center gap-2 rounded-full bg-red-100 px-4 py-1.5 text-sm font-semibold text-red-700">
            <svg class="h-4 w-4"
                 fill="currentColor"
                 viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            Kepercayaan & Kualitas
          </div>

          <h2 class="mt-4 text-4xl font-extrabold text-slate-900 sm:text-5xl">
            Mengapa Memilih
            <span class="bg-gradient-to-r from-red-600 to-rose-500 bg-clip-text text-transparent">UDD PMI</span>
          </h2>

          <p class="mt-4 text-lg leading-relaxed text-slate-600">
            Kami berkomitmen memberikan pelayanan terbaik dalam pengelolaan darah dengan standar internasional dan
            teknologi terdepan untuk keselamatan Anda.
          </p>

          <ul class="mt-8 space-y-5">
            <li class="group flex items-start gap-4">
              <div
                   class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-red-600 to-red-500 text-white shadow-lg transition group-hover:scale-110">
                <svg class="h-6 w-6"
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
                <h3 class="text-lg font-bold text-slate-900">Standar Keamanan Tinggi</h3>
                <p class="mt-1 text-slate-600">
                  Seluruh proses screening dan pengolahan darah mengikuti protokol WHO dan standar internasional untuk
                  menjamin keamanan maksimal.
                </p>
              </div>
            </li>

            <li class="group flex items-start gap-4">
              <div
                   class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-blue-500 text-white shadow-lg transition group-hover:scale-110">
                <svg class="h-6 w-6"
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
                <h3 class="text-lg font-bold text-slate-900">Teknologi Terdepan</h3>
                <p class="mt-1 text-slate-600">
                  Sistem manajemen digital dan peralatan medis terkini untuk keandalan, efisiensi, dan traceability yang
                  sempurna.
                </p>
              </div>
            </li>

            <li class="group flex items-start gap-4">
              <div
                   class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-green-600 to-green-500 text-white shadow-lg transition group-hover:scale-110">
                <svg class="h-6 w-6"
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
                <h3 class="text-lg font-bold text-slate-900">Layanan 24/7</h3>
                <p class="mt-1 text-slate-600">
                  Tim medis profesional dan berpengalaman siap melayani kebutuhan darurat Anda kapan saja, tanpa henti.
                </p>
              </div>
            </li>
          </ul>

          {{-- CTA --}}
          <div class="mt-8 flex flex-wrap gap-4">
            <a href="{{ url('/pemesanan') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-6 py-3 font-semibold text-white shadow-lg transition hover:bg-red-700 hover:shadow-xl">
              Ajukan Permintaan Sekarang
              <svg class="h-5 w-5"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
            </a>
            <a href="{{ url('/about') }}"
               class="inline-flex items-center gap-2 rounded-xl border-2 border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 transition hover:border-red-600 hover:text-red-600">
              Tentang Kami
              <svg class="h-5 w-5"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </a>
          </div>
        </div>

        {{-- Right Content - Image & Stats --}}
        <div class="relative">
          {{-- Main Image Card --}}
          <div class="relative">
            <div class="absolute -inset-4 rounded-3xl bg-gradient-to-r from-red-200/50 to-rose-200/50 blur-2xl"></div>
            <div class="relative overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-slate-200">
              <div class="aspect-[4/3] bg-gradient-to-br from-slate-50 to-slate-100 p-12">
                <div class="flex h-full items-center justify-center">
                  <div class="text-center">
                    <svg class="mx-auto h-40 w-40 text-slate-300"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                      <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="mt-4 text-lg font-semibold text-slate-400">Fasilitas PMI Lampung</p>
                  </div>
                </div>
              </div>

              {{-- Stats Overlay --}}
              <div class="border-t border-slate-100 bg-white p-6">
                <div class="grid grid-cols-3 gap-4 text-center">
                  <div>
                    <div class="text-2xl font-bold text-red-600">15+</div>
                    <div class="mt-1 text-xs text-slate-600">Tahun Pengalaman</div>
                  </div>
                  <div>
                    <div class="text-2xl font-bold text-red-600">50K+</div>
                    <div class="mt-1 text-xs text-slate-600">Darah Terkelola</div>
                  </div>
                  <div>
                    <div class="text-2xl font-bold text-red-600">100%</div>
                    <div class="mt-1 text-xs text-slate-600">Tersertifikasi</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- Floating Badge 1 --}}
          <div class="absolute -left-6 top-12 rounded-2xl bg-white p-4 shadow-2xl ring-1 ring-slate-200">
            <div class="flex items-center gap-3">
              <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-2xl">
                ✓
              </div>
              <div>
                <div class="text-sm font-bold text-slate-900">ISO Certified</div>
                <div class="text-xs text-slate-500">Quality Assured</div>
              </div>
            </div>
          </div>

          {{-- Floating Badge 2 --}}
          <div class="absolute -right-6 bottom-12 rounded-2xl bg-white p-4 shadow-2xl ring-1 ring-slate-200">
            <div class="flex items-center gap-3">
              <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-2xl">
                ⚡
              </div>
              <div>
                <div class="text-sm font-bold text-slate-900">Fast Response</div>
                <div class="text-xs text-slate-500">&lt; 1 Hour</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Enhanced JavaScript with smooth animations --}}
  <script>
    // Stock update function
    async function updateStokDarah() {
      try {
        const response = await fetch('/api/stok-golongan');
        const data = await response.json();

        // Update each blood type stock with animation
        Object.entries(data.stok).forEach(([gol, jumlah]) => {
          const card = document.querySelector(`[data-golongan="${gol}"]`);
          if (card) {
            const counter = card.querySelector('[data-counter]');
            const status = card.querySelector('[data-status]');
            const progress = card.querySelector('[data-progress]');

            // Animate counter
            if (counter) {
              animateValue(counter, parseInt(counter.textContent.replace(/\D/g, '')), jumlah, 1000);
            }

            // Update status badge
            if (status) {
              const newStatus = jumlah < 300 ? ['Kritis', 'red'] :
                jumlah < 1000 ? ['Waspada', 'amber'] : ['Aman', 'emerald'];
              status.textContent = newStatus[0];
              status.className =
                `rounded-full px-3 py-1.5 text-xs font-bold ring-1 bg-${newStatus[1]}-100 text-${newStatus[1]}-700 ring-${newStatus[1]}-200`;
            }

            // Update progress bar
            if (progress) {
              const percentage = Math.min(100, (jumlah / 1500) * 100);
              progress.style.width = percentage + '%';
              progress.setAttribute('data-progress', percentage);
            }
          }
        });

        // Update last updated time
        const lastUpdated = document.getElementById('lastUpdated');
        if (lastUpdated) lastUpdated.textContent = data.lastUpdated;

      } catch (error) {
        console.error('Error updating stock:', error);
      }
    }

    // Animate number counter
    function animateValue(element, start, end, duration) {
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

    document.addEventListener('DOMContentLoaded', () => {
      // Initial stock update
      updateStokDarah();

      // Auto-update every 30 seconds
      setInterval(updateStokDarah, 30000);

      // Counter animation with Intersection Observer
      const counters = document.querySelectorAll('[data-counter]');
      const easeOutQuart = t => 1 - Math.pow(1 - t, 4);

      const counterObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) return;
          const el = entry.target;
          const numeric = el.textContent.replace(/[^0-9]/g, '');
          const target = parseInt(numeric, 10) || 0;
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

      // Progress bar animation
      const progressBars = document.querySelectorAll('[data-progress]');
      const progressObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) return;
          const el = entry.target;
          const targetWidth = el.getAttribute('data-progress');
          el.style.width = '0%';
          setTimeout(() => {
            el.style.width = targetWidth + '%';
          }, 100);
          progressObserver.unobserve(el);
        });
      }, {
        threshold: 0.5
      });

      progressBars.forEach(el => progressObserver.observe(el));

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

  <x-footer bg="bg-slate-50" />
@endsection
