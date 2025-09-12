{{-- resources/views/pemesanan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Form Pemesanan – UDD PMI Provinsi Lampung')

@section('content')
<div class="min-h-screen bg-white text-slate-800">


  <x-navbar />

  {{-- WRAPPER --}}
  <section class="py-10 sm:py-14">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      {{-- CARD --}}
      <div class="relative rounded-3xl bg-white border border-slate-200 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.12)]">
        {{-- header card --}}
        <div class="flex items-center justify-between p-5 sm:p-6 border-b border-slate-100">
          <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-red-600 text-white grid place-items-center shadow ring-4 ring-white">🩸</div>
            <h1 class="text-lg sm:text-xl font-bold">Data Pasien & Rumah Sakit</h1>
          </div>
          {{-- progress ringan --}}
          <div class="hidden sm:flex items-center gap-3 text-xs text-slate-500">
            <span>Progress</span>
            <div class="w-28 h-1.5 rounded-full bg-slate-100 overflow-hidden">
              <div id="bar" class="h-full bg-red-500" style="width:0%"></div>
            </div>
            <span id="pct" class="text-red-500 font-semibold">0%</span>
          </div>
        </div>

        {{-- body card / FORM --}}
        <form method="POST" action="{{ route('pemesanan.store.step1') }}" class="p-5 sm:p-6">
          @csrf
          <div class="grid sm:grid-cols-2 gap-5">

            {{-- Rumah Sakit --}}
            <div>
              <label class="block text-sm font-medium text-slate-700">Rumah Sakit <span class="text-red-600">*</span></label>
              <select name="rumah_sakit" required
                class="mt-2 w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 bg-slate-50/50">
                <option value="" selected disabled>Pilih Rumah Sakit</option>
                @foreach (($rsOptions ?? ['RSUD A','RSUD B','RS PMI']) as $rs)
                  <option value="{{ $rs }}" @selected(old('rumah_sakit')===$rs)>{{ $rs }}</option>
                @endforeach
              </select>
              @error('rumah_sakit') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Jenis Kelamin --}}
            <div>
              <label class="block text-sm font-medium text-slate-700">Jenis Kelamin <span class="text-red-600">*</span></label>
              <select name="jenis_kelamin" required
                class="mt-2 w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 bg-slate-50/50">
                <option value="" selected disabled>Pilih Jenis Kelamin</option>
                <option value="L" @selected(old('jenis_kelamin')==='L')>Laki-laki</option>
                <option value="P" @selected(old('jenis_kelamin')==='P')>Perempuan</option>
              </select>
              @error('jenis_kelamin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- No Registrasi --}}
            <div>
              <label class="block text-sm font-medium text-slate-700">Nomor Registrasi <span class="text-red-600">*</span></label>
              <input type="text" name="no_registrasi" value="{{ old('no_registrasi') }}" required
                class="mt-2 w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 bg-slate-50/50"
                placeholder="Masukkan nomor registrasi" />
              @error('no_registrasi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Status Suami/Istri --}}
            <div>
              <label class="block text-sm font-medium text-slate-700">Nama Suami/Istri <span class="text-red-600">*</span></label>
              <select name="status_perkawinan" required
                class="mt-2 w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 bg-slate-50/50">
                <option value="" selected disabled>Pilih Status</option>
                <option value="Menikah" @selected(old('status_perkawinan')==='Menikah')>Menikah</option>
                <option value="Belum Menikah" @selected(old('status_perkawinan')==='Belum Menikah')>Belum Menikah</option>
                <option value="Cerai" @selected(old('status_perkawinan')==='Cerai')>Cerai</option>
              </select>
              @error('status_perkawinan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Dokter --}}
            <div class="sm:col-span-1">
              <label class="block text-sm font-medium text-slate-700">Nama Dokter <span class="text-red-600">*</span></label>
              <input type="text" name="nama_dokter" value="{{ old('nama_dokter') }}" required
                class="mt-2 w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 bg-slate-50/50"
                placeholder="Nama dokter penanggung jawab" />
              @error('nama_dokter') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Pasien --}}
            <div class="sm:col-span-1">
              <label class="block text-sm font-medium text-slate-700">Nama Pasien <span class="text-red-600">*</span></label>
              <input type="text" name="nama_pasien" value="{{ old('nama_pasien') }}" required
                class="mt-2 w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 bg-slate-50/50"
                placeholder="Nama lengkap pasien" />
              @error('nama_pasien') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

          </div>

          {{-- ACTIONS --}}
          <div class="mt-8 flex justify-center">
            <button type="submit"
              class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 hover:bg-red-700 text-white px-6 py-3 shadow">
              Berikutnya
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>

  {{-- (opsional) footer komponen --}}
  <x-footer bg="bg-slate-50" />
</div>

{{-- progress ringan berdasarkan field yang terisi --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const inputs = Array.from(document.querySelectorAll('input[required], select[required]'));
    const bar = document.getElementById('bar');
    const pct = document.getElementById('pct');
    const update = () => {
      const filled = inputs.filter(i => (i.type === 'select-one' ? i.value : i.value.trim())).length;
      const p = Math.round((filled / inputs.length) * 100);
      if (bar) bar.style.width = p + '%';
      if (pct) pct.textContent = p + '%';
    };
    inputs.forEach(i => i.addEventListener('input', update));
    inputs.forEach(i => i.addEventListener('change', update));
    update();
  });
</script>
@endsection
