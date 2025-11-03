{{-- resources/views/Home.blade.php --}}
@extends('layouts.app')
<x-navbar />

@section('title', 'UDD PMI Provinsi Lampung – Pemesanan Darah')

@section('content')

  </div>
  <div id="mobileMenu"
       class="hidden pb-4 md:hidden">
    <div class="grid gap-2 text-sm">
      <a href="#beranda"
         class="rounded-lg px-3 py-2 hover:bg-slate-50">Beranda</a>
      <a href="{{ url('/pemesanan') }}"
         class="rounded-lg px-3 py-2 hover:bg-slate-50">Pemesanan</a>
      <a href="#stok"
         class="rounded-lg px-3 py-2 hover:bg-slate-50">Stok darah</a>
      <a href="#about"
         class="rounded-lg px-3 py-2 hover:bg-slate-50">Tentang Kami</a>
    </div>
  </div>
  </div>
  </header>

  {{-- HERO --}}
  <section id="beranda"
           class="relative overflow-hidden">
    <div class="pointer-events-none absolute inset-0">
      <div class="absolute -left-24 -top-24 h-72 w-72 rounded-full bg-red-200/60 blur-3xl"></div>
      <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-rose-300/50 blur-3xl"></div>
    </div>

    <div class="bg-gradient-to-r from-red-700 via-red-600 to-rose-500">
      <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2">
          <div class="text-white">
            <h1 class="text-4xl font-extrabold leading-tight sm:text-5xl">
              Selamatkan Nyawa <span class="block">dengan kami</span>
            </h1>
            <p class="mt-4 max-w-xl text-white/90">
              Bergabunglah dengan misi untuk menyediakan darah berkualitas bagi yang membutuhkan. Setiap tetes dapat
              menyelamatkan hingga 3 nyawa.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
              <a href="{{ url('/pemesanan') }}"
                 class="rounded-xl bg-white px-5 py-3 font-semibold text-red-700 shadow hover:bg-slate-100">Info
                Permintaan Darah</a>
              <a href="#about"
                 class="rounded-xl border border-white/40 px-5 py-3 text-white hover:bg-white/10">Pelajari Lebih
                Lanjut</a>
            </div>
            <div class="mt-8 flex flex-wrap gap-8 text-sm text-white/90">
              <div class="flex items-center gap-3">
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-white/10 ring-1 ring-white/20">🩸</div>
                <div>
                  <div class="text-lg font-bold">15,000+</div>
                  <div>Donor Aktif</div>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-white/10 ring-1 ring-white/20">❤️</div>
                <div>
                  <div class="text-lg font-bold">50,000+</div>
                  <div>Nyawa Terselamatkan</div>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-white/10 ring-1 ring-white/20">⏱️</div>
                <div>
                  <div class="text-lg font-bold">24/7</div>
                  <div>Layanan Darurat</div>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="relative mx-auto max-w-lg">
              <div
                   class="aspect-[4/3] overflow-hidden rounded-3xl bg-white/20 shadow-2xl ring-1 ring-white/30 backdrop-blur">
                {{-- Placeholder hero image card --}}
                <div class="grid h-full w-full place-items-center text-xl font-semibold text-white/90">Medical
                  Professional</div>
              </div>
              <div
                   class="absolute -bottom-6 -left-6 rounded-2xl bg-white/90 p-4 text-slate-800 shadow-xl ring-1 ring-slate-200">
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
    use App\Helpers\StokHelper;

    $stok = [
        ['gol' => 'A', 'jumlah' => $stokA],
        ['gol' => 'B', 'jumlah' => $stokB],
        ['gol' => 'O', 'jumlah' => $stokO],
        ['gol' => 'AB', 'jumlah' => $stokAB],
    ];
  @endphp

  <section id="stok"
           class="py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <h2 class="text-3xl font-bold sm:text-4xl">Persediaan Darah</h2>
          <p class="mt-1 text-slate-600">Pantau ketersediaan real-time per golongan.</p>
        </div>
        <div class="flex items-center gap-2">
          <a href="{{ url('/stok') }}"
             class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm hover:bg-slate-50">
            Lihat Detail
          </a>
        </div>
      </div>

      <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($stok as $it)
          @php
            [$label, $cls] = StokHelper::badgeStatus($it['jumlah']);
            $color = str_replace(['bg-', 'text-'], '', explode(' ', $cls)[0]);
          @endphp

          <div class="group relative"
               data-golongan="{{ $it['gol'] }}">
            {{-- glow background --}}
            <div
                 class="absolute -inset-1 rounded-3xl bg-gradient-to-b from-red-500/60 to-rose-400/60 opacity-20 blur transition group-hover:opacity-40">
            </div>

            <div class="relative rounded-3xl border border-slate-200 bg-white p-6 shadow-lg transition hover:shadow-xl">
              <div class="flex items-start justify-between">
                <div class="inline-flex items-center gap-2">
                  <div
                       class="grid h-10 w-10 place-items-center rounded-2xl bg-red-600 text-white shadow ring-4 ring-white">
                    🩸</div>
                  <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">PRC</span>
                </div>
                @php
                  $statusColor = $label === 'Aman' ? 'emerald' : ($label === 'Waspada' ? 'amber' : 'rose');
                @endphp
                <span data-status
                      class="bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 rounded-full px-2.5 py-1 text-xs font-semibold">
                  {{ $label }}
                </span>
              </div>

              {{-- angka & golongan --}}
              <div class="mt-5 flex items-end justify-between">
                <div>
                  <div class="text-4xl font-extrabold tracking-tight"
                       data-counter>
                    {{ number_format($it['jumlah'], 0, ',', '.') }}
                  </div>
                  <div class="text-sm text-slate-500">Unit tersedia</div>
                </div>
                <div class="flex flex-col items-center">
                  <div class="w-12 text-center text-3xl font-black text-red-600">{{ $it['gol'] }}</div>
                  <div class="mt-1 text-xs text-slate-400">Golongan</div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      {{-- footer info --}}
      <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="text-xs text-slate-500">Terakhir diperbarui: <span id="lastUpdated"
                class="font-medium">{{ $lastUpdated }}</span></div>
        <div class="flex items-center gap-2 text-xs">
          <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-1 text-emerald-700">●
            Aman</span>
          <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2 py-1 text-amber-700">● Waspada</span>
          <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2 py-1 text-red-700">● Kritis</span>
        </div>
      </div>
    </div>
  </section>

  {{-- MENGAPA MEMILIH --}}
  <section id="about"
           class="bg-slate-50 py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid items-center gap-10 lg:grid-cols-2">
        <div>
          <h2 class="text-3xl font-bold sm:text-4xl">Mengapa Memilih <span class="text-red-600">BloodBank</span></h2>
          <p class="mt-3 text-slate-600">Kami berkomitmen memberikan pelayanan terbaik dalam pengelolaan darah dengan
            standar internasional dan teknologi terdepan.</p>
          <ul class="mt-6 space-y-4">
            <li class="flex items-start gap-4">
              <span class="mt-1 grid h-6 w-6 place-items-center rounded-full bg-red-100 text-red-600">✓</span>
              <div>
                <div class="font-semibold">Standar Keamanan Tinggi</div>
                <p class="text-sm text-slate-600">Seluruh screening & pengolahan darah mengikuti protokol WHO dan standar
                  internasional.</p>
              </div>
            </li>
            <li class="flex items-start gap-4">
              <span class="mt-1 grid h-6 w-6 place-items-center rounded-full bg-red-100 text-red-600">✓</span>
              <div>
                <div class="font-semibold">Teknologi Terdepan</div>
                <p class="text-sm text-slate-600">Sistem manajemen digital dan peralatan medis terkini untuk keandalan dan
                  efisiensi.</p>
              </div>
            </li>
            <li class="flex items-start gap-4">
              <span class="mt-1 grid h-6 w-6 place-items-center rounded-full bg-red-100 text-red-600">✓</span>
              <div>
                <div class="font-semibold">Layanan 24/7</div>
                <p class="text-sm text-slate-600">Tim medis profesional siap kebutuhan darurat kapan saja.</p>
              </div>
            </li>
          </ul>
        </div>
        <div>
          <div class="relative mx-auto max-w-xl">
            <div class="absolute -inset-6 rounded-3xl bg-gradient-to-r from-slate-200 to-white blur"></div>
            <div
                 class="relative grid aspect-[4/3] place-items-center rounded-3xl bg-white text-xl text-slate-400 shadow-xl ring-1 ring-slate-200">
              Blood Bank Facility</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Enhancement: kecil, interaksi & counter --}}
  <script>
    async function updateStokDarah() {
      try {
        const response = await fetch('/api/stok-golongan');
        const data = await response.json();

        // Update each blood type stock
        Object.entries(data.stok).forEach(([gol, jumlah]) => {
          const card = document.querySelector(`[data-golongan="${gol}"]`);
          if (card) {
            const counter = card.querySelector('[data-counter]');
            const status = card.querySelector('[data-status]');
            if (counter) counter.textContent = new Intl.NumberFormat('id-ID').format(jumlah);

            // Update status badge
            if (status) {
              const newStatus = jumlah < 300 ? ['Kritis', 'red'] :
                jumlah < 1000 ? ['Waspada', 'amber'] : ['Aman', 'emerald'];
              status.textContent = newStatus[0];
              status.className =
                `px-2.5 py-1 text-xs rounded-full font-semibold bg-${newStatus[1]}-100 text-${newStatus[1]}-700`;
            }
          }
        });

        // Update last updated time
        const lastUpdated = document.getElementById('lastUpdated');
        if (lastUpdated) lastUpdated.textContent = data.lastUpdated;

      } catch (error) {
        console.error('Error updating stock:', error);
      }
    }

    // Update every 30 seconds
    setInterval(updateStokDarah, 30000);

    document.addEventListener('DOMContentLoaded', () => {
      // Initial update
      updateStokDarah();
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
      }, {
        threshold: 0.6
      });
      els.forEach(el => io.observe(el));
    });
  </script>

  <x-footer bg="bg-slate-50" />
@endsection
