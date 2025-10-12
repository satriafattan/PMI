@extends('layouts.admin')
@section('content')

<div class="space-y-8">
  {{-- ===== Summary cards ===== --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
      <p class="text-xs font-medium text-emerald-700">Total Stok Tersedia</p>
      <div class="mt-2 text-2xl font-semibold text-emerald-800"><span id="sumTotal">0</span> <span class="text-base font-medium">unit</span></div>
    </div>
    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
      <p class="text-xs font-medium text-amber-700">Stok Menipis</p>
      <div class="mt-2 text-2xl font-semibold text-amber-800"><span id="sumLow">0</span> <span class="text-base font-medium">unit</span></div>
    </div>
    <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4">
      <p class="text-xs font-medium text-rose-700">Stok Kritis</p>
      <div class="mt-2 text-2xl font-semibold text-rose-800"><span id="sumCritical">0</span> <span class="text-base font-medium">unit</span></div>
    </div>
    <div class="rounded-2xl border border-neutral-200 bg-neutral-50 p-4">
      <p class="text-xs font-medium text-neutral-600">Total Produk</p>
      <div class="mt-2 text-2xl font-semibold text-neutral-800"><span id="sumProducts">0</span> <span class="text-base font-medium">item</span></div>
    </div>
  </div>

  {{-- ===== Title & toolbar ===== --}}
  <div class="space-y-4">
    <h1 class="text-2xl md:text-3xl font-semibold">Stok Darah</h1>

    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
      <button class="w-full sm:w-auto inline-flex items-center rounded-lg bg-blue-600 text-white text-sm px-3 py-2 hover:bg-blue-700">
        Tambah Stok
      </button>

      <div class="flex-1"></div>

      {{-- Search --}}
      <div class="relative w-full sm:w-80">
        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
          <svg class="size-5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                  d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/>
          </svg>
        </span>
        <input id="searchInput" type="text"
               class="w-full rounded-xl border border-neutral-200 bg-white pl-11 pr-3 py-2.5 text-sm placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/10 focus:border-neutral-300"
               placeholder="Cari" />
      </div>

      {{-- Filter dropdown --}}
      <div class="relative">
        <button id="filterBtn"
          class="inline-flex items-center justify-center rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50">
          <svg class="size-5 text-neutral-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 6h18M6 12h12M10 18h4"/>
          </svg>
        </button>
        <div id="filterMenu"
             class="hidden absolute right-0 z-20 mt-2 w-56 rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
          <div class="space-y-3">
            <div>
              <label class="text-xs font-medium text-neutral-500">Tampilkan</label>
              <select id="statusSelect"
                      class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                <option value="all">Semua</option>
                <option value="low">Menipis (&lt; 50)</option>
                <option value="critical">Kritis (&lt; 20)</option>
              </select>
            </div>
            <div class="flex items-center justify-between">
              <button id="resetBtn" class="text-sm text-neutral-600 hover:underline">Reset</button>
              <button id="applyBtn" class="rounded-lg bg-neutral-900 text-white text-sm px-3 py-1.5 hover:bg-neutral-800">Terapkan</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== TABLE (≥ md) ===== --}}
  <div class="hidden md:block rounded-2xl border border-neutral-200 bg-white overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 text-neutral-600">
          <tr class="text-left">
            <th data-key="produk" class="sortable px-4 py-3 font-medium cursor-pointer select-none">Produk</th>
            <th data-key="A"      class="sortable px-4 py-3 font-medium cursor-pointer select-none">A</th>
            <th data-key="AB"     class="sortable px-4 py-3 font-medium cursor-pointer select-none">AB</th>
            <th data-key="B"      class="sortable px-4 py-3 font-medium cursor-pointer select-none">B</th>
            <th data-key="O"      class="sortable px-4 py-3 font-medium cursor-pointer select-none">O</th>
            <th data-key="total"  class="sortable px-4 py-3 font-medium cursor-pointer select-none">Total</th>
            <th class="px-4 py-3 font-medium">Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody"></tbody>
      </table>
    </div>
  </div>

  {{-- ===== CARDS (mobile) ===== --}}
  <div id="cardsContainer" class="md:hidden space-y-3"></div>
</div>

{{-- ===== Vanilla JS (data dummy + filter + sort) ===== --}}
<script>
  // --- Data dummy di Blade (satu halaman) ---
  const products = [
    {produk:'WB: Whole Blood',           A:42, AB:18, B:60, O:75},
    {produk:'PRC: Packed Red Cell',      A:15, AB:22, B:33, O:40},
    {produk:'TC: Trombocyte Concentrate',A:55, AB:28, B:19, O:31},
    {produk:'FFP: Fresh Frozen Plasma',  A:73, AB:47, B:52, O:26},
    {produk:'AHF: Cryoprecipitated AHF', A:14, AB:12, B:18, O:10},
    {produk:'LP: Liquid Plasma',         A:65, AB:63, B:59, O:61},
    {produk:'TC Aferesis',               A:24, AB:21, B:20, O:19},
    {produk:'Plasma Konvalesen',         A:36, AB:49, B:41, O:29},
  ];

  const LOW_TH = 50, CRIT_TH = 20;

  // --- state sort ---
  let sortKey = '';    // 'produk' | 'A' | 'AB' | 'B' | 'O' | 'total'
  let sortDir = 'asc';

  // --- icons & helpers ---
  const iconEdit = () => `<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 20h9M16.5 3.5l4 4L7 21H3v-4L16.5 3.5z"/></svg>`;
  const iconTrash= () => `<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M9 7V5h6v2m-8 0 1 12h8l1-12"/></svg>`;
  const rowTotal = (r) => r.A + r.AB + r.B + r.O;

  function pillFor(v){
    if (v < CRIT_TH) return `<span class="inline-flex items-center justify-center min-w-10 px-2 py-0.5 rounded-full text-xs font-semibold border border-rose-200 bg-rose-50 text-rose-700">${v}</span>`;
    if (v < LOW_TH)  return `<span class="inline-flex items-center justify-center min-w-10 px-2 py-0.5 rounded-full text-xs font-semibold border border-amber-200 bg-amber-50 text-amber-700">${v}</span>`;
    return `<span class="inline-flex items-center justify-center min-w-10 px-2 py-0.5 rounded-full text-xs font-semibold border border-emerald-200 bg-emerald-50 text-emerald-700">${v}</span>`;
  }

  // --- summary cards (dari seluruh data) ---
  function renderSummary(){
    let total=0, low=0, critical=0;
    products.forEach(r=>{
      total += rowTotal(r);
      [r.A,r.AB,r.B,r.O].forEach(v=>{
        if (v < CRIT_TH) critical += v;
        else if (v < LOW_TH) low += v;
      });
    });
    document.getElementById('sumTotal').textContent = total;
    document.getElementById('sumLow').textContent = low;
    document.getElementById('sumCritical').textContent = critical;
    document.getElementById('sumProducts').textContent = products.length;
  }

  // --- filter + sort ---
  function getFiltered(){
    const q = (document.getElementById('searchInput').value || '').toLowerCase().trim();
    const mode = document.getElementById('statusSelect')?.value || 'all';
    return products.filter(r=>{
      const matchQ = !q || r.produk.toLowerCase().includes(q);
      let matchM = true;
      if (mode === 'low')      matchM = [r.A,r.AB,r.B,r.O].some(v => v < LOW_TH && v >= CRIT_TH);
      if (mode === 'critical') matchM = [r.A,r.AB,r.B,r.O].some(v => v < CRIT_TH);
      return matchQ && matchM;
    });
  }
  function getSorted(data){
    if (!sortKey) return data;
    const cp = [...data];
    cp.sort((a,b)=>{
      let va = (sortKey === 'total') ? rowTotal(a) : a[sortKey];
      let vb = (sortKey === 'total') ? rowTotal(b) : b[sortKey];
      if (typeof va === 'number' && typeof vb === 'number') return sortDir === 'asc' ? va - vb : vb - va;
      va = String(va).toLowerCase(); vb = String(vb).toLowerCase();
      if (va < vb) return sortDir === 'asc' ? -1 : 1;
      if (va > vb) return sortDir === 'asc' ?  1 : -1;
      return 0;
    });
    return cp;
  }

  // --- renderers ---
  function renderTable(data){
    const tb = document.getElementById('tableBody');
    if (data.length === 0) { tb.innerHTML = `<tr><td colspan="7" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>`; return; }
    tb.innerHTML = data.map(r=>`
      <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
        <td class="px-4 py-3">${r.produk}</td>
        <td class="px-4 py-3">${pillFor(r.A)}</td>
        <td class="px-4 py-3">${pillFor(r.AB)}</td>
        <td class="px-4 py-3">${pillFor(r.B)}</td>
        <td class="px-4 py-3">${pillFor(r.O)}</td>
        <td class="px-4 py-3 font-medium">${rowTotal(r)}</td>
        <td class="px-4 py-3">
          <div class="flex items-center gap-4 text-neutral-600">
            <button class="hover:text-neutral-900" title="Edit">${iconEdit()}</button>
            <button class="hover:text-rose-700"  title="Hapus">${iconTrash()}</button>
          </div>
        </td>
      </tr>
    `).join('');
  }
  function renderCards(data){
    const wrap = document.getElementById('cardsContainer');
    if (data.length === 0) { wrap.innerHTML = `<div class="text-center text-neutral-500">Tidak ada data.</div>`; return; }
    wrap.innerHTML = data.map(r=>`
      <div class="rounded-2xl border border-neutral-200 bg-white p-4">
        <div class="flex items-start justify-between">
          <p class="font-medium">${r.produk}</p>
          <div class="flex items-center gap-3 text-neutral-600">
            <button class="hover:text-neutral-900" title="Edit">${iconEdit()}</button>
            <button class="hover:text-rose-700"  title="Hapus">${iconTrash()}</button>
          </div>
        </div>
        <div class="mt-3 grid grid-cols-4 gap-2 text-sm">
          <div class="text-neutral-500">A</div><div>${pillFor(r.A)}</div>
          <div class="text-neutral-500">AB</div><div>${pillFor(r.AB)}</div>
          <div class="text-neutral-500">B</div><div>${pillFor(r.B)}</div>
          <div class="text-neutral-500">O</div><div>${pillFor(r.O)}</div>
          <div class="text-neutral-500">Total</div><div class="col-span-3 font-medium">${rowTotal(r)}</div>
        </div>
      </div>
    `).join('');
  }
  function markSortHeaders(){
    document.querySelectorAll('th.sortable').forEach(th=>{
      th.querySelector('.sort-ind')?.remove();
      if (th.dataset.key === sortKey) {
        const s = document.createElement('span');
        s.className = 'sort-ind inline-block ml-1 text-neutral-400';
        s.innerHTML = sortDir === 'asc' ? '▲' : '▼';
        th.appendChild(s);
      }
    });
  }

  function renderAll(){
    const filtered = getFiltered();
    const sorted   = getSorted(filtered);
    renderTable(sorted);
    renderCards(sorted);
    markSortHeaders();
  }

  // --- init + events ---
  document.addEventListener('DOMContentLoaded', ()=>{
    renderSummary();
    document.getElementById('searchInput').addEventListener('input', renderAll);

    const btn = document.getElementById('filterBtn');
    const menu = document.getElementById('filterMenu');
    const apply = document.getElementById('applyBtn');
    const reset = document.getElementById('resetBtn');
    const statusSelect = document.getElementById('statusSelect');

    btn.addEventListener('click', (e)=>{ e.stopPropagation(); menu.classList.toggle('hidden'); });
    document.addEventListener('click', (e)=>{ if (!menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden'); });
    apply.addEventListener('click', ()=>{ menu.classList.add('hidden'); renderAll(); });
    reset.addEventListener('click', ()=>{ statusSelect.value='all'; renderAll(); });

    // sort header
    document.querySelectorAll('th.sortable').forEach(th=>{
      th.addEventListener('click', ()=>{
        const key = th.dataset.key;
        if (sortKey === key) sortDir = (sortDir === 'asc' ? 'desc' : 'asc');
        else { sortKey = key; sortDir = 'asc'; }
        renderAll();
      });
    });

    renderAll();
  });
</script>

<style>
  th.sortable:hover { background-color: rgba(0,0,0,0.02); }
</style>
@endsection
