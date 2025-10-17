@extends('layouts.app')
<x-navbar />
@section('content')

  <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Hero --}}
    <div class="mb-8 text-center">
      <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
        Ajukan Event Donor Darah
      </h1>
      <p class="mx-auto mt-2 max-w-3xl text-slate-600">
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
      <section class="rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-sm backdrop-blur-sm md:col-span-2">
        <div class="mb-5 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="grid h-9 w-9 place-items-center rounded-xl bg-red-600 text-white shadow ring-4 ring-white">A</div>
            <h2 class="text-lg font-semibold text-slate-900 sm:text-xl">Data Pemohon</h2>
          </div>
          <span class="text-xs text-slate-500">Wajib diisi</span>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
          {{-- Nama --}}
          <div>
            <label class="block text-sm font-medium text-slate-700">Nama</label>
            <input type="text"
                   name="nama"
                   required
                   value="{{ old('nama') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[15px] shadow-inner focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20">
            @error('nama')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Institusi --}}
          <div>
            <label class="block text-sm font-medium text-slate-700">Institusi Pemohon</label>
            <input type="text"
                   name="institusi_pemohon"
                   required
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
              <span class="text-[11px] text-slate-500">Contoh: 0812xxxxxxx</span>
            </div>
            <input type="text"
                   name="nomor_telefon"
                   required
                   placeholder="0812xxxxxxx"
                   value="{{ old('nomor_telefon') }}"
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
                   value="{{ old('email') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[15px] shadow-inner focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20">
            @error('email')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Surat Instansi --}}
          <div class="md:col-span-1">
            <label class="block text-sm font-medium text-slate-700">Surat Instansi (PDF/JPG/PNG)</label>
            <div class="mt-1 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/60 p-4">
              <input type="file"
                     name="surat_instansi"
                     accept=".pdf,.jpg,.jpeg,.png"
                     class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-[15px] file:mr-4 file:rounded-md file:border-0 file:bg-red-600 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-red-700 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20">
              <p class="mt-2 text-xs text-slate-500">Maksimal 2 MB. Contoh: surat permohonan kegiatan dari institusi.</p>

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
      <section class="rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-sm backdrop-blur-sm md:col-span-2">
        <div class="mb-5 flex items-center gap-3">
          <div class="grid h-9 w-9 place-items-center rounded-xl bg-red-600 text-white shadow ring-4 ring-white">B</div>
          <h2 class="text-lg font-semibold text-slate-900 sm:text-xl">Detail Event</h2>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
          <div>
            <label class="block text-sm font-medium text-slate-700">Tanggal Event</label>
            <input type="date"
                   name="tanggal_event"
                   required
                   value="{{ old('tanggal_event') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[15px] shadow-inner focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20">
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700">Jam Mulai</label>
            <input type="time"
                   name="jam_mulai"
                   value="{{ old('jam_mulai') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[15px] shadow-inner focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20">
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700">Jam Selesai</label>
            <input type="time"
                   name="jam_selesai"
                   value="{{ old('jam_selesai') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[15px] shadow-inner focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20">
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
      <section class="rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-sm backdrop-blur-sm md:col-span-2">
        <div class="mb-5 flex items-center gap-3">
          <div class="grid h-9 w-9 place-items-center rounded-xl bg-red-600 text-white shadow ring-4 ring-white">C</div>
          <h2 class="text-lg font-semibold text-slate-900 sm:text-xl">Estimasi & Kebutuhan</h2>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
          <div>
            <label class="block text-sm font-medium text-slate-700">Jumlah Peserta (perkiraan)</label>
            <input type="number"
                   name="jumlah_peserta"
                   min="1"
                   value="{{ old('jumlah_peserta') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[15px] shadow-inner focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700">Target Peserta</label>
            <select name="target_peserta"
                    class="mt-1 w-full appearance-none rounded-xl border border-slate-200 bg-white px-3 py-2.5 pr-10 text-[15px] shadow-inner focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
              <option value="">-- Pilih Target --</option>
              @foreach ($targetOptions as $t)
                <option value="{{ $t }}"
                        @selected(old('target_peserta') === $t)>{{ $t }}</option>
              @endforeach
            </select>
          </div>

          <div class="flex items-center gap-3 md:mt-7">
            <input id="butuhMU"
                   type="checkbox"
                   name="butuh_mobil_unit"
                   value="1"
                   @checked(old('butuh_mobil_unit'))
                   class="h-5 w-5 rounded border border-slate-300 text-red-600 focus:ring-2 focus:ring-red-500">
            <label for="butuhMU"
                   class="text-sm text-slate-700">Butuh Mobil Unit</label>
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
        <div class="flex items-center justify-between">
          <p class="text-xs text-slate-500"></p>
          <button id="submitBtn"
                  type="submit"
                  class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-6 py-3 text-white shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/30">

            Ajukan Penjadwalan
          </button>
        </div>
      </div>
    </form>

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

  </main>

  <x-footer />

  {{-- Scripts --}}
  <script>
    const form = document.getElementById('eventForm');
    const submitBtn = document.getElementById('submitBtn');

    function allRequiredFilled() {
      let ok = true;
      form.querySelectorAll(
        '[name="nama"],[name="institusi_pemohon"],[name="nomor_telefon"],[name="email"],[name="tanggal_event"],[name="jenis_event"]'
      ).forEach(el => {
        if (!el.value) ok = false;
      });
      return ok;
    }

    function toggleSubmit() {
      submitBtn.disabled = !allRequiredFilled();
      submitBtn.classList.toggle('opacity-60', submitBtn.disabled);
      submitBtn.classList.toggle('cursor-not-allowed', submitBtn.disabled);
    }

    form.addEventListener('input', toggleSubmit);
    document.addEventListener('DOMContentLoaded', toggleSubmit);

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
