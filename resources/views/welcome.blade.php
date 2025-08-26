{{-- resources/views/Home.blade.php --}}
@extends('layouts.app')
<x-navbar />

@section('title', 'UDD PMI Provinsi Lampung – Pemesanan Darah')

@section('content')

      </div>
      <div id="mobileMenu" class="md:hidden hidden pb-4">
        <div class="grid gap-2 text-sm">
          <a href="#beranda" class="px-3 py-2 rounded-lg hover:bg-slate-50">Beranda</a>
          <a href="{{ url('/pemesanan') }}" class="px-3 py-2 rounded-lg hover:bg-slate-50">Pemesanan</a>
          <a href="#stok" class="px-3 py-2 rounded-lg hover:bg-slate-50">Stok darah</a>
          <a href="#about" class="px-3 py-2 rounded-lg hover:bg-slate-50">Tentang Kami</a>
        </div>
      </div>
    </div>
  </header>

  {{-- HERO --}}
  <section id="beranda" class="relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
      <div class="absolute -top-24 -left-24 h-72 w-72 bg-red-200/60 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-24 -right-24 h-72 w-72 bg-rose-300/50 rounded-full blur-3xl"></div>
    </div>

    <div class="bg-gradient-to-r from-red-700 via-red-600 to-rose-500">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-20">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
          <div class="text-white">
            <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">
              Selamatkan Nyawa <span class="block">dengan</span>
            </h1>
            <p class="mt-4 text-white/90 max-w-xl">
              Bergabunglah dengan misi untuk menyediakan darah berkualitas bagi yang membutuhkan. Setiap tetes dapat menyelamatkan hingga 3 nyawa.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
              <a href="{{ url('/pemesanan') }}" class="px-5 py-3 rounded-xl bg-white text-red-700 font-semibold hover:bg-slate-100 shadow">Info Permintaan Darah</a>
              <a href="#about" class="px-5 py-3 rounded-xl border border-white/40 text-white hover:bg-white/10">Pelajari Lebih Lanjut</a>
            </div>
            <div class="mt-8 flex flex-wrap gap-8 text-white/90 text-sm">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-white/10 grid place-items-center ring-1 ring-white/20">🩸</div>
                <div><div class="text-lg font-bold">15,000+</div><div>Donor Aktif</div></div>
              </div>
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-white/10 grid place-items-center ring-1 ring-white/20">❤️</div>
                <div><div class="text-lg font-bold">50,000+</div><div>Nyawa Terselamatkan</div></div>
              </div>
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-white/10 grid place-items-center ring-1 ring-white/20">⏱️</div>
                <div><div class="text-lg font-bold">24/7</div><div>Layanan Darurat</div></div>
              </div>
            </div>
          </div>
          <div>
            <div class="relative mx-auto max-w-lg">
              <div class="aspect-[4/3] rounded-3xl bg-white/20 backdrop-blur shadow-2xl ring-1 ring-white/30 overflow-hidden">
                {{-- Placeholder hero image card --}}
                <div class="w-full h-full grid place-items-center text-white/90 text-xl font-semibold">Medical Professional</div>
              </div>
              <div class="absolute -bottom-6 -left-6 p-4 rounded-2xl bg-white/90 text-slate-800 shadow-xl ring-1 ring-slate-200">
                <div class="text-xs text-slate-500">Estimasi proses</div>
                <div class="font-semibold">&lt; 10 menit untuk pengajuan</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- STOK PRC --}}
  @php
    $stok = [
      'A' => $stokA ?? 3037,
      'B' => $stokB ?? 3037,
      'O' => $stokO ?? 3037,
      'AB' => $stokAB ?? 3037,
    ];
  @endphp

      {{-- STOK PRC (Redesain tanpa grafik & progress bar) --}}
  @php
  $lastUpdated = $lastUpdated ?? now()->format('d M Y H:i');

  $stok = [
    ['gol' => 'A',  'jumlah' => $stokA  ?? 3037],
    ['gol' => 'B',  'jumlah' => $stokB  ?? 3037],
    ['gol' => 'O',  'jumlah' => $stokO  ?? 3037],
    ['gol' => 'AB', 'jumlah' => $stokAB ?? 3037],
  ];

  // fungsi status stok
  $status = function(int $n) {
    if ($n < 300)  return ['Kritis',  'red'];
    if ($n < 1000) return ['Waspada', 'amber'];
    return ['Aman', 'emerald'];
  };
@endphp

<section id="stok" class="py-16 sm:py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <h2 class="text-3xl sm:text-4xl font-bold">Persediaan Darah</h2>
        <p class="mt-1 text-slate-600">Pantau ketersediaan real-time per golongan.</p>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ url('/stok') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm">
          Lihat Detail
        </a>
      </div>
    </div>

    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach ($stok as $it)
        @php
          [$label, $color] = $status($it['jumlah']);
        @endphp

        <div class="relative group">
          {{-- glow background --}}
          <div class="absolute -inset-1 rounded-3xl bg-gradient-to-b from-red-500/60 to-rose-400/60 blur opacity-20 group-hover:opacity-40 transition"></div>

          <div class="relative p-6 rounded-3xl bg-white border border-slate-200 shadow-lg hover:shadow-xl transition">
            <div class="flex items-start justify-between">
              <div class="inline-flex items-center gap-2">
                <div class="h-10 w-10 rounded-2xl bg-red-600 text-white grid place-items-center shadow ring-4 ring-white">🩸</div>
                <span class="px-2.5 py-1 text-xs rounded-full font-medium bg-slate-100 text-slate-700">PRC</span>
              </div>
              <span class="px-2.5 py-1 text-xs rounded-full font-semibold bg-{{ $color }}-100 text-{{ $color }}-700">
                {{ $label }}
              </span>
            </div>

            {{-- angka & golongan --}}
            <div class="mt-5 flex items-end justify-between gap-3">
              <div>
                <div class="text-4xl font-extrabold tracking-tight" data-counter>
                  {{ number_format($it['jumlah'], 0, ',', '.') }}
                </div>
                <div class="text-slate-500 text-sm">Unit tersedia</div>
              </div>
              <div class="text-3xl font-black text-red-600">{{ $it['gol'] }}</div>
            </div>

            {{-- actions --}}
            <div class="mt-6 flex items-center justify-between">
              <a href="{{ url('/pemesanan') }}" class="text-sm font-medium text-red-600 hover:text-red-700 inline-flex items-center gap-1">
                Pesan darah
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                  <path fill-rule="evenodd" d="M3.75 12a.75.75 0 0 1 .75-.75h13.59l-4.22-4.22a.75.75 0 1 1 1.06-1.06l5.5 5.5a.75.75 0 0 1 0 1.06l-5.5 5.5a.75.75 0 1 1-1.06-1.06l4.22-4.22H4.5A.75.75 0 0 1 3.75 12Z" clip-rule="evenodd"/>
                </svg>
              </a>
              <span class="text-xs text-slate-400">Golongan {{ $it['gol'] }}</span>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- footer info --}}
    <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div class="text-xs text-slate-500">Terakhir diperbarui: <span class="font-medium">{{ $lastUpdated }}</span></div>
      <div class="flex items-center gap-2 text-xs">
        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">● Aman</span>
        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-amber-100 text-amber-700">● Waspada</span>
        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-red-100 text-red-700">● Kritis</span>
      </div>
    </div>
  </div>
</section>


  {{-- MENGAPA MEMILIH --}}
  <section id="about" class="py-16 sm:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-2 gap-10 items-center">
        <div>
          <h2 class="text-3xl sm:text-4xl font-bold">Mengapa Memilih <span class="text-red-600">BloodBank</span></h2>
          <p class="mt-3 text-slate-600">Kami berkomitmen memberikan pelayanan terbaik dalam pengelolaan darah dengan standar internasional dan teknologi terdepan.</p>
          <ul class="mt-6 space-y-4">
            <li class="flex items-start gap-4">
              <span class="mt-1 h-6 w-6 rounded-full bg-red-100 text-red-600 grid place-items-center">✓</span>
              <div>
                <div class="font-semibold">Standar Keamanan Tinggi</div>
                <p class="text-slate-600 text-sm">Seluruh screening & pengolahan darah mengikuti protokol WHO dan standar internasional.</p>
              </div>
            </li>
            <li class="flex items-start gap-4">
              <span class="mt-1 h-6 w-6 rounded-full bg-red-100 text-red-600 grid place-items-center">✓</span>
              <div>
                <div class="font-semibold">Teknologi Terdepan</div>
                <p class="text-slate-600 text-sm">Sistem manajemen digital dan peralatan medis terkini untuk keandalan dan efisiensi.</p>
              </div>
            </li>
            <li class="flex items-start gap-4">
              <span class="mt-1 h-6 w-6 rounded-full bg-red-100 text-red-600 grid place-items-center">✓</span>
              <div>
                <div class="font-semibold">Layanan 24/7</div>
                <p class="text-slate-600 text-sm">Tim medis profesional siap kebutuhan darurat kapan saja.</p>
              </div>
            </li>
          </ul>
        </div>
        <div>
          <div class="relative mx-auto max-w-xl">
            <div class="absolute -inset-6 bg-gradient-to-r from-slate-200 to-white rounded-3xl blur"></div>
            <div class="relative aspect-[4/3] rounded-3xl bg-white shadow-xl ring-1 ring-slate-200 grid place-items-center text-slate-400 text-xl">Blood Bank Facility</div>
          </div>
        </div>
      </div>
    </div>
  </section>

 

{{-- Enhancement: kecil, interaksi & counter --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Toggle mobile menu
    const btn = document.getElementById('menuBtn');
    const menu = document.getElementById('mobileMenu');
    if (btn && menu) btn.addEventListener('click', () => menu.classList.toggle('hidden'));

    // Simple counter animation for numbers with data-counter
    const els = document.querySelectorAll('[data-counter]');
    const ease = t => 1 - Math.pow(1 - t, 4);
    const io = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        const el = e.target;
        const numeric = el.textContent.replace(/[^0-9]/g, '');
        const target = parseInt(numeric, 10) || 0;
        let start = null;
        const step = ts => {
          if (!start) start = ts;
          const p = Math.min(1, (ts - start) / 1200);
          const val = Math.floor(ease(p) * target);
          el.textContent = val.toLocaleString('id-ID');
          if (p < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
        io.unobserve(el);
      });
    }, { threshold: 0.6 });
    els.forEach(el => io.observe(el));
  });
</script>

<x-footer bg="bg-slate-50" />
@endsection
