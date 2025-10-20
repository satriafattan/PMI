@extends('layouts.app')
@section('title', 'Stok Darah – UDD PMI Provinsi Lampung')

@section('content')
  <div class="min-h-screen bg-white text-slate-800">
    <x-navbar />

    @php
      // Data kartu dari controller
      $stok = [
        'A'  => $stokA  ?? 0,
        'B'  => $stokB  ?? 0,
        'O'  => $stokO  ?? 0,
        'AB' => $stokAB ?? 0,
      ];

      // Data tabel (agregat per produk) dari controller
      $komponenRows = $komponenRows ?? [];
      $sumA  = collect($komponenRows)->sum('A');
      $sumAB = collect($komponenRows)->sum('AB');
      $sumB  = collect($komponenRows)->sum('B');
      $sumO  = collect($komponenRows)->sum('O');
      $sumAll = $sumA + $sumAB + $sumB + $sumO;
    @endphp

    {{-- ACTION BAR --}}
    <section class="bg-white py-6">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
          <h1></h1>
          <div class="flex w-full items-center gap-2 md:w-auto">
            <input id="q"
                   type="text"
                   placeholder="Cari komponen… (PRC, WB, TC, ...) atau golongan (A, B, AB, O)"
                   class="w-full rounded-xl border-2 border-slate-300 bg-white px-4 py-2 text-sm shadow-sm transition-all duration-200 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 md:w-96">
            <button id="clearQ"
                    class="rounded-xl border border-slate-200 px-3 py-2 text-sm hover:bg-slate-50">Bersihkan</button>
          </div>
        </div>
      </div>
    </section>

    {{-- CARDS: total per golongan (SELALU TAMPIL) --}}
    <section class="py-2">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div id="cardGrid" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          @foreach ($stok as $gol => $jumlah)
            @php
              $status = 'Aman'; $color = 'emerald'; $border = 'border-emerald-400/40';
              if ($jumlah < 50) { $status = 'Menipis'; $color = 'amber'; $border = 'border-amber-400/40'; }
              if ($jumlah < 20) { $status = 'Kritis';  $color = 'red';   $border = 'border-red-400/50';   }
            @endphp
            <div class="card group relative" data-gol="{{ strtoupper($gol) }}">
              <div class="absolute -inset-1 rounded-3xl bg-gradient-to-b from-red-500 to-rose-400 opacity-25 blur transition group-hover:opacity-50"></div>
              <div class="{{ $border }} relative rounded-3xl border-2 bg-white p-6 shadow-lg">
                <div class="flex items-center justify-between">
                  <div class="grid h-9 w-9 place-items-center rounded-lg bg-red-600 font-bold text-white ring-4 ring-white">
                    {{ $gol }}
                  </div>
                  <div class="inline-flex items-center gap-2 text-xs">
                    <span class="bg-{{ $color }}-500 h-2.5 w-2.5 rounded-full"></span>
                    <span class="font-medium text-slate-600">{{ $status }}</span>
                  </div>
                </div>
                <div class="mt-6 text-center">
                  <div class="text-5xl font-extrabold tabular-nums tracking-tight" data-counter>
                    {{ number_format($jumlah, 0, ',', '.') }}
                  </div>
                  <div class="text-sm text-slate-600">Unit tersedia</div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-xs text-slate-600">
          * Ambang: ≥50 Aman, 20–49 Menipis, &lt;20 Kritis.
        </div>
      </div>
    </section>

    {{-- TABLE: agregat per produk (SELALU TAMPIL) --}}
    <section class="py-10">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-xl">
          <div class="overflow-x-auto">
            <table class="min-w-full border-collapse text-lg">
              <thead class="bg-red-100/70 text-slate-800">
                <tr class="border-b border-slate-300">
                  <th class="border-r border-slate-300 px-8 py-5 text-left font-extrabold">Stok per Komponen</th>
                  <th class="border-r border-slate-300 px-8 py-5 text-center font-bold">A</th>
                  <th class="border-r border-slate-300 px-8 py-5 text-center font-bold">AB</th>
                  <th class="border-r border-slate-300 px-8 py-5 text-center font-bold">B</th>
                  <th class="border-r border-slate-300 px-8 py-5 text-center font-bold">O</th>
                  <th class="px-8 py-5 text-center font-bold">Total</th>
                </tr>
              </thead>
              <tbody id="tableBody" class="text-slate-800">
                @forelse ($komponenRows as $row)
                  @php $total = ($row['A'] ?? 0) + ($row['AB'] ?? 0) + ($row['B'] ?? 0) + ($row['O'] ?? 0); @endphp
                  <tr class="row-item border-b border-slate-200 transition-colors odd:bg-white even:bg-slate-50 hover:bg-slate-100"
                      data-produk="{{ strtoupper($row['produk'] ?? '-') }}">
                    <td class="border-r border-slate-200 px-8 py-5 font-semibold">{{ $row['produk'] ?? '-' }}</td>
                    <td class="border-r border-slate-200 px-8 py-5 text-center tabular-nums">{{ number_format($row['A'] ?? 0) }}</td>
                    <td class="border-r border-slate-200 px-8 py-5 text-center tabular-nums">{{ number_format($row['AB'] ?? 0) }}</td>
                    <td class="border-r border-slate-200 px-8 py-5 text-center tabular-nums">{{ number_format($row['B'] ?? 0) }}</td>
                    <td class="border-r border-slate-200 px-8 py-5 text-center tabular-nums">{{ number_format($row['O'] ?? 0) }}</td>
                    <td class="px-8 py-5 text-center font-bold tabular-nums">{{ number_format($total) }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="px-8 py-8 text-center text-slate-500">Belum ada data stok.</td>
                  </tr>
                @endforelse
              </tbody>

              <tfoot class="bg-slate-100 text-slate-900">
                <tr class="border-t-2 border-slate-300">
                  <td class="border-r border-slate-300 px-8 py-5 font-extrabold">Jumlah</td>
                  <td class="border-r border-slate-300 px-8 py-5 text-center font-bold tabular-nums">{{ number_format($sumA) }}</td>
                  <td class="border-r border-slate-300 px-8 py-5 text-center font-bold tabular-nums">{{ number_format($sumAB) }}</td>
                  <td class="border-r border-slate-300 px-8 py-5 text-center font-bold tabular-nums">{{ number_format($sumB) }}</td>
                  <td class="border-r border-slate-300 px-8 py-5 text-center font-bold tabular-nums">{{ number_format($sumO) }}</td>
                  <td class="px-8 py-5 text-center font-extrabold tabular-nums">{{ number_format($sumAll) }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        <p class="mt-3 text-sm text-slate-500"></p>
      </div>
    </section>

    <x-footer bg="bg-white" />
  </div>

  {{-- JS: filter serentak untuk kartu + tabel, plus animasi counter --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const input = document.getElementById('q');
      const clear = document.getElementById('clearQ');

      // Elemen kartu & baris tabel
      const cardEls  = Array.from(document.querySelectorAll('#cardGrid .card'));
      const tableRows = Array.from(document.querySelectorAll('#tableBody .row-item'));

      function norm(s){ return (s||'').toString().trim().toUpperCase(); }

      function applyFilter(){
        const q = norm(input?.value);
        const isGolongan = ['A','B','AB','O'].includes(q);

        // Filter kartu: berdasarkan golongan
        cardEls.forEach(el => {
          const gol = norm(el.getAttribute('data-gol'));
          const hit = !q || (isGolongan ? gol === q : false); // jika ketik golongan, tampil 1 kartu tsb
          el.style.display = hit ? '' : 'none';
        });
        // Jika query kosong atau bukan golongan, tampilkan semua kartu lagi
        if (!q || !isGolongan) cardEls.forEach(el => el.style.display = '');

        // Filter tabel: berdasarkan nama komponen / produk
        tableRows.forEach(tr => {
          const produk = norm(tr.getAttribute('data-produk') || tr.cells?.[0]?.innerText);
          tr.style.display = (!q || produk.includes(q)) ? '' : 'none';
        });
      }

      input?.addEventListener('input', applyFilter);
      clear?.addEventListener('click', () => { if(input){ input.value=''; applyFilter(); } });

      // Counter animasi kartu
      const counters = document.querySelectorAll('[data-counter]');
      const easeOut = t => 1 - Math.pow(1 - t, 4);
      const io = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) return;
          const el = entry.target;
          const numeric = (el.textContent || '').replace(/[^\d]/g, '');
          const target = parseInt(numeric, 10) || 0;
          let start = null;
          function step(ts){
            if(!start) start = ts;
            const p = Math.min(1, (ts - start) / 1100);
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
