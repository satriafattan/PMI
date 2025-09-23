@extends('layouts.admin')
@section('content')

<div class="space-y-6">
  {{-- Header --}}
  <h1 class="text-2xl md:text-3xl font-semibold">Riwayat Pemesanan</h1>

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

    <div class="flex items-center gap-2">
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
            <div class="flex items-center justify-between">
              <button id="resetBtn" class="text-sm text-neutral-600 hover:underline">Reset</button>
              <button id="applyBtn" class="rounded-lg bg-neutral-900 text-white text-sm px-3 py-1.5 hover:bg-neutral-800">Terapkan</button>
            </div>
          </div>
        </div>
      </div>

      {{-- Page size --}}
      <div class="flex items-center gap-2">
        <label for="pageSize" class="text-sm text-neutral-600">Baris:</label>
        <select id="pageSize" class="rounded-xl border border-neutral-200 bg-white px-2 py-2 text-sm">
          <option>5</option>
          <option selected>10</option>
          <option>20</option>
        </select>
      </div>
    </div>
  </div>

  {{-- TABLE (≥ md) --}}
  <div class="hidden md:block rounded-2xl border border-neutral-200 bg-white overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 text-neutral-600">
          <tr class="text-left">
            <th data-key="nama"   class="sortable px-4 py-3 font-medium cursor-pointer select-none">Nama Lengkap</th>
            <th data-key="tgl"    class="sortable px-4 py-3 font-medium cursor-pointer select-none">Tanggal Pemesanan</th>
            <th data-key="gol"    class="px-4 py-3 font-medium">Golongan Darah</th>
            <th data-key="rhesus" class="sortable px-4 py-3 font-medium cursor-pointer select-none">Rhesus</th>
            <th data-key="produk" class="sortable px-4 py-3 font-medium cursor-pointer select-none">Produk Darah</th>
            <th data-key="kantong"class="sortable px-4 py-3 font-medium cursor-pointer select-none">Jumlah Kantong</th>
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

  {{-- Pagination --}}
  <div class="flex flex-col sm:flex-row gap-3 sm:items-center justify-between">
    <div id="pageInfo" class="text-sm text-neutral-600"></div>
    <div id="pagination" class="flex items-center gap-2"></div>
  </div>
</div>

{{-- ===== Vanilla JS ===== --}}
<script>
  // ---------- Data dummy ----------
  const rows = [
    {nama:'Lorem Ipsum Dolor Sit Amet', tgl:'12-02-2025', gol:'A', rhesus:'Rh+', produk:'PRC', kantong:1, status:'Approved'},
    {nama:'Lorem Ipsum Dolor Sit Amet', tgl:'12-02-2025', gol:'A', rhesus:'Rh+', produk:'WB',  kantong:1, status:'Pending'},
    {nama:'Lorem Ipsum Dolor Sit Amet', tgl:'12-02-2025', gol:'A', rhesus:'Rh+', produk:'TRC', kantong:1, status:'Approved'},
    {nama:'Lorem Ipsum Dolor Sit Amet', tgl:'12-02-2025', gol:'A', rhesus:'Rh+', produk:'FFP', kantong:1, status:'Approved'},
    {nama:'Lorem Ipsum Dolor Sit Amet', tgl:'12-02-2025', gol:'A', rhesus:'Rh+', produk:'PRC', kantong:1, status:'Pending'},
    {nama:'Lorem Ipsum Dolor Sit Amet', tgl:'12-02-2025', gol:'A', rhesus:'Rh+', produk:'TC',  kantong:1, status:'Approved'},
    {nama:'Lorem Ipsum Dolor Sit Amet', tgl:'12-02-2025', gol:'A', rhesus:'Rh+', produk:'WB',  kantong:1, status:'Approved'},
    {nama:'Lorem Ipsum Dolor Sit Amet', tgl:'12-02-2025', gol:'A', rhesus:'Rh+', produk:'FFP', kantong:1, status:'Pending'},
    {nama:'Lorem Ipsum Dolor Sit Amet', tgl:'12-02-2025', gol:'A', rhesus:'Rh+', produk:'PRC', kantong:1, status:'Rejected'},
    {nama:'Lorem Ipsum Dolor Sit Amet', tgl:'12-02-2025', gol:'A', rhesus:'Rh+', produk:'PRC', kantong:1, status:'Approved'},
    // tambahkan lebih banyak jika ingin melihat pagination bekerja
    {nama:'John Doe', tgl:'13-02-2025', gol:'B', rhesus:'Rh-', produk:'PRC', kantong:2, status:'Approved'},
    {nama:'Jane Roe', tgl:'14-02-2025', gol:'O', rhesus:'Rh+', produk:'WB',  kantong:3, status:'Pending'},
    {nama:'Ahmad',    tgl:'10-02-2025', gol:'AB',rhesus:'Rh+', produk:'FFP', kantong:2, status:'Approved'},
    {nama:'Siti',     tgl:'11-02-2025', gol:'A', rhesus:'Rh-', produk:'TC',  kantong:1, status:'Rejected'},
  ];

  // ---------- State ----------
  let sortKey = '';   // 'nama' | 'tgl' | 'rhesus' | ...
  let sortDir = 'asc';// 'asc' | 'desc'
  let currentPage = 1;
  let pageSize = 10;

  // ---------- Helpers ----------
  function badgeClass(s){
    if (s === 'Approved') return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
    if (s === 'Pending')  return 'bg-amber-50 text-amber-700 border border-amber-200';
    return 'bg-rose-50 text-rose-700 border border-rose-200';
  }
  function bloodPill(g){
    return `<span class="inline-flex items-center justify-center size-6 rounded-full bg-rose-50 text-rose-600 text-xs font-semibold border border-rose-100">${g}</span>`;
  }
  const icons = {
    eye:`<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12zm10-3.5A3.5 3.5 0 1 1 8.5 12 3.5 3.5 0 0 1 12 8.5z"/></svg>`,
    edit:`<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 20h9M16.5 3.5l4 4L7 21H3v-4L16.5 3.5z"/></svg>`,
    dup:`<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 8h12v12H8zM4 4h12v12H4z"/></svg>`,
    del:`<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M9 7V5h6v2m-8 0 1 12h8l1-12"/></svg>`
  };

  // ---------- Filtering, Sorting, Paging ----------
  function getFiltered() {
    const q = (document.getElementById('searchInput').value || '').toLowerCase().trim();
    const s = document.getElementById('statusSelect')?.value || '';
    return rows.filter(o => {
      const matchQ = !q || o.nama.toLowerCase().includes(q);
      const matchS = !s || o.status === s;
      return matchQ && matchS;
    });
  }

  function getSorted(data) {
    if (!sortKey) return data;
    const copy = [...data];
    copy.sort((a,b) => {
      let va = a[sortKey], vb = b[sortKey];
      // angka vs string
      const isNum = typeof va === 'number' && typeof vb === 'number';
      if (isNum) return sortDir === 'asc' ? va - vb : vb - va;
      // tanggal dd-mm-yyyy → yyyy-mm-dd untuk compare
      if (sortKey === 'tgl') {
        const ta = va.split('-').reverse().join('-');
        const tb = vb.split('-').reverse().join('-');
        if (ta < tb) return sortDir === 'asc' ? -1 : 1;
        if (ta > tb) return sortDir === 'asc' ? 1 : -1;
        return 0;
      }
      // string umum
      va = String(va).toLowerCase(); vb = String(vb).toLowerCase();
      if (va < vb) return sortDir === 'asc' ? -1 : 1;
      if (va > vb) return sortDir === 'asc' ? 1 : -1;
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
      tbody.innerHTML = `<tr><td colspan="8" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>`;
      return;
    }
    tbody.innerHTML = data.map(o => `
      <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
        <td class="px-4 py-3">${o.nama}</td>
        <td class="px-4 py-3">${o.tgl}</td>
        <td class="px-4 py-3">${bloodPill(o.gol)}</td>
        <td class="px-4 py-3">${o.rhesus}</td>
        <td class="px-4 py-3"><a href="#" class="text-blue-600 hover:underline">${o.produk}</a></td>
        <td class="px-4 py-3">${o.kantong}</td>
        <td class="px-4 py-3"><span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ${badgeClass(o.status)}">${o.status}</span></td>
        <td class="px-4 py-3">
          <div class="flex items-center gap-3 text-neutral-600">
            <button class="hover:text-neutral-900" title="Lihat">${icons.eye}</button>
            <button class="hover:text-neutral-900" title="Edit">${icons.edit}</button>
            <button class="hover:text-neutral-900" title="Duplikasi">${icons.dup}</button>
            <button class="hover:text-rose-700" title="Hapus">${icons.del}</button>
          </div>
        </td>
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
          <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ${badgeClass(o.status)}">${o.status}</span>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
          <div class="text-neutral-500">Tanggal</div><div>${o.tgl}</div>
          <div class="text-neutral-500">Golongan</div><div>${bloodPill(o.gol)}</div>
          <div class="text-neutral-500">Rhesus</div><div>${o.rhesus}</div>
          <div class="text-neutral-500">Produk</div><div>${o.produk}</div>
          <div class="text-neutral-500">Kantong</div><div>${o.kantong}</div>
        </div>
        <div class="mt-3 flex items-center gap-4 text-neutral-600">
          <button class="hover:text-neutral-900" title="Lihat">${icons.eye}</button>
          <button class="hover:text-neutral-900" title="Edit">${icons.edit}</button>
          <button class="hover:text-neutral-900" title="Duplikasi">${icons.dup}</button>
          <button class="hover:text-rose-700" title="Hapus">${icons.del}</button>
        </div>
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
      <button
        class="min-w-9 h-9 px-3 rounded-lg border text-sm
               ${active ? 'bg-neutral-900 text-white border-neutral-900' : 'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'}
               ${disabled ? 'opacity-50 cursor-not-allowed' : ''}"
        ${disabled ? 'disabled' : ''} data-page="${page}"
      >${label}</button>`;

    html += btn('«', currentPage-1, currentPage===1);
    const range = getPageRange(pages, currentPage, 5);
    range.forEach(p => {
      if (p === '…') html += `<span class="px-2 text-neutral-400">…</span>`;
      else html += btn(p, p, false, p===currentPage);
    });
    html += btn('»', currentPage+1, currentPage===pages);

    cont.innerHTML = html;

    // bind
    cont.querySelectorAll('button[data-page]').forEach(b=>{
      b.addEventListener('click', () => {
        const p = Number(b.dataset.page);
        if (!Number.isNaN(p)) { currentPage = p; renderAll(); }
      });
    });
  }

  function getPageRange(totalPages, current, max=5) {
    // menghasilkan array nomor halaman dengan elipsis jika perlu
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

  // ---------- Master render ----------
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
    // search
    document.getElementById('searchInput').addEventListener('input', () => { currentPage = 1; renderAll(); });

    // filter menu
    const btn = document.getElementById('filterBtn');
    const menu = document.getElementById('filterMenu');
    const apply = document.getElementById('applyBtn');
    const reset = document.getElementById('resetBtn');
    const statusSelect = document.getElementById('statusSelect');

    btn.addEventListener('click', (e) => { e.stopPropagation(); menu.classList.toggle('hidden'); });
    document.addEventListener('click', (e) => { if (!menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden'); });
    apply.addEventListener('click', () => { menu.classList.add('hidden'); currentPage = 1; renderAll(); });
    reset.addEventListener('click', () => { statusSelect.value = ''; currentPage = 1; renderAll(); });

    // page size
    document.getElementById('pageSize').addEventListener('change', (e) => {
      pageSize = Number(e.target.value) || 10;
      currentPage = 1; renderAll();
    });

    // sort header
    document.querySelectorAll('th.sortable').forEach(th=>{
      th.addEventListener('click', () => {
        const key = th.dataset.key;
        if (sortKey === key) sortDir = (sortDir === 'asc' ? 'desc' : 'asc');
        else { sortKey = key; sortDir = 'asc'; }
        renderAll();
      });
    });

    renderAll();
  });
</script>

{{-- Small style for header hover --}}
<style>
  th.sortable:hover { background-color: rgba(0,0,0,0.02); }
</style>

@endsection
