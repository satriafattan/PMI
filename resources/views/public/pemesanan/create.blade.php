@extends('layouts.app')
@section('title', 'Form Pemesanan Darah')

@section('content')
  <div class="min-h-screen bg-white text-slate-800">
    <x-navbar />

    <section class="py-10 sm:py-14">
      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        {{-- Hero Section --}}
        <div class="mb-10 text-center">
          <div
               class="mb-4 inline-flex items-center justify-center rounded-full bg-red-100/80 px-4 py-1.5 text-sm font-medium text-red-600">
            <svg class="mr-1.5 h-4 w-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
              </path>
            </svg>
            Form Pemesanan Darah
          </div>
          <h1 class="mt-3 text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">
            Formulir Pemesanan Darah
          </h1>
          <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-600">
            Lengkapi formulir berikut untuk melakukan pemesanan darah. Data akan diproses sesuai ketersediaan stok.
          </p>
        </div>

        @php
          $field =
              'w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[15px] shadow-sm transition-all duration-200 placeholder:text-slate-400 hover:border-slate-300 focus:border-red-500 focus:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500/20';
          $select = $field . ' pr-10 appearance-none';
          $textarea =
              'w-full min-h-[120px] rounded-xl border border-slate-200 bg-white p-4 text-[15px] shadow-sm transition-all duration-200 placeholder:text-slate-400 hover:border-slate-300 focus:border-red-500 focus:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500/20';
          $check = 'rounded text-red-600 border-slate-300 focus:ring-red-500 focus:ring-2';
        @endphp

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

        <form id="multiStepForm"
              method="POST"
              action="{{ route('pemesanan.store') }}"
              class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg transition-shadow duration-300 hover:shadow-xl">
          @csrf

          {{-- Header --}}
          <div class="border-b border-slate-100 bg-white p-5 sm:p-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div
                     class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-red-600 to-red-500 text-white shadow-lg ring-4 ring-white">
                  <svg class="h-5 w-5"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                  </svg>
                </div>
                <div>
                  <h1 id="stepTitle"
                      class="text-xl font-bold text-slate-900">Data Pasien & Rumah Sakit</h1>
                  <p class="mt-0.5 text-sm text-slate-500">Langkah <span id="pageNumber"
                          class="font-semibold text-red-500">1</span> dari 4</p>
                </div>
              </div>
              <div
                   class="hidden items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600 sm:flex">
                <svg class="h-4 w-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Step <span id="currentStep"
                        class="font-semibold text-red-500">1</span>/4</span>
              </div>
            </div>
          </div>

          {{-- BODY --}}
          <div class="space-y-6 p-5 sm:p-6">

            {{-- STEP 1 --}}
            <div class="step"
                 id="step-1">
              <div class="grid gap-5 sm:grid-cols-2">
                <div>
                  <label class="block text-sm font-medium text-slate-700">
                    Rumah Sakit <span class="text-red-600">*</span>
                  </label>
                  <input type="text"
                         required
                         name="rs_pemesan"
                         value="{{ old('rs_pemesan') }}"
                         class="{{ $field }}"
                         placeholder="Nama rumah sakit">
                  @error('rs_pemesan')
                    <p class="mt-1.5 text-sm text-red-600">
                      {{ $message }}
                    </p>
                  @enderror
                </div>

                <div class="relative">
                  <label class="block text-sm font-medium text-slate-700">Jenis Kelamin <span
                          class="text-red-600">*</span></label>
                  <select required
                          name="jenis_kelamin"
                          id="jenis_kelamin"
                          class="{{ $select }}">
                    <option value=""
                            disabled
                            selected>Pilih Jenis Kelamin</option>
                    <option value="L"
                            @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                    <option value="P"
                            @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                  </select>
                  <span class="pointer-events-none absolute bottom-3 right-3 text-slate-400">▾</span>
                  @error('jenis_kelamin')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700">Nomor Registrasi <span
                          class="text-red-600">*</span></label>
                  <input type="text"
                         required
                         name="no_regis_rs"
                         value="{{ old('no_regis_rs') }}"
                         class="{{ $field }}"
                         placeholder="Masukkan nomor registrasi">
                  @error('no_regis_rs')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700">Nama Suami/Istri</label>
                  <input type="text"
                         name="nama_suami_istri"
                         value="{{ old('nama_suami_istri') }}"
                         class="{{ $field }}"
                         placeholder="(Opsional)">
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700">Nama Dokter <span
                          class="text-red-600">*</span></label>
                  <input type="text"
                         required
                         name="nama_dokter"
                         value="{{ old('nama_dokter') }}"
                         class="{{ $field }}"
                         placeholder="Nama dokter penanggung jawab">
                  @error('nama_dokter')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700">Nama Pasien <span
                          class="text-red-600">*</span></label>
                  <input type="text"
                         required
                         name="nama_pasien"
                         value="{{ old('nama_pasien') }}"
                         class="{{ $field }}"
                         placeholder="Nama lengkap pasien">
                  @error('nama_pasien')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <div>
                  <label class="block text-sm font-medium text-slate-700">Nomor Telepon <span
                          class="text-red-600">*</span></label>
                  <input type="tel"
                         name="nomor_telepon"
                         value="{{ old('nomor_telepon') }}"
                         required
                         pattern="^[0-9+\s()-]{8,20}$"
                         class="{{ $field }}"
                         placeholder="08xxxxxxxxxx">
                  @error('nomor_telepon')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700">Email <span
                          class="text-red-600">*</span></label>
                  <input type="email"
                         name="email"
                         value="{{ old('email') }}"
                         required
                         class="{{ $field }}"
                         placeholder="nama@email.com">
                  @error('email')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="mt-8 flex justify-end">
                <button type="button"
                        class="rounded-xl bg-red-600 px-5 py-3 text-white shadow hover:bg-red-700"
                        onclick="nextFromStep1()">Berikutnya</button>
              </div>
            </div>

            {{-- STEP 2 --}}
            <div class="step hidden"
                 id="step-2">
              <div class="rounded-2xl border border-slate-200 p-5 md:p-6">
                <div class="grid gap-5 sm:grid-cols-2">
                  <div class="relative sm:col-span-1">
                    <div>
                      <label class="block text-sm font-medium text-slate-700">Tanggal Diperlukan
                        <span class="text-red-600">*</span></label>
                      <input type="date"
                             name="tanggal_diperlukan"
                             value="{{ old('tanggal_diperlukan') }}"
                             required
                             class="{{ $field }}">
                    </div>
                  </div>

                  <div class="relative">
                    <label class="block text-sm font-medium text-slate-700">Apakah Pernah Diperiksa
                      Serologi Darah <span class="text-red-600">*</span></label>
                    <select name="pernah_serologi"
                            required
                            class="{{ $select }}">
                      <option value=""
                              disabled
                              selected>Pilih Status</option>
                      <option value="Ya"
                              @selected(old('pernah_serologi') === 'Ya')>Ya</option>
                      <option value="Tidak"
                              @selected(old('pernah_serologi') === 'Tidak')>Tidak</option>
                    </select>
                    <span class="pointer-events-none absolute bottom-3 right-3 text-slate-400">▾</span>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-slate-700">Diagnosa Klinik <span
                            class="text-red-600">*</span></label>
                    <input type="text"
                           name="diagnosa_klinik"
                           value="{{ old('diagnosa_klinik') }}"
                           required
                           class="{{ $field }}"
                           placeholder="Misal: Anemia berat">
                  </div>

                  <div class="relative">
                    <label class="block text-sm font-medium text-slate-700">Lokasi Di Periksa Serologi
                      <span class="text-red-600">*</span></label>
                    <select name="lokasi_serologi"
                            required
                            class="{{ $select }}">
                      <option value=""
                              disabled
                              selected>Pilih Status</option>
                      <option>Internal RS</option>
                      <option>Laboratorium Rujukan</option>
                    </select>
                    <span class="pointer-events-none absolute bottom-3 right-3 text-slate-400">▾</span>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal Transfusi <span
                            class="text-red-600">*</span></label>
                    <input type="date"
                           name="tanggal_transfusi"
                           value="{{ old('tanggal_transfusi') }}"
                           required
                           class="{{ $field }}">
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal serologi <span
                            class="text-red-600">*</span></label>
                    <input type="date"
                           name="tanggal_serologi"
                           value="{{ old('tanggal_serologi') }}"
                           required
                           class="{{ $field }}">
                  </div>

                  <div class="sm:col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Alasan Transfusi <span
                            class="text-red-600">*</span></label>
                    <textarea name="alasan_transfusi"
                              class="{{ $textarea }}"
                              required
                              placeholder="Alasan klinis transfusi…">{{ old('alasan_transfusi') }}</textarea>
                  </div>

                  <div class="sm:col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Hasil Periksa Serologi
                      <span class="text-red-600">*</span></label>
                    <textarea name="hasil_serologi"
                              class="{{ $textarea }}"
                              required
                              placeholder="Ringkasan hasil serologi…">{{ old('hasil_serologi') }}</textarea>
                  </div>
                </div>
              </div>

              <div class="mt-8 flex justify-between">
                <button type="button"
                        class="rounded-xl border border-slate-200 px-5 py-3 hover:bg-slate-50"
                        onclick="showStep(1)">Kembali</button>
                <button type="button"
                        class="rounded-xl bg-red-600 px-5 py-3 text-white shadow hover:bg-red-700"
                        onclick="nextAfterStep2()">Berikutnya</button>
              </div>
            </div>

            {{-- STEP 3 --}}
            <div class="step hidden"
                 id="step-3">
              <div class="mx-auto max-w-3xl rounded-2xl border border-slate-200 p-5 md:p-6">
                <h3 class="mb-4 font-semibold">Data Khusus Pasien Wanita</h3>
                <div class="space-y-5">
                  <div class="relative">
                    <label class="block text-sm font-medium text-slate-700">Jumlah Kehamilan</label>
                    <select name="jumlah_kehamilan"
                            class="{{ $select }}">
                      <option value=""
                              selected>Pilih jumlah kehamilan</option>
                      @for ($i = 0; $i <= 8; $i++)
                        <option value="{{ $i }}"
                                @selected(old('jumlah_kehamilan') == $i)>
                          {{ $i }}</option>
                      @endfor
                    </select>
                    <span class="pointer-events-none absolute bottom-3 right-3 text-slate-400">▾</span>
                  </div>

                  <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                      Pernah Mengalami Abortus
                    </label>
                    <div class="space-x-4">
                      <label class="inline-flex items-center">
                        <input type="radio"
                               name="abortus"
                               value="Ya"
                               class="{{ $check }}">
                        <span class="ml-2">Ya</span>
                      </label>
                      <label class="inline-flex items-center">
                        <input type="radio"
                               name="abortus"
                               value="Tidak"
                               class="{{ $check }}">
                        <span class="ml-2">Tidak</span>
                      </label>
                    </div>
                  </div>

                  <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                      Riwayat Penyakit Hemolitik Pada Bayi Sebelumnya
                    </label>
                    <div class="space-x-4">
                      <label class="inline-flex items-center">
                        <input type="radio"
                               name="riwayat_hemolitik"
                               value="Ya"
                               class="{{ $check }}">
                        <span class="ml-2">Ya</span>
                      </label>
                      <label class="inline-flex items-center">
                        <input type="radio"
                               name="riwayat_hemolitik"
                               value="Tidak"
                               class="{{ $check }}">
                        <span class="ml-2">Tidak</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-8 flex justify-between">
                <button type="button"
                        class="rounded-xl border border-slate-200 px-5 py-3 hover:bg-slate-50"
                        onclick="showStep(2)">Kembali</button>
                <button type="button"
                        class="rounded-xl bg-red-600 px-5 py-3 text-white shadow hover:bg-red-700"
                        onclick="nextFromStep3()">Berikutnya</button>
              </div>
            </div>

            {{-- STEP 4 --}}
            <div class="step hidden"
                 id="step-4">
              <div class="mx-auto max-w-3xl rounded-2xl border border-slate-200 p-5 md:p-6">
                <div>
                  <div class="relative mt-2">
                    <label class="block text-sm font-medium text-slate-700">Produk <span
                            class="text-red-600">*</span></label>
                    <select name="produk"
                            required
                            class="{{ $select }}">
                      <option value=""
                              disabled
                              selected>Pilih produk darah</option>
                      <option value="WB"
                              @selected(old('produk') === 'WB')>WB: Whole Blood</option>
                      <option value="PRC"
                              @selected(old('produk') === 'PRC')>PRC: Packed Red Cell
                      </option>
                      <option value="TC"
                              @selected(old('produk') === 'TC')>TC: Trombocyte Concentrate
                      </option>
                      <option value="FFP"
                              @selected(old('produk') === 'FFP')>FFP: Fresh Frozen Plasma
                      </option>
                      <option value="AHF"
                              @selected(old('produk') === 'AHF')>AHF: Cryoprecipitated AHF
                      </option>
                      <option value="LP"
                              @selected(old('produk') === 'LP')>LP: Liquid Plasma</option>
                      <option value="TCA"
                              @selected(old('produk') === 'TCA')>TC Aferesis</option>
                      <option value="PK"
                              @selected(old('produk') === 'PK')>Plasma Konvalesen</option>
                    </select>
                    <span class="pointer-events-none absolute bottom-3 right-3 text-slate-400">▾</span>
                  </div>
                  @error('produk')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                  @enderror
                </div>

                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                  <div class="relative">
                    <label class="block text-sm font-medium text-slate-700">Golongan Darah <span
                            class="text-red-600">*</span></label>
                    <select name="gol_darah"
                            required
                            class="{{ $select }}">
                      <option value=""
                              disabled
                              selected>Pilih golongan darah</option>
                      @foreach (['A', 'B', 'AB', 'O'] as $g)
                        <option value="{{ $g }}"
                                @selected(old('gol_darah') === $g)>
                          {{ $g }}</option>
                      @endforeach
                    </select>
                    <span class="pointer-events-none absolute bottom-3 right-3 text-slate-400">▾</span>
                    @error('gol_darah')
                      <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="relative">
                    <label class="block text-sm font-medium text-slate-700">Rhesus <span
                            class="text-red-600">*</span></label>
                    <select name="rhesus"
                            required
                            class="{{ $select }}">
                      <option value=""
                              disabled
                              selected>Pilih rhesus</option>
                      @foreach (['Rh+', 'Rh-'] as $r)
                        <option value="{{ $r }}"
                                @selected(old('rhesus') === $r)>
                          {{ $r }}</option>
                      @endforeach
                    </select>
                    <span class="pointer-events-none absolute bottom-3 right-3 text-slate-400">▾</span>
                    @error('rhesus')
                      <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="relative mt-6">
                  <label class="block text-sm font-medium text-slate-700">Jumlah Darah yang Diminta <span
                          class="text-red-600">*</span></label>
                  <select required
                          name="jumlah_kantong"
                          class="{{ $select }}">
                    <option value=""
                            disabled
                            selected>Pilih jumlah kantong darah</option>
                    @for ($i = 1; $i <= 4; $i++)
                      <option value="{{ $i }}">{{ $i }} kantong</option>
                    @endfor
                  </select>
                  <span class="pointer-events-none absolute bottom-3 right-3 text-slate-400">▾</span>
                  @error('jumlah_kantong')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                  @enderror
                </div>

                <div class="mt-6">
                  <label class="block text-sm font-medium text-slate-700">Alasan Tambahan</label>
                  <input type="text"
                         name="alasan_tambahan"
                         value="{{ old('alasan_tambahan') }}"
                         class="{{ $field }}"
                         placeholder="(Opsional) Alasan tambahan singkat">
                </div>
              </div>

              <div class="mt-8 flex justify-between">
                <button type="button"
                        class="rounded-xl border border-slate-200 px-5 py-3 hover:bg-slate-50"
                        onclick="backFromStep4()">Kembali</button>
                <button type="button"
                        class="rounded-xl bg-red-600 px-5 py-3 text-white shadow hover:bg-red-700"
                        onclick="openConfirmModal()">
                  Preview & Kirim
                </button>
              </div>
            </div>

          </div>
        </form>
      </div>
  </div>

  <!-- MODAL KONFIRMASI (Preview) -->
  <div id="confirmModal"
       class="fixed inset-0 z-[9999] hidden items-center justify-center">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/40"
         onclick="closeConfirmModal()"></div>

    <!-- Container -->
    <div class="relative z-10 w-[94%] max-w-3xl rounded-3xl bg-white p-0 shadow-xl">
      <!-- Header -->
      <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <div>
          <h3 class="text-xl font-bold text-slate-800">Konfirmasi Pemesanan</h3>
          <p class="mt-1 text-sm text-slate-500">Periksa kembali data pemesanan Anda</p>
        </div>
        <button class="rounded-lg p-2 text-slate-400 hover:bg-slate-50 hover:text-slate-600"
                onclick="closeConfirmModal()">
          <svg class="h-5 w-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Body (scroll area) -->
      <div class="max-h-[70vh] overflow-y-auto px-6 py-6">
        <!-- GRID 2 kolom -->
        <div class="grid gap-8 divide-y divide-slate-100 sm:grid-cols-2 sm:divide-x sm:divide-y-0">
          <!-- A -->
          <div class="sm:pr-8">
            <h4 class="text-base font-semibold text-slate-800">A. Data Pasien & RS</h4>
            <dl class="mt-3 space-y-2 text-sm">
              <div class="flex">
                <dt class="w-40 shrink-0 font-medium text-slate-500">Rumah Sakit</dt>
                <dd id="cf_rs_pemesan"
                    class="min-w-0 flex-1 break-words text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Jenis Kelamin</dt>
                <dd id="cf_jenis_kelamin"
                    class="min-w-0 flex-1 font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">No. Registrasi</dt>
                <dd id="cf_no_regis_rs"
                    class="min-w-0 flex-1 break-words font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Nama Dokter</dt>
                <dd id="cf_nama_dokter"
                    class="min-w-0 flex-1 break-words font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Nama Pasien</dt>
                <dd id="cf_nama_pasien"
                    class="min-w-0 flex-1 break-words font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Suami/Istri</dt>
                <dd id="cf_nama_suami_istri"
                    class="min-w-0 flex-1 break-words font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Telepon</dt>
                <dd id="cf_nomor_telepon"
                    class="min-w-0 flex-1 break-words font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Email</dt>
                <dd id="cf_email"
                    class="min-w-0 flex-1 truncate font-medium text-slate-800"
                    title=""></dd>
              </div>
            </dl>
          </div>

          <!-- B -->
          <div class="sm:pl-8">
            <h4 class="mb-2 text-base font-semibold text-slate-800">B. Detail Klinis</h4>
            <dl class="space-y-2 text-sm">
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Tgl Diperlukan</dt>
                <dd id="cf_tgl_diperlukan"
                    class="min-w-0 flex-1 font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Pernah Serologi</dt>
                <dd id="cf_pernah_serologi"
                    class="min-w-0 flex-1 font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Diagnosa</dt>
                <dd id="cf_diagnosa_klinik"
                    class="min-w-0 flex-1 break-words font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Lokasi Serologi</dt>
                <dd id="cf_lokasi_serologi"
                    class="min-w-0 flex-1 break-words font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Tgl Serologi</dt>
                <dd id="cf_tanggal_serologi"
                    class="min-w-0 flex-1 font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Alasan Transfusi</dt>
                <dd id="cf_alasan_transfusi"
                    class="min-w-0 flex-1 break-words font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Hasil Serologi</dt>
                <dd id="cf_hasil_serologi"
                    class="min-w-0 flex-1 break-words font-medium text-slate-800"></dd>
              </div>
            </dl>
          </div>

          <!-- C (default span-2; saat D muncul jadi kolom kiri) -->
          <div id="section-permintaan"
               class="sm:col-span-2 sm:pr-8">
            <h4 class="mb-2 text-base font-semibold text-slate-800">C. Permintaan Darah</h4>
            <dl class="space-y-2 text-sm">
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Jenis Darah</dt>
                <dd id="cf_jenis_darah"
                    class="min-w-0 flex-1 break-words font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Golongan Darah</dt>
                <dd id="cf_gol_darah"
                    class="min-w-0 flex-1 font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Rhesus</dt>
                <dd id="cf_rhesus"
                    class="min-w-0 flex-1 font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Jumlah Kantong</dt>
                <dd id="cf_jumlah_kantong"
                    class="min-w-0 flex-1 font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Alasan Tambahan</dt>
                <dd id="cf_alasan_tambahan"
                    class="min-w-0 flex-1 break-words font-medium text-slate-800"></dd>
              </div>
            </dl>
          </div>

          <!-- D (WANITA) — default hidden; saat tampil jadi kolom kanan) -->
          <div id="section-wanita"
               class="hidden sm:pl-8">
            <h4 class="mb-2 text-base font-semibold text-slate-800">D. Data Khusus Pasien Wanita</h4>
            <dl class="space-y-2 text-sm">
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Jumlah Kehamilan</dt>
                <dd id="cf_jumlah_kehamilan"
                    class="min-w-0 flex-1 font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Pernah Abortus</dt>
                <dd id="cf_abortus"
                    class="min-w-0 flex-1 font-medium text-slate-800"></dd>
              </div>
              <div class="flex">
                <dt class="w-40 shrink-0 text-slate-500">Riwayat Hemolitik pada Bayi</dt>
                <dd id="cf_riwayat_hemolitik"
                    class="min-w-0 flex-1 font-medium text-slate-800"></dd>
              </div>
            </dl>
          </div>

          <!-- Alert full width -->
          <div class="sm:col-span-2">
            <div class="mt-6 rounded-lg bg-amber-50 px-4 py-3 text-xs text-amber-800 sm:text-sm">
              Pastikan semua data sudah benar. Klik <b>Kirim Sekarang</b> untuk mengirim formulir.
            </div>
          </div>
        </div> <!-- /grid -->
      </div> <!-- /body -->

      <!-- Footer (DI LUAR grid & body) -->
      <div class="flex w-full items-center justify-end gap-2 border-t border-slate-200 px-6 py-4">
        <button type="button"
                class="rounded-lg border border-slate-300 px-4 py-2 text-slate-700 hover:bg-slate-50"
                onclick="closeConfirmModal()">Perbaiki</button>
        <button type="button"
                class="rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700"
                onclick="finalSubmit()">Kirim Sekarang</button>
      </div>
    </div>
  </div>
  {{-- Success Modal (dirender hanya jika ada flash success) --}}
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

          <h3 class="text-2xl font-bold text-slate-800">Pemesanan Berhasil!</h3>
          <p class="mx-auto mt-3 max-w-sm text-slate-600">Kami akan memproses permintaan Anda dan mengirimkan
            konfirmasi melalui email.</p>
        </div>

        <div class="grid grid-cols-2 divide-x divide-slate-100 border-t border-slate-100">
          <button type="button"
                  class="px-6 py-4 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
                  onclick="hideSuccessModal()">Tutup</button>
          <a href="{{ route('pemesanan.create') }}"
             class="bg-red-600 px-6 py-4 text-center text-sm font-medium text-white transition hover:bg-red-700">Pesan
            Lagi</a>
        </div>
      </div>
    </div>

    @php
      // Mencegah session flash message muncul sebagai notifikasi terpisah
      Session::forget('success');
    @endphp
  @endif

  </div>
  </section>

  <x-footer bg="bg-slate-50" />
  </div>

  {{-- SCRIPTS --}}
  <script>
    let currentStep = 1;
    const titleMap = {
      1: 'Data Pasien & Rumah Sakit',
      2: 'Data Pemesanan',
      3: 'Data Khusus Pasien Wanita',
      4: 'Pemesanan'
    };

    function showStep(n) {
      document.querySelectorAll('.step').forEach(el => el.classList.add('hidden'));
      document.getElementById('step-' + n).classList.remove('hidden');
      currentStep = n;
      document.getElementById('stepTitle').textContent = titleMap[n];
      document.getElementById('pageNumber').textContent = n;
    }

    function validateStep1() {
      const required = ['rs_pemesan', 'jenis_kelamin', 'no_regis_rs', 'nama_dokter', 'nama_pasien', 'nomor_telepon',
        'email'
      ];
      let ok = true;
      required.forEach(name => {
        const el = document.querySelector(`[name="${name}"]`);
        if (!el || !el.value.trim()) {
          ok = false;
          if (el) el.style.borderColor = 'red';
        } else {
          el.style.borderColor = '';
        }
      });
      const email = document.querySelector('[name="email"]');
      if (email && email.value && !/^\S+@\S+\.\S+$/.test(email.value)) {
        ok = false;
        email.style.borderColor = 'red';
      }
      if (!ok) alert('Harap isi semua field yang wajib diisi dengan benar.');
      return ok;
    }

    function validateStep2() {
      const tanggalDiperlukan = document.querySelector('[name="tanggal_diperlukan"]');
      const pernahSerologi = document.querySelector('[name="pernah_serologi"]');
      const diagnosaKlinik = document.querySelector('[name="diagnosa_klinik"]');
      const lokasiSerologi = document.querySelector('[name="lokasi_serologi"]');
      const tanggalTransfusi = document.querySelectorAll('[name="tanggal_transfusi"]');
      const tanggalSerologi = document.querySelectorAll('[name="tanggal_serologi"]');
      const alasanTransfusi = document.querySelector('[name="alasan_transfusi"]');
      const hasilSerologi = document.querySelector('[name="hasil_serologi"]');

      let isValid = true;

      if (!tanggalDiperlukan.value.trim()) {
        tanggalDiperlukan.style.borderColor = 'red';
        isValid = false;
      } else {
        tanggalDiperlukan.style.borderColor = '';
      }
      if (!pernahSerologi.value.trim()) {
        pernahSerologi.style.borderColor = 'red';
        isValid = false;
      } else {
        pernahSerologi.style.borderColor = '';
      }
      if (!diagnosaKlinik.value.trim()) {
        diagnosaKlinik.style.borderColor = 'red';
        isValid = false;
      } else {
        diagnosaKlinik.style.borderColor = '';
      }

      if (pernahSerologi.value === 'Ya') {
        if (!lokasiSerologi.value.trim()) {
          lokasiSerologi.style.borderColor = 'red';
          isValid = false;
        } else {
          lokasiSerologi.style.borderColor = '';
        }
        const tanggalSerologiInput = tanggalSerologi[tanggalSerologi.length - 1];
        if (!tanggalSerologiInput.value.trim()) {
          tanggalSerologiInput.style.borderColor = 'red';
          isValid = false;
        } else {
          tanggalSerologiInput.style.borderColor = '';
        }
        if (!hasilSerologi.value.trim()) {
          hasilSerologi.style.borderColor = 'red';
          isValid = false;
        } else {
          hasilSerologi.style.borderColor = '';
        }
      } else {
        // ✅ perbaikan: bersihkan style pada input tanggal serologi (pakai tanggalSerologi, bukan tanggalTransfusi)
        const tanggalSerologiInput = tanggalSerologi[tanggalSerologi.length - 1];
        if (tanggalSerologiInput) tanggalSerologiInput.style.borderColor = '';
        hasilSerologi.style.borderColor = '';
        lokasiSerologi.style.borderColor = '';
      }

      if (!alasanTransfusi.value.trim()) {
        alasanTransfusi.style.borderColor = 'red';
        isValid = false;
      } else {
        alasanTransfusi.style.borderColor = '';
      }

      if (!isValid) alert('Harap isi semua field yang wajib diisi sebelum melanjutkan.');
      return isValid;
    }

    function validateStep4() {
      const produkSelect = document.querySelector('[name="produk"]');
      const jumlahKantong = document.querySelector('[name="jumlah_kantong"]');
      const alasanTransfusi = document.querySelector('[name="alasan_transfusi"]');
      const golDarah = document.querySelector('[name="gol_darah"]');
      const rhesus = document.querySelector('[name="rhesus"]');

      let isValid = true;

      if (!produkSelect.value.trim()) {
        produkSelect.style.borderColor = 'red';
        isValid = false;
      } else {
        produkSelect.style.borderColor = '';
      }
      if (!golDarah.value.trim()) {
        golDarah.style.borderColor = 'red';
        isValid = false;
      } else {
        golDarah.style.borderColor = '';
      }
      if (!rhesus.value.trim()) {
        rhesus.style.borderColor = 'red';
        isValid = false;
      } else {
        rhesus.style.borderColor = '';
      }
      if (!jumlahKantong.value.trim()) {
        jumlahKantong.style.borderColor = 'red';
        isValid = false;
      } else {
        jumlahKantong.style.borderColor = '';
      }
      if (!alasanTransfusi.value.trim()) {
        alasanTransfusi.style.borderColor = 'red';
        isValid = false;
      } else {
        alasanTransfusi.style.borderColor = '';
      }

      if (!isValid) alert('Harap isi semua field yang wajib diisi sebelum mengirim formulir.');
      return isValid;
    }

    function nextFromStep1() {
      if (validateStep1()) showStep(2);
    }

    function nextAfterStep2() {
      if (validateStep2()) {
        const g = document.getElementById('jenis_kelamin').value;
        if (g === 'P') showStep(3);
        else showStep(4);
      }
    }

    function nextFromStep3() {
      showStep(4);
    }

    function backFromStep4() {
      const g = document.getElementById('jenis_kelamin').value;
      if (g === 'P') showStep(3);
      else showStep(2);
    }

    function selectSingleJenisDarah(selectedCheckbox) {
      document.querySelectorAll('input[name="produk_multi[]"]').forEach(cb => {
        if (cb !== selectedCheckbox) cb.checked = false;
      });
    }

    // ===== Modal Preview =====
    function openConfirmModal() {
      if (!validateStep1()) {
        showStep(1);
        return;
      }
      if (!validateStep2()) {
        showStep(2);
        return;
      }
      if (!validateStep4()) {
        showStep(4);
        return;
      }

      const f = document.getElementById('multiStepForm');
      const val = (name) => (f.querySelector(`[name="${name}"]`)?.value || '').trim();

      const produkMap = {
        WB: 'WB: Whole Blood',
        PRC: 'PRC: Packed Red Cell',
        TC: 'TC: Trombocyte Concentrate',
        FFP: 'FFP: Fresh Frozen Plasma',
        AHF: 'AHF: Cryoprecipitated AHF',
        LP: 'LP: Liquid Plasma',
        TCA: 'TC Aferesis',
        PK: 'Plasma Konvalesen'
      };

      // ——— A. Data Pasien & RS
      setCF('cf_rs_pemesan', val('rs_pemesan'));
      const genderText = normalizeGender(val('jenis_kelamin'));
      setCF('cf_jenis_kelamin', genderText);
      setCF('cf_no_regis_rs', val('no_regis_rs'));
      setCF('cf_nama_dokter', val('nama_dokter'));
      setCF('cf_nama_pasien', val('nama_pasien'));
      setCF('cf_nama_suami_istri', val('nama_suami_istri'));
      setCF('cf_nomor_telepon', val('nomor_telepon'));
      setCF('cf_email', val('email'), {
        tooltip: true
      }); // truncate + title

      // ——— B. Detail Klinis
      setCF('cf_tgl_diperlukan', val('tanggal_diperlukan'));
      setCF('cf_pernah_serologi', val('pernah_serologi'));
      setCF('cf_diagnosa_klinik', val('diagnosa_klinik'));
      setCF('cf_lokasi_serologi', val('lokasi_serologi'));
      setCF('cf_tanggal_serologi', val('tanggal_serologi'));
      setCF('cf_alasan_transfusi', val('alasan_transfusi'));
      setCF('cf_hasil_serologi', val('hasil_serologi'));

      // ——— C. Permintaan Darah
      const kode = val('produk');
      setCF('cf_jenis_darah', produkMap[kode] || kode || '');
      setCF('cf_gol_darah', val('gol_darah'));
      setCF('cf_rhesus', val('rhesus'));
      setCF('cf_jumlah_kantong', val('jumlah_kantong') ? `${val('jumlah_kantong')} kantong` : '');
      setCF('cf_alasan_tambahan', val('alasan_tambahan'));

      // ——— D. Wanita + layout toggle (C/D)
      const secC = document.getElementById('section-permintaan');
      const secW = document.getElementById('section-wanita');

      if (secC && secW) {
        if (genderText === 'Perempuan') {
          // Isi nilai step 3
          const jk = val('jumlah_kehamilan'); // "0..8" atau ""
          const ab = val('abortus'); // "Ya"/"Tidak"
          const rh = val('riwayat_hemolitik'); // "Ya"/"Tidak"

          setCF('cf_jumlah_kehamilan', jk !== '' ? `${jk} kali` : '—');
          setCF('cf_abortus', ab || '—');
          setCF('cf_riwayat_hemolitik', rh || '—');

          // Tampilkan D (kolom kanan), C jadi kolom kiri (bukan span-2)
          secW.classList.remove('hidden');
          secW.classList.add('sm:pl-8');

          secC.classList.remove('sm:col-span-2');
          secC.classList.add('sm:pr-8');
        } else {
          // Sembunyikan D, lebarkan C kembali
          secW.classList.add('hidden');
          secW.classList.remove('sm:pl-8');

          secC.classList.add('sm:col-span-2');
          secC.classList.remove('sm:pr-8');
        }
      }

      // tampilkan modal
      const m = document.getElementById('confirmModal');
      m.classList.remove('hidden');
      m.classList.add('flex');
    }

    // helper sudah kamu punya, tapi kalau perlu:
    function setCF(id, text, opts = {}) {
      const el = document.getElementById(id);
      if (!el) return;
      const safe = (text && String(text).length) ? text : '—';
      el.textContent = safe;
      if (opts.tooltip) el.title = (safe === '—') ? '' : safe;
    }

    function normalizeGender(raw) {
      const v = (raw || '').toString().trim().toLowerCase();
      if (v === 'l' || v === 'laki-laki' || v === 'laki laki' || v === 'pria') return 'Laki-laki';
      if (v === 'p' || v === 'perempuan' || v === 'wanita') return 'Perempuan';
      return raw || '—';
    }
    // set content + optional tooltip (untuk email)
    function setCF(id, text, opts = {}) {
      const el = document.getElementById(id);
      if (!el) return;
      const safe = (text && String(text).length) ? text : '—';
      el.textContent = safe;

      // handle tooltip untuk truncate (pilihanmu: 2B)
      if (opts.tooltip) {
        el.setAttribute('title', safe === '—' ? '' : safe);
      }
    }

    // Terima 'L','P' atau 'Laki-laki','Perempuan' (fix kasus Perempuan kosong)
    function normalizeGender(raw) {
      const v = (raw || '').toString().trim().toLowerCase();
      if (v === 'l' || v === 'laki-laki' || v === 'laki laki' || v === 'pria') return 'Laki-laki';
      if (v === 'p' || v === 'perempuan' || v === 'wanita') return 'Perempuan';
      return raw || '—';
    }

    function closeConfirmModal() {
      const m = document.getElementById('confirmModal');
      m.classList.add('hidden');
      m.classList.remove('flex');
    }

    function finalSubmit() {
      document.getElementById('multiStepForm').submit();
    }

    // ===== Modal sukses =====
    document.addEventListener('DOMContentLoaded', () => {
      // Selalu mulai di Step 1
      showStep(1);

      // Jika server mengirim flash success, tampilkan modal
      @if (session('success'))
        showSuccessModal(@json(session('success')));
      @endif
    });

    function closeSuccessModal() {
      const m = document.getElementById('successModal');
      if (m) {
        m.classList.remove('hidden');
        m.classList.add('flex');
      }
    }

    function showSuccessModal(message) {
      const modal = document.getElementById('successModal');
      if (!modal) return;
      const msgEl = document.getElementById('successMessage');
      if (message && msgEl) msgEl.textContent = message;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function hideSuccessModal() {
      const modal = document.getElementById('successModal');
      if (!modal) return;
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  </script>
@endsection
