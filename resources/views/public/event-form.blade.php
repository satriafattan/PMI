@extends('layouts.app')
<x-navbar />
@section('content')

  <main class="relative mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    {{-- Hero Section --}}
    <div class="mb-12 text-center">
      <div
           class="mb-4 inline-flex items-center justify-center rounded-full bg-red-100/80 px-4 py-1.5 text-sm font-medium text-red-600 backdrop-blur-sm">
        <svg class="mr-1.5 h-4 w-4"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Jadwalkan Event Anda
      </div>
      <h1 class="mt-3 text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">
        Ajukan Event Anda
      </h1>
      <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-600">
        Lengkapi data pemohon dan detail kegiatan. Tim kami akan meninjau & menghubungi Anda melalui email/telepon.
      </p>
    </div>

    {{-- Errors --}}
    @if ($errors->any())
      <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
        <div class="flex items-start gap-3">
          <div
               class="mt-0.5 grid h-6 w-6 place-items-center rounded-full border border-red-300 bg-white/70 text-xs font-bold text-red-600">
            !</div>
          <div>
            <p class="font-semibold">Harap koreksi input berikut:</p>
            <ul class="ml-5 mt-1 list-disc text-sm">
              @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    @endif

    <form id="eventForm"
          method="POST"
          action="{{ route('public.event.store') }}"
          enctype="multipart/form-data"
          class="grid gap-6 md:grid-cols-2">
      @csrf

      {{-- A. Data Pemohon --}}
      <section
               class="rounded-2xl border border-slate-200 bg-white p-6 shadow-lg transition-all duration-300 hover:shadow-xl md:col-span-2">

        <div class="mb-6 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div
                 class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-red-600 to-red-500 text-white shadow-lg ring-4 ring-white">
              <span class="text-lg font-bold">A</span>
            </div>
            <div>
              <h2 class="text-xl font-bold text-slate-900">Data Pemohon</h2>
              <p class="text-sm text-slate-600">Informasi kontak utama penyelenggara</p>
            </div>
          </div>
          <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-600">Wajib diisi</span>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
          {{-- Nama --}}
          <div class="group">
            <label class="mb-1.5 flex items-center gap-1 text-sm font-medium text-slate-700">
              Nama
            </label>
            <input type="text"
                   name="nama"
                   required
                   placeholder="Nama lengkap pemohon"
                   value="{{ old('nama') }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[15px] shadow-sm transition-all duration-200 placeholder:text-slate-300 hover:border-slate-300 focus:border-red-500 focus:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500/20">
            @error('nama')
              <p class="mt-1.5 flex items-center gap-1 text-sm text-red-600">
                <svg class="h-4 w-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $message }}
              </p>
            @enderror
          </div>

          {{-- Institusi --}}
          <div>
            <label class="block text-sm font-medium text-slate-700">Institusi Pemohon</label>
            <input type="text"
                   name="institusi_pemohon"
                   required
                   placeholder="Nama institusi atau organisasi"
                   value="{{ old('institusi_pemohon') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[15px] shadow-inner focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20">
            @error('institusi_pemohon')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Telepon --}}
          <div>
            <div class="flex items-center justify-between">
              <label class="block text-sm font-medium text-slate-700">Nomor Telepon</label>
            </div>
            <input type="tel"
                   name="nomor_telefon"
                   required
                   placeholder="0812xxxxxxx"
                   value="{{ old('nomor_telefon') }}"
                   pattern="[0-9]{10,15}"
                   title="Nomor telepon harus terdiri dari 10-15 digit angka"
                   inputmode="numeric"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[15px] shadow-inner focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20">
            @error('nomor_telefon')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Email --}}
          <div>
            <label class="block text-sm font-medium text-slate-700">Email</label>
            <input type="email"
                   name="email"
                   required
                   placeholder="nama@email.com"
                   value="{{ old('email') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[15px] shadow-inner focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20">
            @error('email')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Surat Instansi --}}
          <div class="md:col-span-1">
            <label class="block text-sm font-medium text-slate-700">
              Surat Instansi (PDF/JPG/PNG) <span class="text-red-600">*</span>
            </label>
            <div class="mt-1 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/60 p-4">
              <input type="file"
                     name="surat_instansi"
                     required
                     accept=".pdf,.jpg,.jpeg,.png"
                     class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-[15px] file:mr-4 file:rounded-md file:border-0 file:bg-red-600 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-red-700 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20">
              <p class="mt-2 text-xs text-slate-500">Maksimal 2 MB. Contoh: surat permohonan kegiatan dari institusi.</p>
              @error('surat_instansi')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror

              @isset($event)
                @if ($event->surat_instansi_path)
                  <a href="{{ Storage::url($event->surat_instansi_path) }}"
                     class="mt-2 inline-flex items-center gap-2 text-sm text-red-600 hover:underline">
                    Lihat surat yang sudah diunggah
                  </a>
                @endif
              @endisset
            </div>
            @error('surat_instansi')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- spacer --}}
          <div class="md:col-span-1"></div>
        </div>
      </section>

      {{-- B. Detail Event --}}
      <section
               class="rounded-2xl border border-slate-200 bg-white p-6 shadow-lg transition-all duration-300 hover:shadow-xl md:col-span-2">

        <div class="mb-6 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div
                 class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-red-600 to-red-500 text-white shadow-lg ring-4 ring-white">
              <span class="text-lg font-bold">B</span>
            </div>
            <div>
              <h2 class="text-xl font-bold text-slate-900">Detail Event</h2>
              <p class="text-sm text-slate-600">Informasi waktu dan lokasi acara</p>
            </div>
          </div>
          <div class="flex items-center gap-2 rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-orange-600">
            <svg class="h-4 w-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Atur Jadwal</span>
          </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
          <div class="group">
            <label class="mb-1.5 flex items-center gap-1 text-sm font-medium text-slate-700">
              <svg class="h-4 w-4 text-slate-400"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              Tanggal Event
            </label>
            <div class="relative">
              <input type="date"
                     name="tanggal_event"
                     required
                     value="{{ old('tanggal_event') }}"
                     min="{{ date('Y-m-d') }}"
                     class="peer w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 pl-10 text-[15px] shadow-sm transition-all duration-200 hover:border-slate-300 focus:border-red-500 focus:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500/20">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <span class="text-slate-400 transition-colors duration-200 peer-focus:text-red-500">
                  <svg class="h-5 w-5"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.5"
                          d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                  </svg>
                </span>
              </div>
            </div>
            @error('tanggal_event')
              <p class="mt-1.5 flex items-center gap-1 text-sm text-red-600">
                <svg class="h-4 w-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $message }}
              </p>
            @enderror
          </div>

          <div class="group">
            <label class="mb-1.5 flex items-center gap-1 text-sm font-medium text-slate-700">
              <svg class="h-4 w-4 text-slate-400"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Jam Mulai
            </label>
            <div class="relative">
              <input type="time"
                     name="jam_mulai"
                     value="{{ old('jam_mulai') }}"
                     class="peer w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 pl-10 text-[15px] shadow-sm transition-all duration-200 hover:border-slate-300 focus:border-red-500 focus:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500/20">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <span class="text-slate-400 transition-colors duration-200 peer-focus:text-red-500">
                  <svg class="h-5 w-5"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.5"
                          d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </div>
            </div>
          </div>

          <div class="group">
            <label class="mb-1.5 flex items-center gap-1 text-sm font-medium text-slate-700">
              <svg class="h-4 w-4 text-slate-400"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Jam Selesai
            </label>
            <div class="relative">
              <input type="time"
                     name="jam_selesai"
                     value="{{ old('jam_selesai') }}"
                     class="peer w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 pl-10 text-[15px] shadow-sm transition-all duration-200 hover:border-slate-300 focus:border-red-500 focus:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500/20">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <span class="text-slate-400 transition-colors duration-200 peer-focus:text-red-500">
                  <svg class="h-5 w-5"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.5"
                          d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6 grid gap-6 md:grid-cols-2">
          <div>
            <label class="block text-sm font-medium text-slate-700">Jenis Event</label>
            <select name="jenis_event"
                    required
                    class="mt-1 w-full appearance-none rounded-xl border border-slate-200 bg-white px-3 py-2.5 pr-10 text-[15px] shadow-inner focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20">
              <option value="">-- Pilih Jenis Event --</option>
              @foreach ($eventTypes as $type)
                <option value="{{ $type }}"
                        @selected(old('jenis_event') === $type)>{{ $type }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700">Lokasi Lengkap</label>
            <textarea name="lokasi_lengkap"
                      rows="3"
                      placeholder="Alamat lengkap / nama gedung / patokan"
                      class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[15px] shadow-inner focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20">{{ old('lokasi_lengkap') }}</textarea>
          </div>
        </div>
      </section>

      {{-- C. Estimasi & Kebutuhan --}}
      <section
               class="rounded-2xl border border-slate-200 bg-white p-6 shadow-lg transition-all duration-300 hover:shadow-xl md:col-span-2">

        <div class="mb-6 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div
                 class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-red-600 to-red-500 text-white shadow-lg ring-4 ring-white">
              <span class="text-lg font-bold">C</span>
            </div>
            <div>
              <h2 class="text-xl font-bold text-slate-900">Estimasi & Kebutuhan</h2>
              <p class="text-sm text-slate-600">Perkiraan jumlah peserta dan fasilitas</p>
            </div>
          </div>
          <div class="flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-600">
            <svg class="h-4 w-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>Perkiraan</span>
          </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
          <div class="group">
            <label class="mb-1.5 flex items-center gap-1 text-sm font-medium text-slate-700">
              <svg class="h-4 w-4 text-slate-400"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              Jumlah Peserta (perkiraan)
            </label>
            <div class="relative">
              <input type="number"
                     name="jumlah_peserta"
                     min="1"
                     placeholder="Masukkan perkiraan jumlah peserta"
                     value="{{ old('jumlah_peserta') }}"
                     class="peer w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 pl-10 text-[15px] shadow-sm transition-all duration-200 hover:border-slate-300 focus:border-blue-500 focus:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500/20">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <span class="text-slate-400 transition-colors duration-200 peer-focus:text-blue-500">
                  <svg class="h-5 w-5"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.5"
                          d="M18 18v-1.5c0-1.5-1.343-2.5-3-2.5h-3c-1.657 0-3 1-3 2.5V18m9-9a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </span>
              </div>
            </div>
          </div>

          <div class="group">
            <label class="mb-1.5 flex items-center gap-1 text-sm font-medium text-slate-700">
              <svg class="h-4 w-4 text-slate-400"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              Target Peserta
            </label>
            <div class="relative">
              <select name="target_peserta"
                      class="peer w-full appearance-none rounded-xl border border-slate-200 bg-white px-3 py-2.5 pl-10 pr-10 text-[15px] shadow-sm transition-all duration-200 hover:border-slate-300 focus:border-blue-500 focus:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                <option value="">-- Pilih Target --</option>
                @foreach ($targetOptions as $t)
                  <option value="{{ $t }}"
                          @selected(old('target_peserta') === $t)>{{ $t }}</option>
                @endforeach
              </select>
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <span class="text-slate-400 transition-colors duration-200 peer-focus:text-blue-500">
                  <svg class="h-5 w-5"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.5"
                          d="M18 18v-1.5c0-1.5-1.343-2.5-3-2.5h-3c-1.657 0-3 1-3 2.5V18m9-9a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </span>
              </div>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="h-5 w-5 text-slate-400"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-4 md:mt-7">
            <label class="relative inline-flex cursor-pointer items-center">
              <input id="butuhMU"
                     type="checkbox"
                     name="butuh_mobil_unit"
                     value="1"
                     @checked(old('butuh_mobil_unit'))
                     class="peer sr-only">
              <div
                   class="peer h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rtl:peer-checked:after:-translate-x-full">
              </div>
              <span class="ml-2 text-sm font-medium text-slate-700">Butuh Mobil Unit</span>
            </label>
          </div>
        </div>

        <div class="mt-6">
          <label class="block text-sm font-medium text-slate-700">Fasilitas Tersedia</label>
          <textarea name="fasilitas_tersedia"
                    rows="3"
                    placeholder="Contoh: Ruang ber-AC, meja & kursi, listrik, parkir, tenda"
                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[15px] shadow-inner focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">{{ old('fasilitas_tersedia') }}</textarea>

          @isset($facilityHints)
            <p class="mt-2 text-xs text-slate-500">
              Saran:
              @foreach ($facilityHints as $fh)
                <span>{{ $fh }}</span>
                @if (!$loop->last)
                  ,
                @endif
              @endforeach
            </p>
          @endisset
        </div>
      </section>

      {{-- D. Lainnya --}}
      <section class="rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-sm backdrop-blur-sm md:col-span-2">
        <div class="mb-5 flex items-center gap-3">
          <div class="grid h-9 w-9 place-items-center rounded-xl bg-red-600 text-white shadow ring-4 ring-white">D</div>
          <h2 class="text-lg font-semibold text-slate-900 sm:text-xl">Lainnya</h2>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
          <div>
            <label class="block text-sm font-medium text-slate-700">Catatan Tambahan</label>
            <textarea name="catatan_tambahan"
                      rows="3"
                      placeholder="Info khusus, kebutuhan tambahan, dll."
                      class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[15px] shadow-inner focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">{{ old('catatan_tambahan') }}</textarea>
          </div>

          <div class="flex items-center gap-3">
            <input id="izinPublikasi"
                   type="checkbox"
                   name="izin_publikasi"
                   value="1"
                   @checked(old('izin_publikasi'))
                   class="h-5 w-5 rounded border border-slate-300 text-red-600 focus:ring-2 focus:ring-red-500">
            <label for="izinPublikasi"
                   class="text-sm text-slate-700">Mengizinkan dokumentasi & publikasi kegiatan</label>
          </div>
        </div>
      </section>

      {{-- Submit --}}
      <div class="md:col-span-2">
        <div class="flex flex-col-reverse items-center justify-between gap-4 sm:flex-row">
          <p class="text-sm text-slate-500">
            Pastikan semua data yang Anda masukkan sudah benar dan sesuai dengan kebutuhan Anda.
          </p>
          <button id="submitBtn"
                  type="submit"
                  class="group relative inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-red-600 to-red-500 px-8 py-3 text-white shadow-lg transition-all duration-200 hover:translate-y-[-1px] hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-red-500/30 disabled:opacity-70">
            <span
                  class="absolute inset-0 rounded-xl bg-white/10 opacity-0 transition-opacity duration-200 group-hover:opacity-100"></span>
            <svg class="h-5 w-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7" />
            </svg>
            <span class="font-medium">Ajukan Penjadwalan</span>
          </button>
        </div>
      </div>
    </form>

    {{-- Success Modal --}}
    @if (session('success'))
      <div id="successModal"
           class="fixed inset-0 z-[9999] flex items-center justify-center">
        <div class="absolute inset-0 bg-black/40"></div>

        <div class="relative z-10 w-[90%] max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl">
          <div class="absolute right-4 top-4">
            <button type="button"
                    class="rounded-lg p-2 text-slate-400 hover:bg-slate-50 hover:text-slate-600"
                    onclick="hideSuccessModal()">
              <svg class="h-5 w-5"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <div class="px-8 pb-10 pt-12 text-center">
            <div class="mx-auto mb-6 grid h-20 w-20 place-items-center rounded-full bg-emerald-50 text-emerald-500">
              <svg class="h-10 w-10"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>

            <h3 class="text-2xl font-bold text-slate-800">Pengajuan Berhasil Dikirim!</h3>
            <p class="mx-auto mt-3 max-w-sm text-slate-600">Kami akan meninjau pengajuan Anda dan menghubungi melalui
              email atau telepon.</p>
          </div>

          <div class="grid grid-cols-2 divide-x divide-slate-100 border-t border-slate-100">
            <button type="button"
                    class="px-6 py-4 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
                    onclick="hideSuccessModal()">Tutup</button>
            <a href="{{ route('public.event.create') }}"
               class="bg-red-600 px-6 py-4 text-center text-sm font-medium text-white transition hover:bg-red-700">Ajukan
              Lagi</a>
          </div>
        </div>
      </div>

      @php
        // Mencegah session flash message muncul sebagai notifikasi terpisah
        Session::forget('success');
      @endphp
    @endif

  </main>

  <x-footer />

  {{-- Scripts --}}
  <script>
    const form = document.getElementById('eventForm');
    const submitBtn = document.getElementById('submitBtn');

    function allRequiredFilled() {
      let ok = true;
      // Cek semua field required
      form.querySelectorAll(
        '[name="nama"],[name="institusi_pemohon"],[name="nomor_telefon"],[name="email"],[name="tanggal_event"],[name="jenis_event"]'
      ).forEach(el => {
        if (!el.value) ok = false;
      });

      // Cek file surat_instansi juga
      const fileInput = form.querySelector('[name="surat_instansi"]');
      if (fileInput && !fileInput.files.length) {
        ok = false;
      }

      return ok;
    }

    function toggleSubmit() {
      submitBtn.disabled = !allRequiredFilled();
      submitBtn.classList.toggle('opacity-60', submitBtn.disabled);
      submitBtn.classList.toggle('cursor-not-allowed', submitBtn.disabled);
    }

    form.addEventListener('input', toggleSubmit);
    form.addEventListener('change', toggleSubmit); // Untuk file input
    document.addEventListener('DOMContentLoaded', toggleSubmit);

    function hideSuccessModal() {
      const modal = document.getElementById('successModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }
  </script>
@endsection
