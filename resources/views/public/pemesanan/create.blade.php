@extends('layouts.app')
@section('title', 'Form Pemesanan Darah')

@section('content')
  <div class="min-h-screen bg-white text-slate-800">
    <x-navbar />

    <section class="py-10 sm:py-14">
      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        @if (session('success'))
          <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
            {{ session('success') }}
          </div>
        @endif

        {{-- Preset style field agar konsisten --}}
        @php
          $field =
              'w-full h-11 md:h-12 rounded-xl bg-slate-50/70 border border-slate-200 px-4 text-[15px] placeholder-slate-400 shadow-inner focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition';
          $select = $field . ' pr-10 appearance-none';
          $textarea =
              'w-full min-h-[120px] rounded-xl bg-slate-50/70 border border-slate-200 p-4 text-[15px] placeholder-slate-400 shadow-inner focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition';
          $check = 'rounded text-red-600 border-slate-300 focus:ring-red-500 focus:ring-2';
        @endphp

        <form id="multiStepForm"
              method="POST"
              action="{{ route('pemesanan.store') }}"
              class="rounded-3xl border border-slate-200 bg-white shadow-[0_10px_40px_-10px_rgba(0,0,0,0.12)]">
          @csrf

          {{-- Header --}}
          <div class="flex items-center justify-between border-b border-slate-100 p-5 sm:p-6">
            <div class="flex items-center gap-3">
              <div class="grid h-9 w-9 place-items-center rounded-xl bg-red-600 text-white shadow ring-4 ring-white">🩸
              </div>
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

            {{-- STEP 1: Data Pasien & RS --}}
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
                {{-- Nomor Telepon --}}
                <div>
                  <label class="block text-sm font-medium text-slate-700">
                    Nomor Telepon <span class="text-red-600">*</span>
                  </label>
                  <input
                    type="tel"
                    name="nomor_telepon"
                    value="{{ old('nomor_telepon') }}"
                    required
                    pattern="^[0-9+\s()-]{8,20}$"
                    class="{{ $field }}"
                    placeholder="08xxxxxxxxxx"
                  >
                  @error('nomor_telepon')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                  @enderror
                </div>

                {{-- Email --}}
                <div>
                  <label class="block text-sm font-medium text-slate-700">
                    Email <span class="text-red-600">*</span>
                  </label>
                  <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="{{ $field }}"
                    placeholder="nama@email.com"
                  >
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

            {{-- STEP 2: Data Pemesanan (versi sesuai mockup) --}}
            <div class="step hidden"
                 id="step-2">
              <div class="rounded-2xl border border-slate-200 p-5 md:p-6">
                <div class="mb-4 flex items-center gap-3">

                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                  {{-- KIRI: Tanggal Diperlukan --}}
                  <div class="relative sm:col-span-1">
                    <div>
                      <label class="block text-sm font-medium text-slate-700">Tanggal Diperlukan <span
                              class="text-red-600">*</span></label>
                      <input type="date"
                             name="tanggal_transfusi"
                             value="{{ old('tanggal_transfusi') }}"
                             required
                             class="{{ $field }}">
                    </div>

                  </div>

                  {{-- KANAN ATAS: Pernah serologi (select) --}}
                  <div class="relative">
                    <label class="block text-sm font-medium text-slate-700">Apakah Pernah Diperiksa Serologi Darah <span
                            class="text-red-600">*</span></label>
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

                  {{-- KIRI MID: Diagnosa Klinik (input) --}}
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

                  {{-- KANAN MID: Lokasi serologi (select) --}}
                  <div class="relative">
                    <label class="block text-sm font-medium text-slate-700">Lokasi Di Periksa Serologi <span
                            class="text-red-600">*</span></label>
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
                  {{-- KIRI PALING BAWAH: Tanggal Transfusi (input) --}}
                  <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal Transfusi <span
                            class="text-red-600">*</span></label>
                    <input type="date"
                           name="tanggal_transfusi"
                           value="{{ old('tanggal_transfusi') }}"
                           required
                           class="{{ $field }}">
                  </div>
                  {{-- KANAN: Tanggal Periksa Serologi (select preset + date) --}}
                  <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal serologi <span
                            class="text-red-600">*</span></label>
                    <input type="date"
                           name="tanggal_transfusi"
                           value="{{ old('tanggal_transfusi') }}"
                           required
                           class="{{ $field }}">
                  </div>

                  {{-- KIRI BAWAH: Alasan transfusi (textarea) --}}
                  <div class="sm:col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Alasan Transfusi <span
                            class="text-red-600">*</span></label>
                    <textarea name="alasan_transfusi"
                              class="{{ $textarea }}"
                              required
                              placeholder="Alasan klinis transfusi…">{{ old('alasan_transfusi') }}</textarea>
                  </div>
                  {{-- KANAN PALING BAWAH: Hasil serologi (textarea) --}}
                  <div class="sm:col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Hasil Periksa Serologi <span
                            class="text-red-600">*</span></label>
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

            {{-- STEP 3: Data Khusus Wanita (hanya jika P) --}}
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
                                @selected(old('jumlah_kehamilan') == $i)>{{ $i }}</option>
                      @endfor
                    </select>
                    <span class="pointer-events-none absolute bottom-3 right-3 text-slate-400">▾</span>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-slate-700">Pernah Mengalami Abortus</label>
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
                    <label class="block text-sm font-medium text-slate-700">Riwayat Penyakit Hemolitik Pada Bayi
                      Sebelumnya</label>
                    <div class="mt-2 space-y-2">
                      <label class="flex items-center gap-2"><input type="radio"
                               name="riwayat_hemolitik"
                               value="Ya"
                               class="{{ $check }}"> Ya</label>
                      <label class="flex items-center gap-2"><input type="radio"
                               name="riwayat_hemolitik"
                               value="Tidak"
                               class="{{ $check }}"> Tidak</label>
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

            {{-- STEP 4: Pemesanan (final - jenis darah) --}}
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
                  <label class="block text-sm font-medium text-slate-700">Alasan Transfusi (Tambahan)</label>
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
                        onclick="submitForm()">
                  Kirim Formulir
                </button>
              </div>
            </div>

          </div>
        </form>
      </div>
    </section>

    <x-footer bg="bg-slate-50" />
  </div>

  {{-- Step logic + progress (menyesuaikan jika wanita/laki-laki) --}}
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
      // Update page number display instead of progress bar
      document.getElementById('pageNumber').textContent = n;
    }

    function validateStep1() {
      const requiredFields = [
        'rs_pemesan',
        'jenis_kelamin',
        'no_regis_rs',
        'nama_dokter',
        'nama_pasien'
        'nomor_telepon',   // <— ditambahkan
        'email'
      ];

      let isValid = true;
      requiredFields.forEach(field => {
        const element = document.querySelector(`[name="${field}"]`);
        if (!element.value.trim()) {
          element.style.borderColor = 'red';
          isValid = false;
        } else {
          element.style.borderColor = '';
        }
      });

      if (!isValid) {
        alert('Harap isi semua field yang wajib diisi sebelum melanjutkan.');
      }
      return isValid;
    }

    function validateStep2() {
      const tanggalDiperlukan = document.querySelector('[name="tanggal_transfusi"]');
      const pernahSerologi = document.querySelector('[name="pernah_serologi"]');
      const diagnosaKlinik = document.querySelector('[name="diagnosa_klinik"]');
      const lokasiSerologi = document.querySelector('[name="lokasi_serologi"]');
      const tanggalTransfusi = document.querySelectorAll('[name="tanggal_transfusi"]');
      const alasanTransfusi = document.querySelector('[name="alasan_transfusi"]');
      const hasilSerologi = document.querySelector('[name="hasil_serologi"]');
  
      let isValid = true;
  
      // Validate always required fields
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
  
      // If pernah_serologi is "Ya", then lokasi_serologi, tanggal_serologi, hasil_serologi are required
      if (pernahSerologi.value === 'Ya') {
        if (!lokasiSerologi.value.trim()) {
          lokasiSerologi.style.borderColor = 'red';
          isValid = false;
        } else {
          lokasiSerologi.style.borderColor = '';
        }
  
        // tanggal_serologi is the last input of tanggal_transfusi inputs (assuming order)
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
        // If pernah_serologi is not "Ya", clear error styles for these optional fields
        lokasiSerologi.style.borderColor = '';
        // tanggal_serologi input clear style
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
  
      if (!isValid) {
        alert('Harap isi semua field yang wajib diisi sebelum melanjutkan.');
      }
      return isValid;
    }

    function validateStep4() {
      const produkCheckboxes = document.querySelectorAll('input[name="produk_multi[]"]');
      const jumlahKantong = document.querySelector('[name="jumlah_kantong"]');
      const alasanTransfusi = document.querySelector('[name="alasan_transfusi"]');

      let isValid = true;

      // Check if at least one produk is selected
      const produkSelected = Array.from(produkCheckboxes).some(cb => cb.checked);
      if (!produkSelected) {
        alert('Harap pilih setidaknya satu jenis darah.');
        isValid = false;
      }

      // Check jumlah_kantong
      if (!jumlahKantong.value.trim()) {
        jumlahKantong.style.borderColor = 'red';
        isValid = false;
      } else {
        jumlahKantong.style.borderColor = '';
      }

      // Check alasan_transfusi
      if (!alasanTransfusi.value.trim()) {
        alasanTransfusi.style.borderColor = 'red';
        isValid = false;
      } else {
        alasanTransfusi.style.borderColor = '';
      }

      if (!isValid) {
        alert('Harap isi semua field yang wajib diisi sebelum mengirim formulir.');
      }
      return isValid;
    }

    function nextFromStep1() {
      if (validateStep1()) {
        showStep(2);
      }
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

    function submitForm() {
      if (validateStep4()) {
        document.getElementById('multiStepForm').submit();
      }
    }

    function backFromStep4() {
      const g = document.getElementById('jenis_kelamin').value;
      if (g === 'P') showStep(3);
      else showStep(2);
    }

    function selectSingleJenisDarah(selectedCheckbox) {
      // Uncheck all jenis darah checkboxes
      const jenisDarahCheckboxes = document.querySelectorAll('input[name="produk_multi[]"]');
      jenisDarahCheckboxes.forEach(checkbox => {
        if (checkbox !== selectedCheckbox) {
          checkbox.checked = false;
        }
      });
    }

    function selectSingleAlasanTransfusi(selectedCheckbox) {
      // Uncheck all alasan transfusi checkboxes
      const alasanTransfusiCheckboxes = document.querySelectorAll('input[name="alasan_multi[]"]');
      alasanTransfusiCheckboxes.forEach(checkbox => {
        if (checkbox !== selectedCheckbox) {
          checkbox.checked = false;
        }
      });
    }

    document.addEventListener('DOMContentLoaded', () => showStep(1));
  </script>

@endsection
