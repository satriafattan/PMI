{{-- resources/views/about.blade.php --}}
@extends('layouts.app')
<x-navbar />


@section('title', 'Tentang Kami – UDD PMI Provinsi Lampung')
@section('content')
<div class="min-h-screen bg-white text-slate-800">

  {{-- HERO --}}
  <section class="relative overflow-hidden bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 text-center">
      <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-800">Tentang Kami</h1>
      <div class="mt-3 flex justify-center">
        <span class="h-1 w-16 rounded-full bg-red-600"></span>
      </div>
      <p class="mt-6 max-w-3xl mx-auto text-slate-600">
        Membangun masa depan kesehatan yang lebih baik melalui inovasi, dedikasi, dan pelayanan terdepan untuk seluruh masyarakat Indonesia.
      </p>
    </div>
  </section>

  {{-- PROFIL PERUSAHAAN --}}
  <section class="py-14 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-2 gap-10 items-start">
        <div>
          <h2 class="text-2xl sm:text-3xl font-bold">Profil Perusahaan</h2>
          <p class="mt-3 text-slate-600">
            Sebagai institusi kesehatan terdepan, kami berkomitmen untuk memberikan pelayanan medis berkualitas tinggi
            dengan mengintegrasikan teknologi modern dan pendekatan humanis dalam setiap aspek pelayanan.
          </p>
          <p class="mt-3 text-slate-600">
            Dengan pengalaman lebih dari dua dekade, kami terus berinovasi untuk memenuhi kebutuhan kesehatan masyarakat
            yang berkembang.
          </p>

          {{-- Stat cards --}}
          <div class="mt-6 grid grid-cols-3 gap-4">
            @foreach ([
              ['25+', 'Tahun Pengalaman'],
              ['1M+', 'Pasien Dilayani'],
              ['50+', 'Dokter Spesialis'],
            ] as $s)
              <div class="rounded-xl border border-slate-200 bg-white shadow-sm p-4">
                <div class="text-2xl font-extrabold text-red-600">{{ $s[0] }}</div>
                <div class="text-xs text-slate-500 mt-1">{{ $s[1] }}</div>
              </div>
            @endforeach
          </div>
        </div>

        {{-- Ilustrasi / Gambar --}}
        <div class="relative">
          <div class="absolute -inset-2 rounded-3xl bg-red-200/40 blur"></div>
          <div class="relative aspect-[4/3] rounded-3xl bg-white grid place-items-center text-slate-500 text-2xl shadow-xl ring-1 ring-slate-200">
            Medical Team
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- VISI & MISI --}}
  <section class="py-14 sm:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-2xl sm:text-3xl font-bold">Visi & Misi</h2>
      <p class="mt-2 text-slate-600 max-w-2xl mx-auto">Fondasi nilai dan tujuan yang mengarahkan setiap langkah perjalanan kami.</p>

      {{-- Tabs --}}
      <div class="mt-6 inline-flex rounded-2xl border border-slate-200 bg-white p-1">
        <button data-tab="visi" class="tab-btn px-4 py-2 rounded-xl text-sm font-medium bg-red-600 text-white shadow">Visi</button>
        <button data-tab="misi" class="tab-btn px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-50">Misi</button>
      </div>

      {{-- Konten Visi & Misi --}}
      <div class="mt-8">
        <div id="tab-visi" class="tab-panel">
          <div class="grid md:grid-cols-3 gap-6 text-left">
            @foreach ([
              ['Kepemimpinan Global','Menjadi institusi kesehatan terdepan di Asia Tenggara yang diakui secara internasional.'],
              ['Aksesibilitas Universal','Setiap individu memiliki akses layanan kesehatan berkualitas tanpa memandang latar belakang.'],
              ['Inovasi Berkelanjutan','Mengintegrasikan teknologi terdepan dengan pendekatan medis holistik.'],
            ] as $v)
              <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow transition">
                <div class="h-10 w-10 rounded-xl bg-red-100 text-red-600 grid place-items-center mb-3">★</div>
                <div class="font-semibold">{{ $v[0] }}</div>
                <p class="mt-2 text-sm text-slate-600">{{ $v[1] }}</p>
              </div>
            @endforeach
          </div>
        </div>

        <div id="tab-misi" class="tab-panel hidden">
          <div class="grid md:grid-cols-3 gap-6 text-left">
            @foreach ([
              ['Pelayanan Aman','Menjaga standar keamanan donor & transfusi sesuai protokol WHO.'],
              ['Pendidikan & Riset','Mendorong pendidikan, riset, dan kolaborasi lintas disiplin.'],
              ['Respons 24/7','Menyiapkan respon cepat keadaan darurat kapan pun.'],
            ] as $m)
              <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow transition">
                <div class="h-10 w-10 rounded-xl bg-red-100 text-red-600 grid place-items-center mb-3">✓</div>
                <div class="font-semibold">{{ $m[0] }}</div>
                <p class="mt-2 text-sm text-slate-600">{{ $m[1] }}</p>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- NILAI-NILAI --}}
  <section class="py-14 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-2xl sm:text-3xl font-bold">Nilai-Nilai Kami</h2>
      <p class="mt-2 text-slate-600 max-w-2xl mx-auto">Prinsip fundamental yang menjadi landasan tindakan dan keputusan kami.</p>

      <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-5 gap-6 text-left">
        @for ($i=0; $i<5; $i++)
          <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow transition">
            <div class="h-12 w-12 rounded-full bg-red-600 text-white grid place-items-center shadow ring-4 ring-white mb-3">☆</div>
            <div class="font-semibold">Integritas</div>
            <p class="mt-2 text-sm text-slate-600">
              Berkomitmen pada kejujuran, transparansi, dan etika profesional dalam setiap pelayanan.
            </p>
          </div>
        @endfor
      </div>
    </div>
  </section>

  {{-- CTA GRADIENT --}}
  <section class="py-16 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-red-600 to-rose-500 text-white p-8 sm:p-12 text-center">
        <div class="absolute -right-16 -top-16 h-48 w-48 bg-white/10 rounded-full blur-2xl"></div>
        <h3 class="text-2xl sm:text-3xl font-bold">Bergabunglah dengan Kami</h3>
        <p class="mt-2 text-white/90 max-w-2xl mx-auto">
          Mari bersama-sama membangun masa depan kesehatan yang lebih baik untuk Indonesia.
        </p>
        <div class="mt-6 flex flex-wrap gap-3 justify-center">
          <a href="{{ url('/karir') }}" class="px-5 py-3 rounded-xl bg-white text-red-700 font-semibold hover:bg-slate-100">Karir Bersama Kami</a>
          <a href="{{ url('/kontak') }}" class="px-5 py-3 rounded-xl border border-white/40 text-white hover:bg-white/10">Hubungi Kami</a>
        </div>
      </div>
    </div>
  </section>
  <x-footer bg="bg-slate-50" />

</div>

{{-- Tabs JS kecil --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const btns = document.querySelectorAll('.tab-btn');
    const panels = {
      visi: document.getElementById('tab-visi'),
      misi: document.getElementById('tab-misi'),
    };
    btns.forEach(b => b.addEventListener('click', () => {
      btns.forEach(x => x.classList.remove('bg-red-600','text-white','shadow'));
      b.classList.add('bg-red-600','text-white','shadow');
      panels.visi.classList.toggle('hidden', b.dataset.tab !== 'visi');
      panels.misi.classList.toggle('hidden', b.dataset.tab !== 'misi');
    }));
  });
</script>
@endsection
