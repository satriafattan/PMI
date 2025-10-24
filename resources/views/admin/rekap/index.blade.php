@extends('layouts.admin')
@section('title', 'Rekapitulasi Stok Darah')

@section('content')
<div class="space-y-6">
  {{-- Title --}}
  <h1 class="text-2xl md:text-3xl font-semibold">Histori Darah Keluar &amp; Kadaluwarsa</h1>

  {{-- Toolbar --}}
  <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
    {{-- Search --}}
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

  {{-- Table (≥ md) --}}
  <div class="hidden md:block rounded-2xl border border-neutral-200 bg-white overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 text-neutral-600">
          <tr class="text-left">
            <th data-key="id"        class="sortable px-4 py-3 font-medium cursor-pointer select-none">ID Darah</th>
            <th data-key="gol"       class="sortable px-4 py-3 font-medium cursor-pointer select-none">Golongan Darah</th>
            <th data-key="rhesus"    class="sortable px-4 py-3 font-medium cursor-pointer select-none">Rhesus</th>
            <th data-key="produk"    class="sortable px-4 py-3 font-medium cursor-pointer select-none">Produk Darah</th>
            <th data-key="tglMasuk"  class="sortable px-4 py-3 font-medium cursor-pointer select-none">Tanggal Masuk</th>
            <th data-key="tglExp"    class="sortable px-4 py-3 font-medium cursor-pointer select-none">Tanggal Kadaluwarsa</th>
            <th data-key="penerima"  class="sortable px-4 py-3 font-medium cursor-pointer select-none">Penerima</th>
            <th data-key="status"    class="sortable px-4 py-3 font-medium cursor-pointer select-none">Status</th>
          </tr>
        </thead>
        <tbody id="tableBody"></tbody>
      </table>
    </div>
  </div>

  {{-- Cards (mobile) --}}
  <div id="cardsContainer" class="md:hidden space-y-3"></div>

  {{-- Pagination footer --}}
  <div class="flex flex-col sm:flex-row gap-3 sm:items-center justify-between">
    <div id="pageInfo" class="text-sm text-neutral-600"></div>
    <div id="pagination" class="flex items-center gap-2"></div>
  </div>
</div>

{{-- ===== Dummy data + sort + pagination (Vanilla JS) ===== --}}
<script>
  // Dummy data
  const rows = [
    {id:'BD001', gol:'A',  rhesus:'Rh+', produk:'PRC', tglMasuk:'12-02-2025', tglExp:'12-02-2025', penerima:'RS Umum Jakarta', status:'Approved'},
    {id:'BD002', gol:'A',  rhesus:'Rh+', produk:'WB',  tglMasuk:'08-02-2025', tglExp:'15-02-2025', penerima:'RS Umum Jakarta', status:'Pending'},
    {id:'BD003', gol:'B',  rhesus:'Rh-', produk:'TRC', tglMasuk:'05-02-2025', tglExp:'20-02-2025', penerima:'RS Pelita Sehat', status:'Approved'},
    {id:'BD004', gol:'O',  rhesus:'Rh+', produk:'FFP', tglMasuk:'03-02-2025', tglExp:'18-02-2025', penerima:'RS Sinar Abadi', status:'Approved'},
    {id:'BD005', gol:'AB', rhesus:'Rh+', produk:'PRC', tglMasuk:'09-02-2025', tglExp:'17-02-2025', penerima:'RS Umum Jakarta', status:'Pending'},
    {id:'BD006', gol:'A',  rhesus:'Rh-', produk:'TC',  tglMasuk:'04-02-2025', tglExp:'25-02-2025', penerima:'RS Persada', status:'Approved'},
    {id:'BD007', gol:'O',  rhesus:'Rh+', produk:'WB',  tglMasuk:'10-02-2025', tglExp:'20-02-2025', penerima:'RS Persada', status:'Approved'},
    {id:'BD008', gol:'B',  rhesus:'Rh+', produk:'FFP', tglMasuk:'06-02-2025', tglExp:'15-02-2025', penerima:'RS Maju Jaya', status:'Pending'},
    {id:'BD009', gol:'A',  rhesus:'Rh+', produk:'PRC', tglMasuk:'02-02-2025', tglExp:'12-02-2025', penerima:'RS Umum Jakarta', status:'Rejected'},
    {id:'BD010', gol:'AB', rhesus:'Rh-', produk:'PRC', tglMasuk:'11-02-2025', tglExp:'28-02-2025', penerima:'RS Kasih Ibu',   status:'Approved'},
    {id:'BD011', gol:'O',  rhesus:'Rh-', produk:'TRC', tglMasuk:'12-02-2025', tglExp:'27-02-2025', penerima:'RS Kasih Ibu',   status:'Pending'},
    {id:'BD012', gol:'B',  rhesus:'Rh+', produk:'FFP', tglMasuk:'07-02-2025', tglExp:'19-02-2025', penerima:'RS Kasih Karunia',status:'Approved'},
    {id:'BD013', gol:'A',  rhesus:'Rh+', produk:'WB',  tglMasuk:'01-02-2025', tglExp:'14-02-2025', penerima:'RS Harapan',     status:'Approved'},
    {id:'BD014', gol:'AB', rhesus:'Rh+', produk:'TRC', tglMasuk:'05-02-2025', tglExp:'26-02-2025', penerima:'RS Harapan',     status:'Pending'},
  ];

  // State
  let sortKey = '';
  let sortDir = 'asc';
  let currentPage = 1;
  let pageSize = 10;

  // Helpers
  function badgeClass(s){
    if (s === 'Approved') return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
    if (s === 'Pending')  return 'bg-amber-50 text-amber-700 border border-amber-200';
    return 'bg-rose-50 text-rose-700 border border-rose-200';
  }
  const bloodPill = g => `<span class="inline-flex items-center justify-center size-6 rounded-full bg-rose-50 text-rose-600 text-xs font-semibold border border-rose-100">${g}</span>`;
  const productPill = p => `<span class="inline-flex items-center rounded-full border border-sky-200 bg-sky-50 px-2.5 py-0.5 text-xs text-sky-700">${p}</span>`;
  const toIso = d => d.split('-').reverse().join('-'); // dd-mm-yyyy -> yyyy-mm-dd

  // Filter, sort, paginate
  function getFiltered(){
    const q = (document.getElementById('searchInput').value || '').toLowerCase().trim();
    const s = document.getElementById('statusSelect')?.value || '';
    return rows.filter(o=>{
      const text = (o.id+' '+o.penerima).toLowerCase();
      const matchQ = !q || text.includes(q);
      const matchS = !s || o.status === s;
      return matchQ && matchS;
    });
  }
  function getSorted(data){
    if (!sortKey) return data;
    const cp = [...data];
    cp.sort((a,b)=>{
      let va=a[sortKey], vb=b[sortKey];
      if (sortKey==='tglMasuk' || sortKey==='tglExp'){ va=toIso(va); vb=toIso(vb); }
      va = (typeof va==='string') ? va.toLowerCase() : va;
      vb = (typeof vb==='string') ? vb.toLowerCase() : vb;
      if (va < vb) return sortDir==='asc' ? -1 : 1;
      if (va > vb) return sortDir==='asc' ?  1 : -1;
      return 0;
    });
    return cp;
  }
  function getPaged(data){
    const total = data.length;
    const pages = Math.max(1, Math.ceil(total / pageSize));
    currentPage = Math.min(currentPage, pages);
    const start = (currentPage-1)*pageSize;
    return { slice: data.slice(start, start+pageSize), total, pages };
  }

  // Renderers
  function renderTable(data){
    const tb = document.getElementById('tableBody');
    if (data.length===0){ tb.innerHTML = `<tr><td colspan="8" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>`; return; }
    tb.innerHTML = data.map(o=>`
      <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
        <td class="px-4 py-3">${o.id}</td>
        <td class="px-4 py-3">${bloodPill(o.gol)}</td>
        <td class="px-4 py-3">${o.rhesus}</td>
        <td class="px-4 py-3">${productPill(o.produk)}</td>
        <td class="px-4 py-3">${o.tglMasuk}</td>
        <td class="px-4 py-3">${o.tglExp}</td>
        <td class="px-4 py-3">${o.penerima}</td>
        <td class="px-4 py-3"><span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ${badgeClass(o.status)}">${o.status}</span></td>
      </tr>
    `).join('');
  }
  function renderCards(data){
    const wrap = document.getElementById('cardsContainer');
    if (data.length===0){ wrap.innerHTML = `<div class="text-center text-neutral-500">Tidak ada data.</div>`; return; }
    wrap.innerHTML = data.map(o=>`
      <div class="rounded-2xl border border-neutral-200 bg-white p-4">
        <div class="flex items-start justify-between gap-3">
          <div>
            <p class="font-medium">${o.id}</p>
            <p class="text-xs text-neutral-500">${o.penerima}</p>
          </div>
          <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ${badgeClass(o.status)}">${o.status}</span>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
          <div class="text-neutral-500">Golongan</div><div>${bloodPill(o.gol)}</div>
          <div class="text-neutral-500">Rhesus</div><div>${o.rhesus}</div>
          <div class="text-neutral-500">Produk</div><div>${productPill(o.produk)}</div>
          <div class="text-neutral-500">Masuk</div><div>${o.tglMasuk}</div>
          <div class="text-neutral-500">Kadaluwarsa</div><div>${o.tglExp}</div>
        </div>
      </div>
    `).join('');
  }
  function getPageRange(totalPages, current, max=5){
    const pages=[]; const half=Math.floor(max/2);
    let start=Math.max(1,current-half), end=Math.min(totalPages,start+max-1);
    if (end-start+1<max) start=Math.max(1,end-max+1);
    if (start>1){ pages.push(1); if(start>2) pages.push('…'); }
    for(let i=start;i<=end;i++) pages.push(i);
    if (end<totalPages){ if(end<totalPages-1) pages.push('…'); pages.push(totalPages); }
    return pages;
  }
  function renderPagination(total, pages){
    const cont=document.getElementById('pagination');
    const info=document.getElementById('pageInfo');
    const start = total===0?0:(currentPage-1)*pageSize+1;
    const end   = Math.min(currentPage*pageSize,total);
    info.textContent = `Menampilkan ${start}-${end} dari ${total} data`;

    if (pages<=1){ cont.innerHTML=''; return; }
    const btn=(label, p, disabled=false, active=false)=>`
      <button class="min-w-9 h-9 px-3 rounded-lg border text-sm
                     ${active?'bg-neutral-900 text-white border-neutral-900':'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'}
                     ${disabled?'opacity-50 cursor-not-allowed':''}"
              ${disabled?'disabled':''} data-page="${p}">
        ${label}
      </button>`;
    let html = btn('«', currentPage-1, currentPage===1);
    getPageRange(pages,currentPage,5).forEach(p=>{
      html += (p==='…') ? `<span class="px-2 text-neutral-400">…</span>` : btn(p,p,false,p===currentPage);
    });
    html += btn('»', currentPage+1, currentPage===pages);
    cont.innerHTML = html;
    cont.querySelectorAll('button[data-page]').forEach(b=>{
      b.addEventListener('click', ()=>{ currentPage = Number(b.dataset.page); renderAll(); });
    });
  }
  function markSortHeaders(){
    document.querySelectorAll('th.sortable').forEach(th=>{
      th.querySelector('.sort-ind')?.remove();
      if (th.dataset.key===sortKey){
        const s=document.createElement('span');
        s.className='sort-ind inline-block ml-1 text-neutral-400';
        s.innerHTML = sortDir==='asc' ? '▲' : '▼';
        th.appendChild(s);
      }
    });
  }

  // Master render
  function renderAll(){
    const filtered = getFiltered();
    const sorted   = getSorted(filtered);
    const {slice, total, pages} = getPaged(sorted);
    renderTable(slice);
    renderCards(slice);
    renderPagination(total, pages);
    markSortHeaders();
  }

  // Events & init
  document.addEventListener('DOMContentLoaded', ()=>{
    // search live
    document.getElementById('searchInput').addEventListener('input', ()=>{ currentPage=1; renderAll(); });

    // filter dropdown
    const btn=document.getElementById('filterBtn');
    const menu=document.getElementById('filterMenu');
    const apply=document.getElementById('applyBtn');
    const reset=document.getElementById('resetBtn');
    const statusSelect=document.getElementById('statusSelect');

    btn.addEventListener('click',(e)=>{ e.stopPropagation(); menu.classList.toggle('hidden'); });
    document.addEventListener('click',(e)=>{ if(!menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden'); });
    apply.addEventListener('click',()=>{ menu.classList.add('hidden'); currentPage=1; renderAll(); });
    reset.addEventListener('click',()=>{ statusSelect.value=''; currentPage=1; renderAll(); });

    // page size
    document.getElementById('pageSize').addEventListener('change',(e)=>{ pageSize=Number(e.target.value)||10; currentPage=1; renderAll(); });

    // sort header
    document.querySelectorAll('th.sortable').forEach(th=>{
      th.addEventListener('click', ()=>{
        const key = th.dataset.key;
        if (sortKey===key) sortDir = (sortDir==='asc'?'desc':'asc');
        else { sortKey=key; sortDir='asc'; }
        currentPage=1; renderAll();
      });
    });

    renderAll();
  });
</script>

<style>
  th.sortable:hover { background-color: rgba(0,0,0,0.02); }
</style>
@endsection