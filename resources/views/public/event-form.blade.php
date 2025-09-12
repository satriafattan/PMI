    @extends('layouts.app')
    <x-navbar />
    @section('content')

<main class="max-w-5xl mx-auto px-6 py-12">
  <div class="text-center mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Form Penjadwalan Event Donor Darah</h1>
    <p class="text-gray-600">Isi formulir di bawah untuk mengajukan penjadwalan event donor darah</p>
  </div>

  @if ($errors->any())
    <div class="mb-6 rounded bg-red-100 p-4 text-red-700">
      <ul class="ml-5 list-disc">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form
    id="eventForm"
    method="POST"
    action="{{ route('public.event.store') }}"
    class="grid gap-6 md:grid-cols-2"
  >
    @csrf

    {{-- A. Data Pemohon --}}
    <section class="md:col-span-2 bg-white shadow-lg rounded-lg p-6 border border-gray-200">
      <h2 class="mb-4 text-xl font-semibold text-red-700 flex items-center">
        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
        </svg>
        A. Data Pemohon
      </h2>
      <div class="grid gap-6 md:grid-cols-2">
        <div>
          <label class="block text-sm font-medium">Nama</label>
          <input
            type="text"
            name="nama"
            required
            value="{{ old('nama') }}"
            class="mt-1 w-full rounded border p-2 focus:outline-none focus:ring-2 focus:ring-red-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Institusi Pemohon</label>
          <input
            type="text"
            name="institusi_pemohon"
            required
            value="{{ old('institusi_pemohon') }}"
            class="mt-1 w-full rounded border p-2 focus:outline-none focus:ring-2 focus:ring-red-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Nomor Telefon</label>
          <input
            type="text"
            name="nomor_telefon"
            required
            placeholder="0812xxxxxxx"
            value="{{ old('nomor_telefon') }}"
            class="mt-1 w-full rounded border p-2 focus:outline-none focus:ring-2 focus:ring-red-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Email</label>
          <input
            type="email"
            name="email"
            required
            value="{{ old('email') }}"
            class="mt-1 w-full rounded border p-2 focus:outline-none focus:ring-2 focus:ring-red-500"
          />
        </div>
      </div>
    </section>

    {{-- B. Detail Event --}}
    <section class="md:col-span-2 bg-white shadow-lg rounded-lg p-6 border border-gray-200">
      <h2 class="mb-4 text-xl font-semibold text-red-700 flex items-center">
        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
        </svg>
        B. Detail Event
      </h2>

      <div class="grid gap-6 md:grid-cols-3">
        <div>
          <label class="block text-sm font-medium">Tanggal Event</label>
          <input
            type="date"
            name="tanggal_event"
            required
            value="{{ old('tanggal_event') }}"
            class="mt-1 w-full rounded border p-2 focus:outline-none focus:ring-2 focus:ring-red-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Jam Mulai</label>
          <input
            type="time"
            name="jam_mulai"
            value="{{ old('jam_mulai') }}"
            class="mt-1 w-full rounded border p-2 focus:outline-none focus:ring-2 focus:ring-red-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Jam Selesai</label>
          <input
            type="time"
            name="jam_selesai"
            value="{{ old('jam_selesai') }}"
            class="mt-1 w-full rounded border p-2 focus:outline-none focus:ring-2 focus:ring-red-500"
          />
        </div>
      </div>

      <div class="mt-6 grid gap-6 md:grid-cols-2">
        <div>
          <label class="block text-sm font-medium">Jenis Event</label>
          <select
            name="jenis_event"
            required
            class="mt-1 w-full rounded border bg-white p-2 focus:outline-none focus:ring-2 focus:ring-red-500"
          >
            <option value="">-- Pilih Jenis Event --</option>
            @foreach ($eventTypes as $type)
              <option value="{{ $type }}" @selected(old('jenis_event') === $type)>{{ $type }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium">Lokasi Lengkap</label>
          <textarea
            name="lokasi_lengkap"
            rows="3"
            placeholder="Alamat lengkap / nama gedung / patokan"
            class="mt-1 w-full rounded border p-2 focus:outline-none focus:ring-2 focus:ring-red-500"
          >{{ old('lokasi_lengkap') }}</textarea>
        </div>
      </div>
    </section>

    {{-- C. Estimasi & Kebutuhan --}}
    <section class="md:col-span-2 bg-white shadow-lg rounded-lg p-6 border border-gray-200">
      <h2 class="mb-4 text-xl font-semibold text-red-700 flex items-center">
        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
        </svg>
        C. Estimasi & Kebutuhan
      </h2>

      <div class="grid gap-6 md:grid-cols-3">
        <div>
          <label class="block text-sm font-medium">Jumlah Peserta (perkiraan)</label>
          <input
            type="number"
            name="jumlah_peserta"
            min="1"
            value="{{ old('jumlah_peserta') }}"
            class="mt-1 w-full rounded border p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Target Peserta</label>
          <select
            name="target_peserta"
            class="mt-1 w-full rounded border bg-white p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="">-- Pilih Target --</option>
            @foreach ($targetOptions as $t)
              <option value="{{ $t }}" @selected(old('target_peserta') === $t)>{{ $t }}</option>
            @endforeach
          </select>
        </div>

        <div class="mt-7 flex items-center gap-3 md:mt-7">
          <input
            id="butuhMU"
            type="checkbox"
            name="butuh_mobil_unit"
            value="1"
            @checked(old('butuh_mobil_unit'))
            class="h-5 w-5 rounded border"
          />
          <label for="butuhMU" class="text-sm">Butuh Mobil Unit</label>
        </div>
      </div>

      <div class="mt-6">
        <label class="block text-sm font-medium">Fasilitas Tersedia</label>
          <textarea
            name="fasilitas_tersedia"
            rows="3"
            placeholder="Contoh: Ruang ber-AC, meja & kursi, listrik, parkir, tenda"
            class="mt-1 w-full rounded border p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >{{ old('fasilitas_tersedia') }}</textarea>

        @isset($facilityHints)
          <p class="mt-1 text-xs text-slate-500">
            Saran:
            @foreach ($facilityHints as $fh)
              <span>{{ $fh }}</span>@if (! $loop->last), @endif
            @endforeach
          </p>
        @endisset
      </div>
    </section>

    {{-- D. Lainnya --}}
    <section class="md:col-span-2 bg-white shadow-lg rounded-lg p-6 border border-gray-200">
      <h2 class="mb-4 text-xl font-semibold text-red-700 flex items-center">
        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
        </svg>
        D. Lainnya
      </h2>

      <div class="grid gap-6 md:grid-cols-2">
        <div>
          <label class="block text-sm font-medium">Catatan Tambahan</label>
          <textarea
            name="catatan_tambahan"
            rows="3"
            placeholder="Info khusus, kebutuhan tambahan, dll."
            class="mt-1 w-full rounded border p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >{{ old('catatan_tambahan') }}</textarea>
        </div>

        <div class="flex items-center gap-3">
          <input
            id="izinPublikasi"
            type="checkbox"
            name="izin_publikasi"
            value="1"
            @checked(old('izin_publikasi'))
            class="h-5 w-5 rounded border"
          />
          <label for="izinPublikasi" class="text-sm">Mengizinkan dokumentasi & publikasi kegiatan</label>
        </div>
      </div>
    </section>

    <div class="md:col-span-2">
      <div class="flex justify-end">
        <button
          id="submitBtn"
          type="submit"
          class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 hover:bg-red-700 text-white px-6 py-3 shadow"
        >
          Ajukan Penjadwalan
        </button>
      </div>
    </div>
  </form>
</main>

<x-footer />

{{-- Disable submit jika field wajib belum lengkap (A & B minimal) --}}
<script>
  const form = document.getElementById('eventForm');
  const submitBtn = document.getElementById('submitBtn');

  function allRequiredFilled() {
    let ok = true;
    form.querySelectorAll(
      '[name="nama"],[name="institusi_pemohon"],[name="nomor_telefon"],[name="email"],[name="tanggal_event"],[name="jenis_event"]'
    ).forEach(el => { if (!el.value) ok = false; });
    return ok;
  }

  function toggleSubmit() { submitBtn.disabled = !allRequiredFilled(); }
  form.addEventListener('input', toggleSubmit);
  document.addEventListener('DOMContentLoaded', toggleSubmit);
</script>
