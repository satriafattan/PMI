{{-- resources/views/admin/detail/index.blade.php --}}
@extends('layouts.admin')
@section('title','Informasi Detail Darah')

@section('content')
@php
  // Fallback data jika controller belum mengirim $rows
  $rows = $rows ?? [
    ['id_darah' => 'WB-001', 'gol_darah'=>'A',  'rhesus'=>'+', 'tgl_masuk'=>'2025-01-04', 'tgl_kadaluarsa'=>'2025-02-04', 'komponen'=>'WB: Whole Blood'],
    ['id_darah' => 'PRC-002','gol_darah'=>'O',  'rhesus'=>'+', 'tgl_masuk'=>'2025-01-03', 'tgl_kadaluarsa'=>'2025-03-03', 'komponen'=>'PRC: Packed Red Cell'],
    ['id_darah' => 'TC-003', 'gol_darah'=>'B',  'rhesus'=>'-', 'tgl_masuk'=>'2025-01-01', 'tgl_kadaluarsa'=>'2025-01-20', 'komponen'=>'TC: Trombocyte Concentrate'],
    ['id_darah' => 'FFP-004','gol_darah'=>'AB', 'rhesus'=>'+', 'tgl_masuk'=>'2025-01-08', 'tgl_kadaluarsa'=>'2025-04-08', 'komponen'=>'FFP: Fresh Frozen Plasma'],
    ['id_darah' => 'AHF-005','gol_darah'=>'A',  'rhesus'=>'-', 'tgl_masuk'=>'2025-01-09', 'tgl_kadaluarsa'=>'2025-03-09', 'komponen'=>'AHF: Cryoprecipitated AHF'],
    ['id_darah' => 'LP-006', 'gol_darah'=>'O',  'rhesus'=>'+', 'tgl_masuk'=>'2025-01-10', 'tgl_kadaluarsa'=>'2025-04-10', 'komponen'=>'LP: Liquid Plasma'],
    ['id_darah' => 'TCA-007','gol_darah'=>'AB', 'rhesus'=>'-', 'tgl_masuk'=>'2025-01-02', 'tgl_kadaluarsa'=>'2025-01-25', 'komponen'=>'TC Afereris'],
    ['id_darah' => 'PK-008', 'gol_darah'=>'B',  'rhesus'=>'+', 'tgl_masuk'=>'2025-01-05', 'tgl_kadaluarsa'=>'2025-03-05', 'komponen'=>'Plasma Konvalesen'],
  ];

  // Opsi komponen (untuk filter); silakan samakan dengan data produksi Anda
  $kompOpts = [
    'WB: Whole Blood','PRC: Packed Red Cell','TC: Trombocyte Concentrate',
    'FFP: Fresh Frozen Plasma','AHF: Cryoprecipitated AHF','LP: Liquid Plasma',
    'TC Afereris','Plasma Konvalesen'
  ];
@endphp

<div class="space-y-6">
  {{-- Header --}}
  <div class="space-y-1">
    <h1 class="text-2xl md:text-3xl font-semibold">Informasi Detail Darah</h1>
    <p class="text-sm text-neutral-500">Data unit darah per kantong beserta golongan, rhesus, serta masa berlaku</p>
  </div>

  {{-- Toolbar: search full width + filter kecil di kanan + page size --}}
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex w-full items-center gap-2 sm:flex-1">
      {{-- Search full width --}}
      <div class="relative flex-1">
        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
          <svg class="size-5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                  d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/>
          </svg>
        </span>
        <input id="searchInput" type="text"
               class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-900/10"
               placeholder="Cari ID darah atau komponen…" />
      </div>

      {{-- Filter btn + menu --}}
      <div class="relative">
        <button id="filterBtn" type="button"
                class="inline-flex items-center justify-center rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50">
          <svg class="size-5 text-neutral-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 6h18M6 12h12M10 18h4"/>
          </svg>
        </button>

        <div id="filterMenu"
             class="absolute right-0 z-20 mt-2 hidden w-[22rem] rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
          <div class="grid gap-3 sm:grid-cols-2">
            {{-- Golongan --}}
            <div>
              <label class="text-xs font-medium text-neutral-500">Golongan</label>
              <select id="golSelect"
                      class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach (['A','B','AB','O'] as $g)
                  <option value="{{ $g }}">{{ $g }}</option>
                @endforeach
              </select>
            </div>

            {{-- Rhesus --}}
            <div>
              <label class="text-xs font-medium text-neutral-500">Rhesus</label>
              <select id="rhesusSelect"
                      class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                <option value="">Semua</option>
                <option value="+">+</option>
                <option value="-">-</option>
              </select>
            </div>

            {{-- Komponen --}}
            <div class="sm:col-span-2">
              <label class="text-xs font-medium text-neutral-500">Komponen</label>
              <select id="kompSelect"
                      class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                <option value="">Semua</option>
                @foreach ($kompOpts as $opt)
                  <option value="{{ $opt }}">{{ $opt }}</option>
                @endforeach
              </select>
            </div>

            {{-- Rentang tanggal masuk --}}
            <div>
              <label class="text-xs font-medium text-neutral-500">Tgl Masuk (dari)</label>
              <input type="date" id="masukFrom"
                     class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
            </div>
            <div>
              <label class="text-xs font-medium text-neutral-500">Tgl Masuk (hingga)</label>
              <input type="date" id="masukTo"
                     class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
            </div>

            {{-- Rentang tanggal kadaluarsa --}}
            <div>
              <label class="text-xs font-medium text-neutral-500">Kadaluarsa (dari)</label>
              <input type="date" id="expFrom"
                     class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
            </div>
            <div>
              <label class="text-xs font-medium text-neutral-500">Kadaluarsa (hingga)</label>
              <input type="date" id="expTo"
                     class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
            </div>

            <div class="sm:col-span-2 flex items-center justify-between">
              <button type="button" id="resetBtn"
                      class="text-sm text-neutral-600 hover:underline">Reset</button>
              <button type="button" id="applyBtn"
                      class="rounded-lg bg-neutral-900 px-3 py-1.5 text-sm text-white hover:bg-neutral-800">
                Terapkan
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Page size --}}
    <div class="flex items-center gap-2">
      <label for="pageSize" class="text-sm text-neutral-600">Baris:</label>
      <select id="pageSize" class="rounded-xl border border-neutral-200 bg-white px-2 py-2 text-sm">
        <option>5</option><option selected>10</option><option>20</option>
      </select>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="rounded-2xl border border-neutral-200 bg-white overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 text-neutral-600">
          <tr class="text-left">
            <th data-key="id_darah"       class="sortable px-4 py-3 font-medium cursor-pointer select-none">Id Darah</th>
            <th data-key="gol_darah"      class="sortable px-4 py-3 font-medium cursor-pointer select-none">Golongan Darah</th>
            <th data-key="tgl_masuk"      class="sortable px-4 py-3 font-medium cursor-pointer select-none">Tanggal Masuk</th>
            <th data-key="tgl_kadaluarsa" class="sortable px-4 py-3 font-medium cursor-pointer select-none">Kadaluarsa</th>
            <th data-key="rhesus"         class="sortable px-4 py-3 font-medium cursor-pointer select-none">Rhesus</th>
            <th data-key="komponen"       class="sortable px-4 py-3 font-medium cursor-pointer select-none">Komponen Darah</th>
          </tr>
        </thead>
        <tbody id="tableBody"></tbody>
      </table>
    </div>
  </div>

  {{-- Pagination info --}}
  <div class="flex flex-col sm:flex-row gap-3 sm:items-center justify-between">
    <div id="pageInfo" class="text-sm text-neutral-600"></div>
    <div id="pagination" class="flex items-center gap-2"></div>
  </div>
</div>

{{-- ===== Scripts ===== --}}
<script>
  // Data dari PHP
  const rows = @json($rows);

  let sortKey = 'id_darah';
  let sortDir = 'asc';
  let currentPage = 1;
  let pageSize = 10;

  function toYmd(s){ return String(s || ''); }
  function inRange(dateStr, fromStr, toStr){
    if(!dateStr) return true;
    const d = toYmd(dateStr);
    if(fromStr && d < toYmd(fromStr)) return false;
    if(toStr && d > toYmd(toStr))     return false;
    return true;
  }

  function getFiltered(){
    const q = (document.getElementById('searchInput').value || '').toLowerCase().trim();
    const gol = document.getElementById('golSelect').value;
    const rh  = document.getElementById('rhesusSelect').value;
    const kp  = document.getElementById('kompSelect').value;
    const masukFrom = document.getElementById('masukFrom').value;
    const masukTo   = document.getElementById('masukTo').value;
    const expFrom   = document.getElementById('expFrom').value;
    const expTo     = document.getElementById('expTo').value;

    return rows.filter(o=>{
      const hitQ = !q || String(o.id_darah).toLowerCase().includes(q) || String(o.komponen).toLowerCase().includes(q);
      const hitG = !gol || String(o.gol_darah) === gol;
      const hitR = !rh  || String(o.rhesus) === rh;
      const hitK = !kp  || String(o.komponen) === kp;
      const hitMasuk = inRange(o.tgl_masuk, masukFrom, masukTo);
      const hitExp   = inRange(o.tgl_kadaluarsa, expFrom, expTo);
      return hitQ && hitG && hitR && hitK && hitMasuk && hitExp;
    });
  }

  function getSorted(data){
    if(!sortKey) return data;
    const cp = [...data];
    cp.sort((a,b)=>{
      let va = a[sortKey], vb = b[sortKey];

      // Tanggal sort dengan string Y-m-d sudah lexicographic friendly
      // Default: string/number compare
      const isNum = typeof va === 'number' && typeof vb === 'number';
      if(isNum) return sortDir === 'asc' ? va - vb : vb - va;
      va = String(va ?? '').toLowerCase();
      vb = String(vb ?? '').toLowerCase();
      if(va < vb) return sortDir === 'asc' ? -1 : 1;
      if(va > vb) return sortDir === 'asc' ?  1 : -1;
      return 0;
    });
    return cp;
  }

  function getPaged(data){
    const total = data.length;
    const pages = Math.max(1, Math.ceil(total / pageSize));
    currentPage = Math.min(currentPage, pages);
    const start = (currentPage - 1) * pageSize;
    const end   = start + pageSize;
    return { slice: data.slice(start, end), total, pages };
  }

  function renderTable(data){
    const tb = document.getElementById('tableBody');
    if(data.length === 0){
      tb.innerHTML = `<tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>`;
      return;
    }
    tb.innerHTML = data.map(o => `
      <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
        <td class="px-4 py-3 font-medium text-neutral-800">${o.id_darah ?? '-'}</td>
        <td class="px-4 py-3">${o.gol_darah ?? '-'}</td>
        <td class="px-4 py-3">${o.tgl_masuk ?? '-'}</td>
        <td class="px-4 py-3">${o.tgl_kadaluarsa ?? '-'}</td>
        <td class="px-4 py-3">${o.rhesus ?? '-'}</td>
        <td class="px-4 py-3">${o.komponen ?? '-'}</td>
      </tr>
    `).join('');
  }

  function getPageRange(totalPages, current, max=5) {
    const pages = [];
    const half = Math.floor(max/2);
    let start = Math.max(1, current - half);
    let end   = Math.min(totalPages, start + max - 1);
    if (end - start + 1 < max) start = Math.max(1, end - max + 1);
    if (start > 1) { pages.push(1); if (start > 2) pages.push('…'); }
    for (let i=start;i<=end;i++) pages.push(i);
    if (end < totalPages) { if (end < totalPages-1) pages.push('…'); pages.push(totalPages); }
    return pages;
  }

  function renderPagination(total, pages){
    const cont = document.getElementById('pagination');
    const info = document.getElementById('pageInfo');
    const start = total === 0 ? 0 : (currentPage - 1) * pageSize + 1;
    const end   = Math.min(currentPage * pageSize, total);
    info.textContent = `Menampilkan ${start}-${end} dari ${total} data`;

    if(pages <= 1){ cont.innerHTML = ''; return; }

    const btn = (label, page, disabled=false, active=false) => `
      <button
        class="min-w-9 h-9 px-3 rounded-lg border text-sm
               ${active ? 'bg-neutral-900 text-white border-neutral-900' : 'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'}
               ${disabled ? 'opacity-50 cursor-not-allowed' : ''}"
        ${disabled ? 'disabled' : ''} data-page="${page}"
      >${label}</button>`;

    let html = '';
    html += btn('«', currentPage-1, currentPage===1);
    const range = getPageRange(pages, currentPage, 5);
    range.forEach(p=>{
      if(p==='…') html += `<span class="px-2 text-neutral-400">…</span>`;
      else html += btn(p, p, false, p===currentPage);
    });
    html += btn('»', currentPage+1, currentPage===pages);
    cont.innerHTML = html;

    cont.querySelectorAll('button[data-page]').forEach(b=>{
      b.addEventListener('click', () => {
        const p = Number(b.dataset.page);
        if(!Number.isNaN(p)){ currentPage = p; renderAll(); }
      });
    });
  }

  function markSortHeaders(){
    document.querySelectorAll('th.sortable').forEach(th=>{
      th.querySelector('.sort-ind')?.remove();
      if(th.dataset.key === sortKey){
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
    const { slice, total, pages } = getPaged(sorted);
    renderTable(slice);
    renderPagination(total, pages);
    markSortHeaders();
  }

  document.addEventListener('DOMContentLoaded', () => {
    // Search
    document.getElementById('searchInput').addEventListener('input', () => { currentPage = 1; renderAll(); });

    // Filter menu
    const btn = document.getElementById('filterBtn');
    const menu = document.getElementById('filterMenu');
    const apply = document.getElementById('applyBtn');
    const reset = document.getElementById('resetBtn');

    btn.addEventListener('click', (e)=>{ e.stopPropagation(); menu.classList.toggle('hidden'); });
    document.addEventListener('click', (e)=>{
      if(menu && btn && !menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden');
    });
    apply.addEventListener('click', ()=>{ menu.classList.add('hidden'); currentPage = 1; renderAll(); });
    reset.addEventListener('click', ()=>{
      ['golSelect','rhesusSelect','kompSelect','masukFrom','masukTo','expFrom','expTo']
        .forEach(id => { const el = document.getElementById(id); if(el) el.value = ''; });
      currentPage = 1; renderAll();
    });

    // Page size
    document.getElementById('pageSize').addEventListener('change', (e)=>{
      pageSize = Number(e.target.value) || 10;
      currentPage = 1; renderAll();
    });

    // Sort
    document.querySelectorAll('th.sortable').forEach(th=>{
      th.addEventListener('click', ()=>{
        const key = th.dataset.key;
        if(sortKey === key) sortDir = (sortDir === 'asc' ? 'desc' : 'asc');
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
