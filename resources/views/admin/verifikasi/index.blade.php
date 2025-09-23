@extends('layouts.admin')
@section('content')
    <div class="space-y-6">
  {{-- Header --}}
  <div class="space-y-1">
    <h1 class="text-2xl md:text-3xl font-semibold">Verifikasi Pemesanan</h1>
    <p class="text-sm text-neutral-500">Kelola dan verifikasi permintaan darah dari rumah sakit</p>
  </div>

  {{-- Toolbar --}}
  <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
    <div class="relative flex-1">
      <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
        <svg class="size-5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/>
        </svg>
      </span>
      <input id="searchInput" type="text"
             class="w-full rounded-xl border border-neutral-200 bg-white pl-11 pr-3 py-2.5 text-sm placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/10 focus:border-neutral-300"
             placeholder="Cari nama pemesan atau rumah sakit..." />
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
           class="hidden absolute right-0 z-20 mt-2 w-64 rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
        <div class="space-y-3">
          <div>
            <label class="text-xs font-medium text-neutral-500">Status</label>
            <select id="statusSelect"
                    class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              <option value="">Semua</option>
              <option>Approved</option>
              <option>Pending</option>
              <option>Rejected</option>
            </select>
          </div>
          <div>
            <label class="text-xs font-medium text-neutral-500">Golongan Darah</label>
            <select id="golSelect"
                    class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              <option value="">Semua</option>
              <option>A+</option><option>A-</option>
              <option>B+</option><option>B-</option>
              <option>AB+</option><option>AB-</option>
              <option>O+</option><option>O-</option>
            </select>
          </div>
          <div class="flex items-center justify-between">
            <button id="resetBtn" class="text-sm text-neutral-600 hover:underline">Reset</button>
            <button id="applyBtn" class="rounded-lg bg-neutral-900 text-white text-sm px-3 py-1.5 hover:bg-neutral-800">Terapkan</button>
          </div>
        </div>
      </div>
    </div>

    {{-- Page size --}}
    <div class="flex items-center gap-2 sm:ml-auto">
      <label for="pageSize" class="text-sm text-neutral-600">Baris:</label>
      <select id="pageSize" class="rounded-xl border border-neutral-200 bg-white px-2 py-2 text-sm">
        <option>5</option>
        <option selected>10</option>
        <option>20</option>
      </select>
    </div>
  </div>

  {{-- TABLE (≥ md) --}}
  <div class="hidden md:block rounded-2xl border border-neutral-200 bg-white overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 text-neutral-600">
          <tr class="text-left">
            <th data-key="nama" class="sortable px-4 py-3 font-medium cursor-pointer select-none">Nama Pemesan</th>
            <th data-key="rs"   class="sortable px-4 py-3 font-medium cursor-pointer select-none">RS Pemesan</th>
            <th class="px-4 py-3 font-medium">Golongan Darah</th>
            <th data-key="tgl"  class="sortable px-4 py-3 font-medium cursor-pointer select-none">Tanggal permintaan</th>
            <th data-key="produk" class="sortable px-4 py-3 font-medium cursor-pointer select-none">Produk Darah</th>
            <th data-key="status" class="sortable px-4 py-3 font-medium cursor-pointer select-none">Status</th>
            <th class="px-4 py-3 font-medium">Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody"></tbody>
      </table>
    </div>
  </div>

  {{-- CARDS (mobile) --}}
  <div id="cardsContainer" class="md:hidden space-y-3"></div>

  {{-- Pagination footer --}}
  <div class="flex flex-col sm:flex-row gap-3 sm:items-center justify-between">
    <div id="pageInfo" class="text-sm text-neutral-600"></div>
    <div id="pagination" class="flex items-center gap-2"></div>
  </div>
</div>

{{-- ===== Vanilla JS ===== --}}
<script>
  // ---------- Data dummy ----------
  const rows = [
    {nama:'Hendra Gunawan Santoso', rs:'Hendra Gunawan Santoso', gol:'A+',  tgl:'12-02-2025', produk:'PRC', status:'Approved'},
    {nama:'Hendra Gunawan Santoso', rs:'Hendra Gunawan Santoso', gol:'AB+', tgl:'12-02-2025', produk:'WB',  status:'Pending'},
    {nama:'Hendra Gunawan Santoso', rs:'Hendra Gunawan Santoso', gol:'O+',  tgl:'12-02-2025', produk:'TRC', status:'Approved'},
    {nama:'Hendra Gunawan Santoso', rs:'Hendra Gunawan Santoso', gol:'A+',  tgl:'12-02-2025', produk:'FFP', status:'Approved'},
    {nama:'Hendra Gunawan Santoso', rs:'Hendra Gunawan Santoso', gol:'B+',  tgl:'12-02-2025', produk:'PRC', status:'Pending'},
    {nama:'Hendra Gunawan Santoso', rs:'Hendra Gunawan Santoso', gol:'A+',  tgl:'12-02-2025', produk:'TC',  status:'Approved'},
    {nama:'Hendra Gunawan Santoso', rs:'Hendra Gunawan Santoso', gol:'AB+', tgl:'12-02-2025', produk:'WB',  status:'Approved'},
    {nama:'Hendra Gunawan Santoso', rs:'Hendra Gunawan Santoso', gol:'O+',  tgl:'12-02-2025', produk:'FFP', status:'Pending'},
    {nama:'Hendra Gunawan Santoso', rs:'Hendra Gunawan Santoso', gol:'A+',  tgl:'12-02-2025', produk:'PRC', status:'Rejected'},
    {nama:'Hendra Gunawan Santoso', rs:'Hendra Gunawan Santoso', gol:'B+',  tgl:'12-02-2025', produk:'PRC', status:'Approved'},
    // tambah beberapa agar pagination terasa
    {nama:'Andi',  rs:'RS Sejahtera', gol:'O-', tgl:'10-02-2025', produk:'PRC', status:'Approved'},
    {nama:'Budi',  rs:'RS Bakti',     gol:'AB-',tgl:'09-02-2025', produk:'WB',  status:'Pending'},
    {nama:'Citra', rs:'RS Maju',      gol:'B-', tgl:'08-02-2025', produk:'FFP', status:'Approved'},
    {nama:'Dewi',  rs:'RS Harapan',   gol:'A-', tgl:'07-02-2025', produk:'TC',  status:'Rejected'},
  ];

  // ---------- State ----------
  let sortKey = '';      // 'nama' | 'rs' | 'tgl' | 'produk' | 'status'
  let sortDir = 'asc';   // 'asc' | 'desc'
  let currentPage = 1;
  let pageSize = 10;

  // ---------- Helpers ----------
  function statusBadge(s){
    if (s === 'Approved') return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
    if (s === 'Pending')  return 'bg-amber-50 text-amber-700 border border-amber-200';
    return 'bg-rose-50 text-rose-700 border border-rose-200';
  }
  function bloodPill(g){
    const red = 'bg-rose-50 text-rose-600 border-rose-100';
    const blue = 'bg-sky-50 text-sky-700 border-sky-100';
    const color = ['A+','A-','AB+','AB-'].includes(g) ? red : blue;
    return `<span class="inline-flex items-center justify-center h-6 px-2 rounded-full text-xs font-semibold border ${color}">${g}</span>`;
  }
  function productPill(p){
    return `<span class="inline-flex items-center rounded-full border border-sky-200 bg-sky-50 px-2.5 py-0.5 text-xs text-sky-700">${p}</span>`;
  }
  function actionCell(status){
    if (status === 'Approved') return `<span class="inline-flex items-center rounded-lg bg-neutral-100 text-neutral-600 px-3 py-1 text-xs">Disetujui</span>`;
    if (status === 'Rejected') return `<span class="inline-flex items-center rounded-lg bg-neutral-100 text-neutral-600 px-3 py-1 text-xs">Ditolak</span>`;
    return `<div class="flex items-center gap-2">
      <button class="px-3 py-1.5 text-xs rounded-lg border border-blue-200 text-blue-700 bg-blue-50 hover:bg-blue-100">Terima</button>
      <button class="px-3 py-1.5 text-xs rounded-lg border border-rose-200 text-rose-700 bg-rose-50 hover:bg-rose-100">Tolak</button>
    </div>`;
  }

  // ---------- Filter / Sort / Page ----------
  function getFiltered() {
    const q = (document.getElementById('searchInput').value || '').toLowerCase().trim();
    const s = document.getElementById('statusSelect')?.value || '';
    const g = document.getElementById('golSelect')?.value || '';
    return rows.filter(o => {
      const text = (o.nama + ' ' + o.rs).toLowerCase();
      const matchQ = !q || text.includes(q);
      const matchS = !s || o.status === s;
      const matchG = !g || o.gol === g;
      return matchQ && matchS && matchG;
    });
  }

  function getSorted(data) {
    if (!sortKey) return data;
    const copy = [...data];
    copy.sort((a,b) => {
      let va = a[sortKey], vb = b[sortKey];

      if (sortKey === 'tgl') { // dd-mm-yyyy → yyyy-mm-dd
        va = va.split('-').reverse().join('-');
        vb = vb.split('-').reverse().join('-');
      }
      va = String(va).toLowerCase();
      vb = String(vb).toLowerCase();

      if (va < vb) return sortDir === 'asc' ? -1 : 1;
      if (va > vb) return sortDir === 'asc' ?  1 : -1;
      return 0;
    });
    return copy;
  }

  function getPaged(data) {
    const total = data.length;
    const pages = Math.max(1, Math.ceil(total / pageSize));
    currentPage = Math.min(currentPage, pages);
    const start = (currentPage - 1) * pageSize;
    const end   = start + pageSize;
    return { slice: data.slice(start, end), total, pages };
  }

  // ---------- Renderers ----------
  function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    if (data.length === 0) {
      tbody.innerHTML = `<tr><td colspan="7" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>`;
      return;
    }
    tbody.innerHTML = data.map(o => `
      <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
        <td class="px-4 py-3">${o.nama}</td>
        <td class="px-4 py-3">${o.rs}</td>
        <td class="px-4 py-3">${bloodPill(o.gol)}</td>
        <td class="px-4 py-3">${o.tgl}</td>
        <td class="px-4 py-3">${productPill(o.produk)}</td>
        <td class="px-4 py-3"><span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ${statusBadge(o.status)}">${o.status}</span></td>
        <td class="px-4 py-3">${actionCell(o.status)}</td>
      </tr>
    `).join('');
  }

  function renderCards(data) {
    const wrap = document.getElementById('cardsContainer');
    if (data.length === 0) { wrap.innerHTML = `<div class="text-center text-neutral-500">Tidak ada data.</div>`; return; }
    wrap.innerHTML = data.map(o => `
      <div class="rounded-2xl border border-neutral-200 bg-white p-4">
        <div class="flex items-start justify-between">
          <p class="font-medium">${o.nama}</p>
          <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ${statusBadge(o.status)}">${o.status}</span>
        </div>
        <p class="text-xs text-neutral-500">${o.rs}</p>
        <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
          <div class="text-neutral-500">Golongan</div><div>${bloodPill(o.gol)}</div>
          <div class="text-neutral-500">Tanggal</div><div>${o.tgl}</div>
          <div class="text-neutral-500">Produk</div><div>${productPill(o.produk)}</div>
        </div>
        <div class="mt-3">${actionCell(o.status)}</div>
      </div>
    `).join('');
  }

  function renderPagination(total, pages) {
    const cont = document.getElementById('pagination');
    const info = document.getElementById('pageInfo');

    const start = total === 0 ? 0 : (currentPage - 1) * pageSize + 1;
    const end   = Math.min(currentPage * pageSize, total);
    info.textContent = `Menampilkan ${start}-${end} dari ${total} data`;

    if (pages <= 1) { cont.innerHTML = ''; return; }

    let html = '';
    const btn = (label, page, disabled=false, active=false) => `
      <button class="min-w-9 h-9 px-3 rounded-lg border text-sm
                    ${active ? 'bg-neutral-900 text-white border-neutral-900' : 'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'}
                    ${disabled ? 'opacity-50 cursor-not-allowed' : ''}"
              ${disabled ? 'disabled' : ''} data-page="${page}">
        ${label}
      </button>`;
    html += btn('«', currentPage-1, currentPage===1);
    getPageRange(pages, currentPage, 5).forEach(p=>{
      if (p === '…') html += `<span class="px-2 text-neutral-400">…</span>`;
      else html += btn(p, p, false, p===currentPage);
    });
    html += btn('»', currentPage+1, currentPage===pages);

    cont.innerHTML = html;
    cont.querySelectorAll('button[data-page]').forEach(b=>{
      b.addEventListener('click', () => { currentPage = Number(b.dataset.page); renderAll(); });
    });
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

  function markSortHeaders() {
    document.querySelectorAll('th.sortable').forEach(th=>{
      th.querySelector('.sort-ind')?.remove();
      if (th.dataset.key === sortKey) {
        const span = document.createElement('span');
        span.className = 'sort-ind inline-block ml-1 text-neutral-400';
        span.innerHTML = sortDir === 'asc' ? '▲' : '▼';
        th.appendChild(span);
      }
    });
  }

  function renderAll() {
    const filtered = getFiltered();
    const sorted   = getSorted(filtered);
    const { slice, total, pages } = getPaged(sorted);
    renderTable(slice);
    renderCards(slice);
    renderPagination(total, pages);
    markSortHeaders();
  }

  // ---------- Events & Init ----------
  document.addEventListener('DOMContentLoaded', () => {
    // search live
    document.getElementById('searchInput').addEventListener('input', () => { currentPage = 1; renderAll(); });

    // filter
    const btn = document.getElementById('filterBtn');
    const menu = document.getElementById('filterMenu');
    const apply = document.getElementById('applyBtn');
    const reset = document.getElementById('resetBtn');
    const statusSelect = document.getElementById('statusSelect');
    const golSelect = document.getElementById('golSelect');

    btn.addEventListener('click', (e) => { e.stopPropagation(); menu.classList.toggle('hidden'); });
    document.addEventListener('click', (e) => { if (!menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden'); });
    apply.addEventListener('click', () => { menu.classList.add('hidden'); currentPage = 1; renderAll(); });
    reset.addEventListener('click', () => { statusSelect.value=''; golSelect.value=''; currentPage = 1; renderAll(); });

    // page size
    document.getElementById('pageSize').addEventListener('change', (e) => {
      pageSize = Number(e.target.value) || 10; currentPage = 1; renderAll();
    });

    // sort header
    document.querySelectorAll('th.sortable').forEach(th=>{
      th.addEventListener('click', () => {
        const key = th.dataset.key;
        if (sortKey === key) sortDir = (sortDir === 'asc' ? 'desc' : 'asc');
        else { sortKey = key; sortDir = 'asc'; }
        currentPage = 1; renderAll();
      });
    });

    renderAll();
  });
</script>

<style>
  th.sortable:hover { background-color: rgba(0,0,0,0.02); }
</style>

@endsection
