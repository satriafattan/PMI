@extends('layouts.app')
@section('title','Stok Darah – UDD PMI Provinsi Lampung')

@section('content')
<div class="min-h-screen bg-white text-slate-800">
  <x-navbar />
  @php
    // === Dummy data; ganti dari controller kamu ===
    $stok = [
      'A'  => $stokA  ?? 76,
      'B'  => $stokB  ?? 41,
      'O'  => $stokO  ?? 18,
      'AB' => $stokAB ?? 7,
    ];
    $stokTable = $stokTable ?? [
      ['unit' => 'UDD Pusat',   'A'=>76, 'AB'=>21, 'B'=>41, 'O'=>18],
      ['unit' => 'UDD Barat',   'A'=>50, 'AB'=>15, 'B'=>28, 'O'=>22],
      ['unit' => 'UDD Timur',   'A'=>32, 'AB'=>12, 'B'=>20, 'O'=>10],
      ['unit' => 'UDD Selatan', 'A'=>44, 'AB'=> 9, 'B'=>16, 'O'=> 8],
      ['unit' => 'UDD Utara',   'A'=>39, 'AB'=>11, 'B'=>18, 'O'=>13],
    ];
    $sumA  = collect($stokTable)->sum('A');
    $sumAB = collect($stokTable)->sum('AB');
    $sumB  = collect($stokTable)->sum('B');
    $sumO  = collect($stokTable)->sum('O');
    $sumAll = $sumA + $sumAB + $sumB + $sumO;
  @endphp

  {{-- ACTION BAR --}}
  <section class="py-6 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="w-full md:w-auto flex items-center gap-2">
          <input id="q" type="text" placeholder="Cari golongan… (A, B, O, AB)"
                 class="w-full md:w-72 rounded-xl border-slate-200 bg-slate-50/70 px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
          <button id="clearQ" class="px-3 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-sm">Bersihkan</button>
        </div>
        <div class="flex items-center gap-2">
          <div class="rounded-xl border border-slate-200 p-1 bg-slate-50/60">
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- VIEW: CARDS --}}
  <section id="viewCards" class="py-2">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div id="grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($stok as $gol => $jumlah)
          @php
            $status = 'Aman'; $color = 'emerald'; $border = 'border-emerald-400/40';
            if ($jumlah < 50) { $status = 'Menipis'; $color = 'amber'; $border = 'border-amber-400/40'; }
            if ($jumlah < 20) { $status = 'Kritis'; $color = 'red'; $border = 'border-red-400/50'; }
          @endphp

          <div class="relative group card" data-gol="{{ $gol }}">
            <div class="absolute -inset-1 rounded-3xl bg-gradient-to-b from-red-500 to-rose-400 blur opacity-25 group-hover:opacity-50 transition"></div>
            <div class="relative p-6 rounded-3xl bg-white border-2 {{ $border }} shadow-lg">
              <div class="flex items-center justify-between">
                <div class="h-9 w-9 rounded-lg bg-red-600 text-white grid place-items-center ring-4 ring-white font-bold">{{ $gol }}</div>
                <div class="inline-flex items-center gap-2 text-xs">
                  <span class="h-2.5 w-2.5 rounded-full bg-{{ $color }}-500"></span>
                  <span class="font-medium text-slate-600">{{ $status }}</span>
                </div>
              </div>

              <div class="mt-6 text-center">
                <div class="text-4xl font-extrabold tracking-tight tabular-nums" data-counter>
                  {{ number_format($jumlah, 0, ',', '.') }}
                </div>
                <div class="text-slate-500 text-sm">Unit tersedia</div>
              </div>

              <div class="mt-6 grid grid-cols-2 gap-2">
                <a href="{{ url('/pemesanan') }}"
                   class="px-3 py-2 rounded-xl bg-red-600 text-white text-sm hover:bg-red-700 text-center shadow">
                  Pesan
                </a>
                <button type="button"
                   class="px-3 py-2 rounded-xl border border-slate-200 text-slate-700 text-sm hover:bg-slate-50 text-center">
                  Detail
                </button>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-slate-600 text-xs">
        * Ambang: ≥50 Aman, 20–49 Menipis, &lt;20 Kritis.
      </div>
    </div>
  </section>

 {{-- ===== TABEL STOK KOMPONEN (gaya PMI DKI, warna konsisten) ===== --}}
@php
// ==== Contoh data; ganti dari Controller (lihat catatan di bawah) ====
// Struktur: [ 'komponen' => 'PRC', 'A'=>..., 'B'=>..., 'O'=>..., 'AB'=>... ]
$komponenRows = $komponenRows ?? [
  ['komponen'=>'WB',               'A'=>7,   'B'=>4,   'O'=>12,  'AB'=>0 ],
  ['komponen'=>'PRC',              'A'=>3917,'B'=>4259,'O'=>5715,'AB'=>1092],
  ['komponen'=>'TC',               'A'=>385, 'B'=>364, 'O'=>457, 'AB'=>101],
  ['komponen'=>'FFP',              'A'=>2800,'B'=>2910,'O'=>4031,'AB'=>716],
  ['komponen'=>'FP',               'A'=>72,  'B'=>0,   'O'=>0,   'AB'=>0 ],
  ['komponen'=>'AHF',              'A'=>952, 'B'=>713, 'O'=>746, 'AB'=>342],
  ['komponen'=>'LP',               'A'=>94,  'B'=>122, 'O'=>163, 'AB'=>31 ],
  ['komponen'=>'WE',               'A'=>5,   'B'=>5,   'O'=>13,  'AB'=>3  ],
  ['komponen'=>'TC Apheresis',     'A'=>15,  'B'=>21,  'O'=>23,  'AB'=>7  ],
  ['komponen'=>'PRC Leucodepleted','A'=>1,   'B'=>5,   'O'=>5,   'AB'=>2  ],
  ['komponen'=>'PRC Leucoreduce',  'A'=>3769,'B'=>4247,'O'=>5951,'AB'=>1277],
];
$sumA  = collect($komponenRows)->sum('A');
$sumB  = collect($komponenRows)->sum('B');
$sumO  = collect($komponenRows)->sum('O');
$sumAB = collect($komponenRows)->sum('AB');
$sumAll = $sumA + $sumB + $sumO + $sumAB;
@endphp

<section class="py-10">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <div class="rounded-2xl border border-slate-300 bg-white shadow-xl overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-lg border-collapse">
        {{-- HEADER --}}
        <thead class="bg-red-100/70 text-slate-800">
          <tr class="border-b border-slate-300">
            <th class="px-8 py-5 text-left font-extrabold border-r border-slate-300">Stok Saat Ini</th>
            <th class="px-8 py-5 text-center font-bold border-r border-slate-300">A</th>
            <th class="px-8 py-5 text-center font-bold border-r border-slate-300">AB</th>
            <th class="px-8 py-5 text-center font-bold border-r border-slate-300">B</th>
            <th class="px-8 py-5 text-center font-bold border-r border-slate-300">O</th>
            <th class="px-8 py-5 text-center font-bold">Total</th>
          </tr>
        </thead>

        {{-- BODY --}}
        <tbody class="text-slate-800">
          @foreach ($komponenRows as $row)
            @php $total = $row['A'] + $row['AB'] + $row['B'] + $row['O']; @endphp
            <tr class="odd:bg-white even:bg-slate-50 hover:bg-slate-100 transition-colors border-b border-slate-200">
              <td class="px-8 py-5 font-semibold border-r border-slate-200">{{ $row['komponen'] }}</td>
              <td class="px-8 py-5 text-center tabular-nums border-r border-slate-200">{{ number_format($row['A']) }}</td>
              <td class="px-8 py-5 text-center tabular-nums border-r border-slate-200">{{ number_format($row['AB']) }}</td>
              <td class="px-8 py-5 text-center tabular-nums border-r border-slate-200">{{ number_format($row['B']) }}</td>
              <td class="px-8 py-5 text-center tabular-nums border-r border-slate-200">{{ number_format($row['O']) }}</td>
              <td class="px-8 py-5 text-center font-bold tabular-nums">{{ number_format($total) }}</td>
            </tr>
          @endforeach
        </tbody>

        {{-- FOOTER JUMLAH --}}
        <tfoot class="bg-slate-100 text-slate-900">
          <tr class="border-t-2 border-slate-300">
            <td class="px-8 py-5 font-extrabold border-r border-slate-300">Jumlah</td>
            <td class="px-8 py-5 text-center font-bold tabular-nums border-r border-slate-300">{{ number_format($sumA) }}</td>
            <td class="px-8 py-5 text-center font-bold tabular-nums border-r border-slate-300">{{ number_format($sumAB) }}</td>
            <td class="px-8 py-5 text-center font-bold tabular-nums border-r border-slate-300">{{ number_format($sumB) }}</td>
            <td class="px-8 py-5 text-center font-bold tabular-nums border-r border-slate-300">{{ number_format($sumO) }}</td>
            <td class="px-8 py-5 text-center font-extrabold tabular-nums">{{ number_format($sumAll) }}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <p class="mt-3 text-sm text-slate-500">
  </p>
</div>
</section>



  <x-footer bg="bg-white" />
</div>

{{-- Interaksi ringan --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
      /* -------------------------------
       * Toggles: Cards <-> Table
       * ----------------------------- */
      const tabCards = document.getElementById('tabCards');
      const tabTable = document.getElementById('tabTable');
      const vCards   = document.getElementById('viewCards');
      const vTable   = document.getElementById('viewTable');
    
      function activateTab(btnOn, btnOff) {
        // gaya aktif/non-aktif sesuai tema
        btnOn?.classList.add('bg-white','shadow');
        btnOn?.classList.remove('text-slate-600');
        btnOn?.setAttribute('aria-selected','true');
    
        btnOff?.classList.remove('bg-white','shadow');
        btnOff?.classList.add('text-slate-600');
        btnOff?.setAttribute('aria-selected','false');
      }
    
      function setTab(mode){
        const showCards = mode === 'cards';
        vCards?.classList.toggle('hidden', !showCards);
        vTable?.classList.toggle('hidden', showCards);
        if (showCards) activateTab(tabCards, tabTable);
        else           activateTab(tabTable, tabCards);
      }
    
      tabCards?.addEventListener('click', () => setTab('cards'));
      tabTable?.addEventListener('click', () => setTab('table'));
    
      // default tampilan (boleh ubah ke 'table' kalau mau mulai dari tabel)
      setTab('cards');
    
    
      /* -------------------------------
       * Filter sederhana (Cards + Table)
       * - input id: #q (fallback: #search)
       * - clear btn id: #clearQ (fallback: [data-clear])
       * ----------------------------- */
      const input = document.getElementById('q') || document.getElementById('search');
      const clear = document.getElementById('clearQ') || document.querySelector('[data-clear]');
      // Cards
      const cardEls = Array.from(document.querySelectorAll('#grid .card'));
      // Table rows (tbody tr)
      const tableRows = Array.from(document.querySelectorAll('#viewTable table tbody tr'));
    
      function normalize(s){ return (s || '').toString().trim().toUpperCase(); }
    
      function applyFilter() {
        const q = normalize(input?.value);
        const isGol = ['A','B','AB','O'].includes(q);
    
        // Filter Cards: cocokkan ke data-gol atau data-name
        cardEls.forEach(el => {
          const gol  = normalize(el.getAttribute('data-gol'));
          const name = normalize(el.getAttribute('data-name')); // optional
          const hit  = !q || gol.includes(q) || name.includes(q);
          el.style.display = hit ? '' : 'none';
        });
    
        // Filter Table: cocokkan ke teks komponen (kolom pertama)
        tableRows.forEach(tr => {
          // kalau q adalah golongan (A/B/AB/O), tabel tampil semua (referensi menampilkan semua golongan)
          if (isGol || !q) { tr.style.display = ''; return; }
          const firstCellText = normalize(tr.cells?.[0]?.innerText);
          tr.style.display = firstCellText.includes(q) ? '' : 'none';
        });
      }
    
      input?.addEventListener('input', applyFilter);
      clear?.addEventListener('click', () => { if (input) { input.value=''; applyFilter(); } });
    
    
      /* -------------------------------
       * Counter animation untuk angka
       * elemen: [data-counter]
       * ----------------------------- */
      const counters = document.querySelectorAll('[data-counter]');
      const easeOut = t => 1 - Math.pow(1 - t, 4);
    
      const io = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) return;
          const el = entry.target;
    
          // Ambil nilai target dari teks (buang semua selain angka)
          const numeric = (el.textContent || '').replace(/[^\d]/g,'');
          const target  = parseInt(numeric, 10) || 0;
    
          let startTs = null;
          function step(ts){
            if (!startTs) startTs = ts;
            const p = Math.min(1, (ts - startTs) / 1100);
            const val = Math.floor(easeOut(p) * target);
            el.textContent = val.toLocaleString('id-ID');
            if (p < 1) requestAnimationFrame(step);
          }
          requestAnimationFrame(step);
          io.unobserve(el);
        });
      }, { threshold: 0.6 });
    
      counters.forEach(el => io.observe(el));
    });
    </script>
    
@endsection
