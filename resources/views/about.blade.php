{{-- resources/views/about.blade.php --}}
@extends('layouts.app')

@section('title', 'Tentang Kami – SIMPHONY')
@section('content')
  <div class="min-h-screen bg-white text-slate-800">
    <x-navbar />

    {{-- HERO - Enhanced Design --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-red-700 via-red-600 to-rose-500">
      {{-- Animated background elements --}}
      <div class="pointer-events-none absolute inset-0">
        <div class="absolute -left-24 -top-24 h-96 w-96 animate-pulse rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 h-96 w-96 animate-pulse rounded-full bg-rose-300/20 blur-3xl"
             style="animation-delay: 1s;"></div>
        <div class="absolute left-1/2 top-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2 animate-pulse rounded-full bg-white/5 blur-3xl"
             style="animation-delay: 2s;"></div>
      </div>

      <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <div class="text-center text-white">
          <div
               class="mx-auto inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-medium ring-1 ring-white/20 backdrop-blur-sm">
            <svg class="h-4 w-4"
                 fill="currentColor"
                 viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            Kepercayaan & Dedikasi
          </div>

          <h1 class="mt-6 text-5xl font-extrabold leading-tight tracking-tight sm:text-6xl lg:text-7xl">
            Tentang
            <span class="block bg-gradient-to-r from-white to-red-100 bg-clip-text text-transparent">SIMPHONY</span>
          </h1>

          <p class="mx-auto mt-6 max-w-3xl text-lg leading-relaxed text-white/90 sm:text-xl">
            Membangun masa depan kesehatan yang lebih baik melalui <strong class="font-semibold text-white">inovasi,
              dedikasi, dan pelayanan terdepan</strong> dalam pengelolaan pemesanan dan inventori darah.
          </p>

          {{-- Stats --}}
          <div class="mt-12 flex justify-center gap-8">
        </div>
      </div>
    </section>

    {{-- PROFIL PERUSAHAAN - Enhanced Design --}}
    <section class="relative bg-gradient-to-b from-white to-slate-50 py-20 sm:py-28">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
          <div
               class="mx-auto inline-flex items-center gap-2 rounded-full bg-red-100 px-4 py-1.5 text-sm font-semibold text-red-700">
            <span class="relative flex h-2 w-2">
              <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex h-2 w-2 rounded-full bg-red-600"></span>
            </span>
            Profil Institusi
          </div>
          <h2 class="mt-4 text-4xl font-extrabold text-slate-900 sm:text-5xl">
            Mengenal SIMPHONY
          </h2>
        </div>

        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
          {{-- Left Content --}}
          <div>
            <div class="space-y-4 text-lg leading-relaxed text-slate-600">
              <p>
                Sebagai <strong class="font-semibold text-slate-900">platform sistem informasi terdepan</strong>, SIMPHONY
                berkomitmen untuk memberikan solusi digital berkualitas tinggi dalam mengelola pemesanan darah dan
                inventori dengan mengintegrasikan teknologi modern dan pendekatan yang efisien.
              </p>
              <p>
                SIMPHONY (Sistem Informasi Pemesanan dan Inventori) dirancang untuk <strong
                        class="font-semibold text-red-600">mengharmonisasikan alur kerja</strong> antara proses
                pemesanan darah, pengelolaan stok, serta verifikasi pada Unit Donor Darah.
              </p>
              <p>
                Didukung oleh <strong class="font-semibold text-slate-900">teknologi terkini</strong> dan antarmuka yang
                user-friendly, kami memastikan setiap proses pemesanan dan pengelolaan stok darah berjalan lancar, aman,
                dan efisien sesuai standar kesehatan.
              </p>
            </div>

            {{-- CTA Button --}}
            <div class="mt-8">
              <a href="{{ url('/pemesanan') }}"
                 class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-6 py-3 font-semibold text-white shadow-lg transition hover:bg-red-700 hover:shadow-xl">
                Ajukan Permintaan Darah
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
            </div>
          </div>

          {{-- Right Content - Image --}}
          <div class="relative">
            <div class="absolute -inset-4 rounded-3xl bg-gradient-to-r from-red-200/50 to-rose-200/50 blur-2xl"></div>
            <div class="relative overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-slate-200">
              <div class="aspect-[4/3]">
                <img src="{{ asset('images/Gambar About.jpeg') }}"
                     alt="Platform Digital Terpadu"
                     class="h-full w-full object-cover">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- VISI & MISI - Enhanced Design --}}
    <section class="relative overflow-hidden bg-white py-20 sm:py-28">
      {{-- Background Pattern --}}
      <div class="pointer-events-none absolute inset-0 opacity-40">
        <div class="absolute left-0 top-0 h-96 w-96 rounded-full bg-red-100 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-rose-100 blur-3xl"></div>
      </div>

      <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <div
               class="mx-auto inline-flex items-center gap-2 rounded-full bg-red-100 px-4 py-1.5 text-sm font-semibold text-red-700">
            <svg class="h-4 w-4"
                 fill="currentColor"
                 viewBox="0 0 20 20">
              <path
                    d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01-.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
            </svg>
            Visi & Misi
          </div>
          <h2 class="mt-4 text-4xl font-extrabold text-slate-900 sm:text-5xl">
            Fondasi <span class="bg-gradient-to-r from-red-600 to-rose-500 bg-clip-text text-transparent">Kami</span>
          </h2>
          <p class="mx-auto mt-3 max-w-2xl text-lg text-slate-600">
            Nilai dan tujuan yang mengarahkan setiap langkah perjalanan kami.
          </p>
        </div>

        {{-- Enhanced Tabs --}}
        <div class="mt-10 flex justify-center">
          <div class="inline-flex rounded-2xl border-2 border-slate-200 bg-white p-1.5 shadow-lg">
            <button data-tab="visi"
                    class="tab-btn rounded-xl bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-md transition-all duration-200">
              <div class="flex items-center gap-2">
                <svg class="h-5 w-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Visi Kami
              </div>
            </button>
            <button data-tab="misi"
                    class="tab-btn rounded-xl px-6 py-3 text-sm font-semibold text-slate-700 transition-all duration-200 hover:bg-slate-50">
              <div class="flex items-center gap-2">
                <svg class="h-5 w-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Misi Kami
              </div>
            </button>
          </div>
        </div>

        {{-- Tab Content --}}
        {{-- Visi --}}
        <div id="tab-visi"
             class="tab-panel mt-12">
          <div class="grid gap-6 md:grid-cols-3">
            {{-- Card 1: Transformasi Digital --}}
            <div class="group relative text-left">
              <div
                   class="absolute -inset-0.5 rounded-3xl bg-gradient-to-br from-blue-100 to-blue-50 opacity-0 blur transition duration-300 group-hover:opacity-100">
              </div>
              <div
                   class="relative h-full rounded-3xl border-2 border-slate-200 bg-white p-6 shadow-lg transition duration-300 group-hover:border-blue-200 group-hover:shadow-2xl">
                <div
                     class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-blue-500 text-white shadow-lg">
                  <svg class="h-7 w-7"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Transformasi Digital</h3>
                <p class="mt-3 leading-relaxed text-slate-600">Menjadi platform sistem informasi terdepan yang
                  mengintegrasikan teknologi digital dalam pengelolaan pemesanan dan inventori darah.</p>
              </div>
            </div>

            {{-- Card 2: Aksesibilitas 24/7 --}}
            <div class="group relative text-left">
              <div
                   class="absolute -inset-0.5 rounded-3xl bg-gradient-to-br from-green-100 to-green-50 opacity-0 blur transition duration-300 group-hover:opacity-100">
              </div>
              <div
                   class="relative h-full rounded-3xl border-2 border-slate-200 bg-white p-6 shadow-lg transition duration-300 group-hover:border-green-200 group-hover:shadow-2xl">
                <div
                     class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-green-600 to-green-500 text-white shadow-lg">
                  <svg class="h-7 w-7"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Aksesibilitas 24/7</h3>
                <p class="mt-3 leading-relaxed text-slate-600">Setiap pengguna memiliki akses real-time ke sistem untuk
                  pemesanan dan monitoring stok darah kapan saja, dimana saja.</p>
              </div>
            </div>

            {{-- Card 3: Harmonisasi Alur Kerja --}}
            <div class="group relative text-left">
              <div
                   class="absolute -inset-0.5 rounded-3xl bg-gradient-to-br from-purple-100 to-purple-50 opacity-0 blur transition duration-300 group-hover:opacity-100">
              </div>
              <div
                   class="relative h-full rounded-3xl border-2 border-slate-200 bg-white p-6 shadow-lg transition duration-300 group-hover:border-purple-200 group-hover:shadow-2xl">
                <div
                     class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-600 to-purple-500 text-white shadow-lg">
                  <svg class="h-7 w-7"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Harmonisasi Alur Kerja</h3>
                <p class="mt-3 leading-relaxed text-slate-600">Mengintegrasikan proses pemesanan, pengelolaan stok, dan
                  verifikasi dalam satu platform yang terkoordinasi.</p>
              </div>
            </div>
          </div>
        </div>

        {{-- Misi --}}
        <div id="tab-misi"
             class="tab-panel mt-12 hidden">
          <div class="grid gap-6 md:grid-cols-3">
            {{-- Card 1: Sistem Terintegrasi --}}
            <div class="group relative text-left">
              <div
                   class="absolute -inset-0.5 rounded-3xl bg-gradient-to-br from-red-100 to-red-50 opacity-0 blur transition duration-300 group-hover:opacity-100">
              </div>
              <div
                   class="relative h-full rounded-3xl border-2 border-slate-200 bg-white p-6 shadow-lg transition duration-300 group-hover:border-red-200 group-hover:shadow-2xl">
                <div
                     class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-red-600 to-red-500 text-white shadow-lg">
                  <svg class="h-7 w-7"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Sistem Terintegrasi</h3>
                <p class="mt-3 leading-relaxed text-slate-600">Menyediakan platform terpadu untuk mengelola pemesanan
                  darah, inventori stok, dan verifikasi secara efisien.</p>
              </div>
            </div>

            {{-- Card 2: Digitalisasi Proses --}}
            <div class="group relative text-left">
              <div
                   class="absolute -inset-0.5 rounded-3xl bg-gradient-to-br from-indigo-100 to-indigo-50 opacity-0 blur transition duration-300 group-hover:opacity-100">
              </div>
              <div
                   class="relative h-full rounded-3xl border-2 border-slate-200 bg-white p-6 shadow-lg transition duration-300 group-hover:border-indigo-200 group-hover:shadow-2xl">
                <div
                     class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-indigo-500 text-white shadow-lg">
                  <svg class="h-7 w-7"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Digitalisasi Proses</h3>
                <p class="mt-3 leading-relaxed text-slate-600">Mengotomatisasi alur kerja pemesanan dan pengelolaan stok
                  untuk meningkatkan kecepatan dan akurasi.</p>
              </div>
            </div>

            {{-- Card 3: Update Real-time --}}
            <div class="group relative text-left">
              <div
                   class="absolute -inset-0.5 rounded-3xl bg-gradient-to-br from-amber-100 to-amber-50 opacity-0 blur transition duration-300 group-hover:opacity-100">
              </div>
              <div
                   class="relative h-full rounded-3xl border-2 border-slate-200 bg-white p-6 shadow-lg transition duration-300 group-hover:border-amber-200 group-hover:shadow-2xl">
                <div
                     class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-600 to-amber-500 text-white shadow-lg">
                  <svg class="h-7 w-7"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Update Real-time</h3>
                <p class="mt-3 leading-relaxed text-slate-600">Menyediakan informasi stok darah yang akurat dan terkini
                  untuk mendukung pengambilan keputusan cepat.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- NILAI-NILAI - Enhanced Design --}}
    <section class="relative bg-gradient-to-b from-slate-50 to-white py-20 sm:py-28">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <div
               class="mx-auto inline-flex items-center gap-2 rounded-full bg-red-100 px-4 py-1.5 text-sm font-semibold text-red-700">
            <svg class="h-4 w-4"
                 fill="currentColor"
                 viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                    clip-rule="evenodd" />
            </svg>
            Core Values
          </div>
          <h2 class="mt-4 text-4xl font-extrabold text-slate-900 sm:text-5xl">
            Nilai-Nilai <span class="bg-gradient-to-r from-red-600 to-rose-500 bg-clip-text text-transparent">Kami</span>
          </h2>
          <p class="mx-auto mt-3 max-w-2xl text-lg text-slate-600">
            Prinsip fundamental yang menjadi landasan setiap tindakan dan keputusan kami.
          </p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-5">
          {{-- Card 1: Integritas --}}
          <div class="group relative text-left">
            <div
                 class="absolute -inset-0.5 rounded-3xl bg-gradient-to-br from-red-100 to-red-50 opacity-0 blur transition duration-300 group-hover:opacity-100">
            </div>
            <div
                 class="relative h-full rounded-3xl border-2 border-slate-200 bg-white p-6 shadow-lg transition duration-300 group-hover:border-red-200 group-hover:shadow-2xl">
              <div
                   class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-red-600 to-red-500 text-white shadow-lg transition group-hover:scale-110">
                <svg class="h-7 w-7"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
              </div>
              <h3 class="text-lg font-bold text-slate-900">Integritas</h3>
              <p class="mt-2 text-sm leading-relaxed text-slate-600">
                Berkomitmen pada kejujuran, transparansi, dan akurasi data dalam setiap transaksi sistem.
              </p>
            </div>
          </div>

          {{-- Card 2: Kecepatan --}}
          <div class="group relative text-left">
            <div
                 class="absolute -inset-0.5 rounded-3xl bg-gradient-to-br from-blue-100 to-blue-50 opacity-0 blur transition duration-300 group-hover:opacity-100">
            </div>
            <div
                 class="relative h-full rounded-3xl border-2 border-slate-200 bg-white p-6 shadow-lg transition duration-300 group-hover:border-blue-200 group-hover:shadow-2xl">
              <div
                   class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-blue-500 text-white shadow-lg transition group-hover:scale-110">
                <svg class="h-7 w-7"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <h3 class="text-lg font-bold text-slate-900">Kecepatan</h3>
              <p class="mt-2 text-sm leading-relaxed text-slate-600">
                Mengoptimalkan setiap proses untuk menghemat waktu dan meningkatkan produktivitas.
              </p>
            </div>
          </div>

          {{-- Card 3: User-Friendly --}}
          <div class="group relative text-left">
            <div
                 class="absolute -inset-0.5 rounded-3xl bg-gradient-to-br from-rose-100 to-rose-50 opacity-0 blur transition duration-300 group-hover:opacity-100">
            </div>
            <div
                 class="relative h-full rounded-3xl border-2 border-slate-200 bg-white p-6 shadow-lg transition duration-300 group-hover:border-rose-200 group-hover:shadow-2xl">
              <div
                   class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-rose-600 to-rose-500 text-white shadow-lg transition group-hover:scale-110">
                <svg class="h-7 w-7"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h3 class="text-lg font-bold text-slate-900">User-Friendly</h3>
              <p class="mt-2 text-sm leading-relaxed text-slate-600">
                Mengutamakan kemudahan penggunaan dengan antarmuka yang intuitif dan responsif.
              </p>
            </div>
          </div>

          {{-- Card 4: Inovatif --}}
          <div class="group relative text-left">
            <div
                 class="absolute -inset-0.5 rounded-3xl bg-gradient-to-br from-amber-100 to-amber-50 opacity-0 blur transition duration-300 group-hover:opacity-100">
            </div>
            <div
                 class="relative h-full rounded-3xl border-2 border-slate-200 bg-white p-6 shadow-lg transition duration-300 group-hover:border-amber-200 group-hover:shadow-2xl">
              <div
                   class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-600 to-amber-500 text-white shadow-lg transition group-hover:scale-110">
                <svg class="h-7 w-7"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
              </div>
              <h3 class="text-lg font-bold text-slate-900">Inovatif</h3>
              <p class="mt-2 text-sm leading-relaxed text-slate-600">
                Terus berinovasi dengan teknologi dan fitur terkini untuk pelayanan yang lebih baik.
              </p>
            </div>
          </div>

          {{-- Card 5: Keamanan Data --}}
          <div class="group relative text-left">
            <div
                 class="absolute -inset-0.5 rounded-3xl bg-gradient-to-br from-green-100 to-green-50 opacity-0 blur transition duration-300 group-hover:opacity-100">
            </div>
            <div
                 class="relative h-full rounded-3xl border-2 border-slate-200 bg-white p-6 shadow-lg transition duration-300 group-hover:border-green-200 group-hover:shadow-2xl">
              <div
                   class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-green-600 to-green-500 text-white shadow-lg transition group-hover:scale-110">
                <svg class="h-7 w-7"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <h3 class="text-lg font-bold text-slate-900">Keamanan Data</h3>
              <p class="mt-2 text-sm leading-relaxed text-slate-600">
                Menjamin keamanan dan privasi data dengan sistem enkripsi dan protokol keamanan tingkat tinggi.
              </p>
            </div>
          </div>
        </div>

        {{-- Additional Trust Indicators --}}
        <div class="mt-12 grid gap-6 md:grid-cols-3">
          <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-lg">
            <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-2xl bg-green-100 text-green-600">
              <svg class="h-8 w-8"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
              </svg>
            </div>
            <h3 class="font-bold text-slate-900">Platform Terintegrasi</h3>
            <p class="mt-2 text-sm text-slate-600">Sistem terpadu untuk pemesanan dan inventori</p>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-lg">
            <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
              <svg class="h-8 w-8"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <h3 class="font-bold text-slate-900">Teknologi Modern</h3>
            <p class="mt-2 text-sm text-slate-600">Dibangun dengan framework Laravel terkini</p>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-lg">
            <div
                 class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-2xl bg-purple-100 text-purple-600">
              <svg class="h-8 w-8"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
            <h3 class="font-bold text-slate-900">Keamanan Terjamin</h3>
            <p class="mt-2 text-sm text-slate-600">Enkripsi data dan sistem autentikasi berlapis</p>
          </div>
        </div>
      </div>
    </section>

    {{-- CTA - Enhanced Design --}}
    <section class="relative overflow-hidden py-20 sm:py-28">
      {{-- Background Pattern --}}
      <div class="pointer-events-none absolute inset-0">
        <div class="absolute -left-24 top-0 h-96 w-96 rounded-full bg-red-100 blur-3xl"></div>
        <div class="absolute -right-24 bottom-0 h-96 w-96 rounded-full bg-rose-100 blur-3xl"></div>
      </div>

      <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div
             class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-red-700 via-red-600 to-rose-500 shadow-2xl">
          {{-- Animated background blobs --}}
          <div class="pointer-events-none absolute inset-0">
            <div class="absolute -left-16 -top-16 h-64 w-64 animate-pulse rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-16 -right-16 h-64 w-64 animate-pulse rounded-full bg-rose-300/20 blur-3xl"
                 style="animation-delay: 1s;"></div>
          </div>

          <div class="relative px-8 py-16 text-center text-white sm:px-12 sm:py-20">
            <h3 class="mt-6 text-4xl font-extrabold leading-tight sm:text-5xl">
              Berkolaborasi Dengan Kami
            </h3>
            <p class="mx-auto mt-4 max-w-2xl text-lg leading-relaxed text-white/90">
              Mari bersama-sama membangun sistem kesehatan yang lebih baik melalui digitalisasi dan inovasi.
              <strong class="font-semibold text-white">Setiap kontribusi teknologi membawa dampak nyata.</strong>
            </p>

            <div class="mt-8 flex flex-wrap justify-center gap-4">
              <a href="{{ url('/jadwal-event') }}"
                 class="group inline-flex items-center gap-2 rounded-xl bg-white px-6 py-4 font-semibold text-red-700 shadow-xl transition hover:scale-105 hover:bg-red-50 hover:shadow-2xl">
                <svg class="h-5 w-5 transition group-hover:rotate-12"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Jadwalkan Event
              </a>
              <a href="{{ url('/') }}"
                 class="inline-flex items-center gap-2 rounded-xl border-2 border-white/30 bg-white/5 px-6 py-4 font-semibold text-white backdrop-blur-sm transition hover:bg-white/10">
                Kembali ke Beranda
                <svg class="h-5 w-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
              </a>
            </div>

            {{-- Contact Info --}}
            <div class="mx-auto mt-12 flex max-w-3xl flex-wrap justify-center gap-6 text-sm text-white/80">
              <div class="flex items-center gap-2">
                <svg class="h-5 w-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span>Support : 0721475019</span>
              </div>
              <div class="flex items-center gap-2">
                <svg class="h-5 w-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>simphonydarah@gmail.com</span>
              </div>
              <div class="flex items-center gap-2">
                <svg class="h-5 w-5"
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
                <span>Lampung, Indonesia</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <x-footer bg="bg-slate-50" />
  </div>

  {{-- Enhanced JavaScript with animations --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Tab switching with smooth transitions
      const btns = document.querySelectorAll('.tab-btn');
      const panels = {
        visi: document.getElementById('tab-visi'),
        misi: document.getElementById('tab-misi'),
      };

      btns.forEach(b => b.addEventListener('click', () => {
        // Update button states
        btns.forEach(x => {
          x.classList.remove('bg-red-600', 'text-white', 'shadow-md');
          x.classList.add('text-slate-700', 'hover:bg-slate-50');
        });
        b.classList.add('bg-red-600', 'text-white', 'shadow-md');
        b.classList.remove('text-slate-700', 'hover:bg-slate-50');

        // Toggle panels with fade effect
        Object.values(panels).forEach(p => {
          p.style.opacity = '0';
          p.style.transform = 'translateY(10px)';
        });

        setTimeout(() => {
          panels.visi.classList.toggle('hidden', b.dataset.tab !== 'visi');
          panels.misi.classList.toggle('hidden', b.dataset.tab !== 'misi');

          const activePanel = b.dataset.tab === 'visi' ? panels.visi : panels.misi;
          activePanel.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
          activePanel.style.opacity = '1';
          activePanel.style.transform = 'translateY(0)';
        }, 150);
      }));

      // Counter animation for stats
      const counters = document.querySelectorAll('[data-counter], [data-counter-stat]');
      const easeOutQuart = t => 1 - Math.pow(1 - t, 4);

      const counterObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) return;
          const el = entry.target;
          const text = el.textContent.trim();

          // Extract number from text like "25+", "1M+", "50+"
          let target = 0;
          if (text.includes('M+')) {
            target = parseFloat(text) * 1000000;
          } else if (text.includes('K+')) {
            target = parseFloat(text) * 1000;
          } else {
            target = parseInt(text.replace(/[^0-9]/g, ''), 10) || 0;
          }

          let start = null;
          const suffix = text.includes('+') ? '+' : '';
          const prefix = text.includes('M') ? 'M' : text.includes('K') ? 'K' : '';

          const animate = (timestamp) => {
            if (!start) start = timestamp;
            const progress = Math.min(1, (timestamp - start) / 1200);
            let value = Math.floor(easeOutQuart(progress) * target);

            // Format display
            if (prefix === 'M') {
              el.textContent = (value / 1000000).toFixed(0) + 'M' + suffix;
            } else if (prefix === 'K') {
              el.textContent = (value / 1000).toFixed(0) + 'K' + suffix;
            } else {
              el.textContent = value.toLocaleString('id-ID') + suffix;
            }

            if (progress < 1) requestAnimationFrame(animate);
          };

          requestAnimationFrame(animate);
          counterObserver.unobserve(el);
        });
      }, {
        threshold: 0.5
      });

      counters.forEach(el => counterObserver.observe(el));

      // Fade-in animation for sections
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
    });
  </script>
@endsection
