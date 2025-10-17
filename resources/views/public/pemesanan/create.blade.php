{{-- resources/views/public/pemesanan/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Form Pemesanan Darah')

@section('content')
  <div class="min-h-screen bg-white text-slate-800">
    <x-navbar />

    <section class="py-10 sm:py-14">
      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        {{-- Banner flash (optional) --}}
        @if (session('success'))
          <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
            {{ session('success') }}
          </div>
        @endif

        @php
          $field =
              'w-full h-11 md:h-12 rounded-xl bg-slate-50/70 border border-slate-200 px-4 text-[15px] placeholder-slate-400 shadow-inner focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition';
          $select = $field . ' pr-10 appearance-none';
          $textarea =
              'w-full min-h-[120px] rounded-xl bg-slate-50/70 border border-slate-200 p-4 text-[15px] placeholder-slate-400 shadow-inner focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition';
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
              class="rounded-3xl border border-slate-200 bg-white shadow-[0_10px_40px_-10px_rgba(0,0,0,0.12)]">
          @csrf

          {{-- Header --}}
          <div class="flex items-center justify-between border-b border-slate-100 p-5 sm:p-6">
            <div class="flex items-center gap-3">
              <div class="grid h-9 w-9 place-items-center rounded-xl bg-red-600 text-white shadow ring-4 ring-white">
                🩸</div>
              <h1 id="stepTitle"
                  class="text-lg font-bold sm:text-xl">Data Pasien & Rumah Sakit</h1>
            </div>
            <div class="hidden items-center gap-3 text-sm text-slate-500 sm:flex">
              <span>Page</span>
              <span id="pageNumber"
                    class="text-lg font-semibold text-red-500">1</span>
            </div>
          </div>

          {{-- BODY --}}
          <div class="space-y-6 p-5 sm:p-6">

            {{-- STEP 1 --}}
            <div class="step"
                 id="step-1">
              <div class="grid gap-5 sm:grid-cols-2">
                <div>
                  <label class="block text-sm font-medium text-slate-700">Rumah Sakit <span
                          class="text-red-600">*</span></label>
                  <input type="text"
                         required
                         name="rs_pemesan"
                         value="{{ old('rs_pemesan') }}"
                         class="{{ $field }}"
                         placeholder="Nama rumah sakit">
                  @error('rs_pemesan')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
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

              <div class="grid gap-5 sm:grid-cols-2">
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
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
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
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
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
                      <label class="block text-sm font-medium text-slate-700">Tanggal Diperlukan <span
                              class="text-red-600">*</span></label>
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
                    <label class="block text-sm font-medium text-slate-700">Pernah Mengalami
                      Abortus</label>
                    <div class="mt-2 space-y-2">
                      <label class="flex items-center gap-2"><input type="radio"
                               name="abortus"
                               value="Ya"
                               class="{{ $check }}"> Ya</label>
                      <label class="flex items-center gap-2"><input type="radio"
                               name="abortus"
                               value="Tidak"
                               class="{{ $check }}"> Tidak</label>
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-slate-700">Riwayat Penyakit Hemolitik
                      Pada Bayi Sebelumnya</label>
                    <div class="mt-2 space-y-2">
                      <label class="flex items-center gap-2"><input type="radio"
                               name="riwayat_hemolitik"
                               value="Ya"
                               class="{{ $check }}">
                        Ya</label>
                      <label class="flex items-center gap-2"><input type="radio"
                               name="riwayat_hemolitik"
                               value="Tidak"
                               class="{{ $check }}">
                        Tidak</label>
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
                  <label class="block text-sm font-medium text-slate-700">Jenis Darah <span
                          class="text-red-600">*</span></label>
                  <div class="mt-2 space-y-3">
                    @foreach (['Segar', 'Biasa', 'Cuci'] as $j)
                      <label class="flex items-center gap-2">
                        <input type="checkbox"
                               name="produk_multi[]"
                               value="{{ $j }}"
                               class="{{ $check }}"
                               onclick="selectSingleJenisDarah(this)">
                        <span>{{ $j }}</span>
                      </label>
                    @endforeach
                  </div>
                  @error('produk_multi')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                  @enderror
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
                  <label class="block text-sm font-medium text-slate-700">Alasan Transfusi
                    (Tambahan)</label>
                  <div class="mt-2 space-y-3">
                    @foreach (['Plasma Biasa', 'FFP (Fresh Frozen Plasma)'] as $a)
                      <label class="flex items-center gap-2">
                        <input type="checkbox"
                               name="alasan_multi[]"
                               value="{{ $a }}"
                               class="{{ $check }}"
                               onclick="selectSingleAlasanTransfusi(this)">
                        <span>{{ $a }}</span>
                      </label>
                    @endforeach
                  </div>
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

        {{-- MODAL KONFIRMASI (Preview) --}}
        <div id="confirmModal"
             class="fixed inset-0 z-[9999] hidden items-center justify-center">
          <div class="absolute inset-0 bg-black/40"
               onclick="closeConfirmModal()"></div>

          <div class="relative z-10 w-[94%] max-w-3xl rounded-2xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-200 p-4">
              <h3 class="text-lg font-semibold text-slate-800">Konfirmasi Pemesanan</h3>
              <button class="text-slate-400 hover:text-slate-600"
                      onclick="closeConfirmModal()">✕</button>
            </div>

            <div class="max-h-[70vh] overflow-y-auto p-4 sm:p-6">
              <div class="grid gap-6 sm:grid-cols-2">
                <div>
                  <h4 class="mb-2 font-semibold text-slate-700">A. Pasien & RS</h4>
                  <dl class="space-y-2 text-sm">
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Rumah Sakit</dt>
                      <dd id="cf_rs_pemesan"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Jenis Kelamin</dt>
                      <dd id="cf_jenis_kelamin"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">No. Registrasi</dt>
                      <dd id="cf_no_regis_rs"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Nama Dokter</dt>
                      <dd id="cf_nama_dokter"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Nama Pasien</dt>
                      <dd id="cf_nama_pasien"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Suami/Istri</dt>
                      <dd id="cf_nama_suami_istri"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Telepon</dt>
                      <dd id="cf_nomor_telepon"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Email</dt>
                      <dd id="cf_email"
                          class="font-medium"></dd>
                    </div>
                  </dl>
                </div>

                <div>
                  <h4 class="mb-2 font-semibold text-slate-700">B. Detail Klinis</h4>
                  <dl class="space-y-2 text-sm">
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Tgl Diperlukan</dt>
                      <dd id="cf_tgl_diperlukan"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Pernah Serologi</dt>
                      <dd id="cf_pernah_serologi"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Diagnosa</dt>
                      <dd id="cf_diagnosa_klinik"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Lokasi Serologi</dt>
                      <dd id="cf_lokasi_serologi"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Tgl Serologi</dt>
                      <dd id="cf_tgl_serologi"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Alasan Transfusi</dt>
                      <dd id="cf_alasan_transfusi"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Hasil Serologi</dt>
                      <dd id="cf_hasil_serologi"
                          class="font-medium"></dd>
                    </div>
                  </dl>
                </div>

                <div class="sm:col-span-2">
                  <h4 class="mb-2 font-semibold text-slate-700">C. Permintaan Darah</h4>
                  <dl class="space-y-2 text-sm">
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Jenis Darah</dt>
                      <dd id="cf_jenis_darah"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Jumlah Kantong</dt>
                      <dd id="cf_jumlah_kantong"
                          class="font-medium"></dd>
                    </div>
                    <div class="flex">
                      <dt class="w-40 text-slate-500">Alasan Tambahan</dt>
                      <dd id="cf_alasan_tambahan"
                          class="font-medium"></dd>
                    </div>
                  </dl>
                </div>
              </div>

              <p class="mt-6 rounded-md bg-amber-50 px-3 py-2 text-xs text-amber-700">
                Pastikan semua data sudah benar. Klik <b>Kirim Sekarang</b> untuk mengirim formulir.
              </p>
            </div>

            <div class="flex justify-end gap-2 border-t border-slate-200 p-4">
              <button type="button"
                      class="rounded-lg border border-slate-300 px-4 py-2 text-slate-700 hover:bg-slate-50"
                      onclick="closeConfirmModal()">Perbaiki</button>
              <button type="button"
                      class="rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700"
                      onclick="finalSubmit()">Kirim Sekarang</button>
            </div>
          </div>
        </div>
        {{-- /MODAL KONFIRMASI --}}

         {{-- Success Modal --}}
    <div id="successModal"
         class="fixed inset-0 z-[9999] hidden items-center justify-center">
      <div class="absolute inset-0 bg-black/40"></div>

      <div class="relative z-10 w-[90%] max-w-2xl rounded-3xl bg-white p-10 text-center shadow-2xl">
        <button type="button"
                class="absolute right-4 top-4 text-2xl text-slate-400 hover:text-slate-600"
                onclick="hideSuccessModal()">✕</button>

        <div
             class="mx-auto mb-4 grid h-16 w-16 place-items-center rounded-full bg-emerald-100 text-3xl text-emerald-700">
          ✔
        </div>

        <!-- GANTI JUDUL DI SINI -->
        <h3 id="successTitle"
            class="text-2xl font-bold text-slate-800">
          Pengajuan Berhasil Dikirim!
        </h3>

        <!-- GANTI PESAN DI SINI -->
        <p id="successMessage"
           class="mt-3 text-lg text-slate-600">
          Silakan cek email secara berkala untuk informasi selanjutnya.
        </p>

        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
          <button type="button"
                  class="rounded-lg border border-slate-300 px-6 py-3 text-slate-700 transition hover:bg-slate-50"
                  onclick="hideSuccessModal()">Tutup</button>
          <a href="{{ route('public.event.create') }}"
             class="rounded-lg bg-red-600 px-6 py-3 text-white transition hover:bg-red-700">Ajukan Lagi</a>
        </div>
      </div>
    </div>

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
        const tanggalSerologiInput = tanggalTransfusi[tanggalTransfusi.length - 1];
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
        lokasiSerologi.style.borderColor = '';
        const tanggalSerologiInput = tanggalTransfusi[tanggalTransfusi.length - 1];
        tanggalSerologiInput.style.borderColor = '';
        hasilSerologi.style.borderColor = '';
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
      const produkCheckboxes = document.querySelectorAll('input[name="produk_multi[]"]');
      const jumlahKantong = document.querySelector('[name="jumlah_kantong"]');
      const alasanTransfusi = document.querySelector('[name="alasan_transfusi"]');

      let isValid = true;
      const produkSelected = Array.from(produkCheckboxes).some(cb => cb.checked);
      if (!produkSelected) {
        alert('Harap pilih setidaknya satu jenis darah.');
        isValid = false;
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

    function selectSingleAlasanTransfusi(selectedCheckbox) {
      document.querySelectorAll('input[name="alasan_multi[]"]').forEach(cb => {
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
      const vals = (name) => Array.from(f.querySelectorAll(`[name="${name}"]`)).map(i => i.value).filter(Boolean);
      const checkedVals = (name) => Array.from(f.querySelectorAll(`[name="${name}"]:checked`)).map(i => i.value);

      const tglAll = vals('tanggal_transfusi'); // 3 input dengan name yang sama
      const tglDiperlukan = tglAll[0] || '';
      const tglSerologi = tglAll[tglAll.length - 1] || '';

      setCF('cf_rs_pemesan', val('rs_pemesan'));
      setCF('cf_jenis_kelamin', ({
        'L': 'Laki-laki',
        'P': 'Perempuan'
      } [val('jenis_kelamin')] || ''));
      setCF('cf_no_regis_rs', val('no_regis_rs'));
      setCF('cf_nama_dokter', val('nama_dokter'));
      setCF('cf_nama_pasien', val('nama_pasien'));
      setCF('cf_nama_suami_istri', val('nama_suami_istri'));
      setCF('cf_nomor_telepon', val('nomor_telepon'));
      setCF('cf_email', val('email'));

      setCF('cf_tgl_diperlukan', tglDiperlukan);
      setCF('cf_pernah_serologi', val('pernah_serologi'));
      setCF('cf_diagnosa_klinik', val('diagnosa_klinik'));
      setCF('cf_lokasi_serologi', val('lokasi_serologi'));
      setCF('cf_tgl_serologi', tglSerologi);
      setCF('cf_alasan_transfusi', val('alasan_transfusi'));
      setCF('cf_hasil_serologi', val('hasil_serologi'));

      const jenisDarah = checkedVals('produk_multi[]')[0] || '';
      setCF('cf_jenis_darah', jenisDarah);
      setCF('cf_jumlah_kantong', val('jumlah_kantong') ? val('jumlah_kantong') + ' kantong' : '');
      const alasanTambahan = checkedVals('alasan_multi[]')[0] || '';
      setCF('cf_alasan_tambahan', alasanTambahan);

      const m = document.getElementById('confirmModal');
      m.classList.remove('hidden');
      m.classList.add('flex');
    }

    function setCF(id, text) {
      const el = document.getElementById(id);
      if (el) el.textContent = text || '—';
    }

    function closeConfirmModal() {
      const m = document.getElementById('confirmModal');
      m.classList.add('hidden');
      m.classList.remove('flex');
    }

    function finalSubmit() {
      document.getElementById('multiStepForm').submit();
    }

    // Modal sukses: auto-open kalau ada session('success')
    document.addEventListener('DOMContentLoaded', () => {
      showStep(1);
      const m = document.getElementById('successModal');
      if (m) {
        m.classList.remove('hidden');
        m.classList.add('flex');
      }
    });

    function closeSuccessModal() {
      const m = document.getElementById('successModal');
      if (m) {
        m.classList.add('hidden');
        m.classList.remove('flex');
      }
    }

    function showSuccessModal(message) {
      const modal = document.getElementById('successModal');
      const msgEl = document.getElementById('successMessage');
      if (message && msgEl) msgEl.textContent = message;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function hideSuccessModal() {
      const modal = document.getElementById('successModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    // Auto show modal jika ada flash session "success"
    document.addEventListener('DOMContentLoaded', () => {
      @if (session('success'))
        showSuccessModal(@json(session('success')));
      @endif
    });
  </script>
@endsection
