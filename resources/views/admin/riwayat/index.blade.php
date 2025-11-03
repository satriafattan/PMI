{{-- resources/views/admin/riwayat/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="space-y-6">
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

  {{-- TABEL --}}
  <div class="hidden md:block rounded-2xl border border-neutral-200 bg-white overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 text-neutral-600">
          <tr class="text-left">
            <th data-key="nama"   class="sortable px-4 py-3 font-medium cursor-pointer select-none">Nama Lengkap</th>
            <th data-key="tgl"    class="sortable px-4 py-3 font-medium cursor-pointer select-none">Tanggal Pemesanan</th>
            <th class="px-4 py-3 font-medium">Golongan Darah</th>
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

{{-- ===== Modal: Detail (layout verifikasi, tanpa Terima/Tolak) ===== --}}
<div id="detailModal" class="fixed inset-0 z-40 hidden items-center justify-center bg-black/20 p-4">
  <div class="w-full max-w-4xl rounded-3xl border border-neutral-200 bg-white shadow-xl">
    <div class="flex items-center justify-between border-b border-neutral-100 px-6 py-4">
      <h3 class="text-xl font-semibold">Detail Pemesanan</h3>
      <button type="button" class="dm-close rounded-lg p-1 text-neutral-500 hover:bg-neutral-100">
        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M6 18 18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <div class="px-6 py-5">
      <div class="max-h-[70vh] overflow-auto pr-1 space-y-8">
        {{-- A. Pasien & RS --}}
        <section>
          <h4 class="mb-3 text-sm font-semibold tracking-wide text-neutral-700">A. Pasien & RS</h4>
          <dl class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm">
            <dt class="text-neutral-500">Rumah Sakit</dt>      <dd id="dm_rs" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Jenis Kelamin</dt>    <dd id="dm_jk" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">No. Registrasi</dt>   <dd id="dm_no_regis" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Nama Dokter</dt>      <dd id="dm_dokter" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Nama Pasien</dt>      <dd id="dm_nama" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Suami/Istri</dt>      <dd id="dm_suami_istri" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Telepon</dt>          <dd id="dm_telp" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Email</dt>            <dd id="dm_email" class="font-medium text-neutral-900 break-all">-</dd>
          </dl>
        </section>

        {{-- B. Detail Klinis --}}
        <section>
          <h4 class="mb-3 text-sm font-semibold tracking-wide text-neutral-700">B. Detail Klinis</h4>
          <dl class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm">
            <dt class="text-neutral-500">Tgl Diperlukan</dt>   <dd id="dm_tgl_minta" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Pernah Serologi</dt>  <dd id="dm_pernah_serologi" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Diagnosa</dt>         <dd id="dm_diagnosa" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Lokasi Serologi</dt>  <dd id="dm_lokasi_serologi" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Tgl Serologi</dt>     <dd id="dm_tgl_serologi" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Tgl Transfusi</dt>    <dd id="dm_tgl_transfusi" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Alasan Transfusi</dt> <dd id="dm_alasan" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Hasil Serologi</dt>   <dd id="dm_hasil_serologi" class="font-medium text-neutral-900">-</dd>
          </dl>
        </section>

        {{-- C. Permintaan Darah --}}
        <section>
          <h4 class="mb-3 text-sm font-semibold tracking-wide text-neutral-700">C. Permintaan Darah</h4>
          <dl class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm">
            <dt class="text-neutral-500">Jenis Darah</dt>      <dd id="dm_produk" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Golongan Darah</dt>   <dd id="dm_gol" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Rhesus</dt>           <dd id="dm_rhesus" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Jumlah Kantong</dt>   <dd id="dm_jumlah" class="font-medium text-neutral-900">-</dd>
            <dt class="text-neutral-500">Gejala Tambahan</dt>  <dd id="dm_gejala" class="font-medium text-neutral-900">—</dd>
            <dt class="text-neutral-500">Cek Transfusi</dt>    <dd id="dm_cek" class="font-medium text-neutral-900">-</dd>
          </dl>
        </section>

        {{-- Meta --}}
        <section>
          <dl class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm">
            <dt class="text-neutral-500">Status Saat Ini</dt>  <dd id="dm_status" class="font-semibold text-neutral-900">-</dd>
            <dt class="text-neutral-500">Tgl. Pemesanan</dt>   <dd id="dm_tgl_pesan" class="font-medium text-neutral-900">-</dd>
          </dl>
        </section>
      </div>
    </div>

    <div class="flex items-center justify-between gap-3 border-t border-neutral-100 px-6 py-4">
      <span class="text-xs text-neutral-500">ID: <span id="dm_id_disp">-</span></span>
      <button type="button"
              class="dm-close rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
        Tutup
      </button>
    </div>
  </div>
</div>

{{-- Form Hapus --}}
<form id="deleteForm" method="POST" action="" class="hidden">
  @csrf
  @method('DELETE')
</form>

<script>
  // ========== Data dari Controller ==========
  const rows = {!! $rowsJson ?? '[]' !!};

  // ========== State / UI helpers ==========
  let sortKey = '', sortDir = 'asc', currentPage = 1, pageSize = 10;

  function badgeClass(s){
    if (s === 'Approved') return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
    if (s === 'Pending')  return 'bg-amber-50 text-amber-700 border border-amber-200';
    return 'bg-rose-50 text-rose-700 border border-rose-200';
  }
  function bloodPill(g){
    return `<span class="inline-flex items-center justify-center size-6 rounded-full bg-rose-50 text-rose-600 text-xs font-semibold border border-rose-100">${g ?? '-'}</span>`;
  }
  function productLabel(code){
    const m = {WB:'WB: Whole Blood', PRC:'PRC: Packed Red Cell', TC:'TC: Thrombocyte Concentrate', FFP:'FFP: Fresh Frozen Plasma', AHF:'AHF: Anti Hemophilic Factor', LP:'LP: Leukocyte Poor', TCA:'TCA: Thrombocyte Apheresis', PK:'PK: Platelet Kriopresipitat'};
    return m[code] || code || '-';
  }
  function yaTidak(v){
    if (v === true) return 'Ya';
    if (v === false) return 'Tidak';
    const s = String(v ?? '').toLowerCase();
    if (s === 'ya') return 'Ya';
    if (s === 'tidak') return 'Tidak';
    return '-';
  }
  function labelJK(v){ return v==='L'?'Laki-laki':(v==='P'?'Perempuan':(v??'-')); }
  function jumlahLabel(v){ const n=Number(v||0); return n>0?`${n} kantong`:'-'; }

  // ========== Filter/Sort/Page ==========
  function getFiltered(){
    const q = (document.getElementById('searchInput').value||'').toLowerCase().trim();
    const s = document.getElementById('statusSelect')?.value || '';
    return rows.filter(o=>{
      const name = String(o.nama ?? '').toLowerCase();
      const matchQ = !q || name.includes(q);
      const matchS = !s || (o.status === s);
      return matchQ && matchS;
    });
  }
  function getSorted(data){
    if(!sortKey) return data;
    const cp = [...data];
    cp.sort((a,b)=>{
      let va=a[sortKey], vb=b[sortKey];
      if (sortKey==='tgl'){ // dd-mm-yyyy
        const ta=String(va||'').split('-').reverse().join('-');
        const tb=String(vb||'').split('-').reverse().join('-');
        return (sortDir==='asc' ? ta.localeCompare(tb) : tb.localeCompare(ta));
      }
      va = (typeof va==='number')?va:String(va??'').toLowerCase();
      vb = (typeof vb==='number')?vb:String(vb??'').toLowerCase();
      if (va<vb) return (sortDir==='asc')?-1:1;
      if (va>vb) return (sortDir==='asc')? 1:-1;
      return 0;
    });
    return cp;
  }
  function getPaged(data){
    const total=data.length, pages=Math.max(1,Math.ceil(total/pageSize));
    currentPage = Math.min(currentPage,pages);
    const start=(currentPage-1)*pageSize, end=start+pageSize;
    return { slice:data.slice(start,end), total, pages };
  }

  // ========== Renderers ==========
  function renderTable(data){
    const tbody=document.getElementById('tableBody');
    if (data.length===0){
      tbody.innerHTML = `<tr><td colspan="8" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>`;
      return;
    }
    tbody.innerHTML = data.map(o=>`
      <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
        <td class="px-4 py-3">${o.nama ?? '-'}</td>
        <td class="px-4 py-3">${o.tgl ?? '-'}</td>
        <td class="px-4 py-3">${bloodPill(o.gol)}</td>
        <td class="px-4 py-3">${o.rhesus ?? '-'}</td>
        <td class="px-4 py-3"><span class="text-blue-600">${o.produk ?? '-'}</span></td>
        <td class="px-4 py-3">${o.kantong ?? 0}</td>
        <td class="px-4 py-3">
          <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ${badgeClass(o.status ?? '')}">
            ${o.status ?? '-'}
          </span>
        </td>
        <td class="px-4 py-3">
          <div class="flex flex-wrap items-center gap-2">
            <button class="btn-detail inline-flex items-center gap-2 rounded-lg border border-neutral-200 bg-white px-3 py-1.5 text-xs font-medium hover:bg-neutral-50"
                    title="Lihat Detail"
                    data-payload='${JSON.stringify(o.payload || {})}'>
              <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12zm10-3.5A3.5 3.5 0 1 1 8.5 12 3.5 3.5 0 0 1 12 8.5z"/></svg>
              Lihat Detail
            </button>
            <button class="btn-delete inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-100"
                    title="Hapus" data-id="${o.id}">
              <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M9 7V5h6v2m-8 0 1 12h8l1-12"/></svg>
              Hapus
            </button>
          </div>
        </td>
      </tr>
    `).join('');

    tbody.querySelectorAll('.btn-detail').forEach(b=>b.addEventListener('click', onDetailClick));
    tbody.querySelectorAll('.btn-delete').forEach(b=>b.addEventListener('click', onDeleteClick));
  }

  function renderCards(data){
    const wrap=document.getElementById('cardsContainer');
    if (data.length===0){
      wrap.innerHTML = `<div class="text-center text-neutral-500">Tidak ada data.</div>`;
      return;
    }
    wrap.innerHTML = data.map(o=>`
      <div class="rounded-2xl border border-neutral-200 bg-white p-4">
        <div class="flex items-start justify-between">
          <p class="font-medium">${o.nama ?? '-'}</p>
          <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ${badgeClass(o.status ?? '')}">
            ${o.status ?? '-'}
          </span>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
          <div class="text-neutral-500">Tanggal</div><div>${o.tgl ?? '-'}</div>
          <div class="text-neutral-500">Golongan</div><div>${bloodPill(o.gol)}</div>
          <div class="text-neutral-500">Rhesus</div><div>${o.rhesus ?? '-'}</div>
          <div class="text-neutral-500">Produk</div><div>${o.produk ?? '-'}</div>
          <div class="text-neutral-500">Kantong</div><div>${o.kantong ?? 0}</div>
        </div>
        <div class="mt-3 flex flex-wrap items-center gap-2">
          <button class="btn-detail inline-flex items-center gap-2 rounded-lg border border-neutral-200 bg-white px-3 py-1.5 text-xs font-medium hover:bg-neutral-50"
                  title="Lihat Detail"
                  data-payload='${JSON.stringify(o.payload || {})}'>
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12zm10-3.5A3.5 3.5 0 1 1 8.5 12 3.5 3.5 0 0 1 12 8.5z"/></svg>
            Lihat Detail
          </button>
          <button class="btn-delete inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-100"
                  title="Hapus" data-id="${o.id}">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M9 7V5h6v2m-8 0 1 12h8l1-12"/></svg>
            Hapus
          </button>
        </div>
      </div>
    `).join('');

    wrap.querySelectorAll('.btn-detail').forEach(b=>b.addEventListener('click', onDetailClick));
    wrap.querySelectorAll('.btn-delete').forEach(b=>b.addEventListener('click', onDeleteClick));
  }

  function renderPagination(total,pages){
    const cont=document.getElementById('pagination');
    const info=document.getElementById('pageInfo');
    const start= total===0?0:(currentPage-1)*pageSize+1;
    const end=Math.min(currentPage*pageSize,total);
    info.textContent = `Menampilkan ${start}-${end} dari ${total} data`;
    if (pages<=1){ cont.innerHTML=''; return; }

    const btn=(lab,page,disabled=false,active=false)=>`
      <button class="min-w-9 h-9 px-3 rounded-lg border text-sm ${active?'bg-neutral-900 text-white border-neutral-900':'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'} ${disabled?'opacity-50 cursor-not-allowed':''}"
              ${disabled?'disabled':''} data-page="${page}">${lab}</button>`;
    let html = btn('«', currentPage-1, currentPage===1);
    const range = getPageRange(pages,currentPage,5);
    range.forEach(p=> html += (p==='…') ? `<span class="px-2 text-neutral-400">…</span>` : btn(p,p,false,p===currentPage));
    html += btn('»', currentPage+1, currentPage===pages);
    cont.innerHTML = html;
    cont.querySelectorAll('button[data-page]').forEach(b=>b.addEventListener('click', ()=>{ currentPage=+b.dataset.page; renderAll(); }));
  }
  function getPageRange(total,current,max=5){
    const out=[]; const half=Math.floor(max/2);
    let start=Math.max(1,current-half), end=Math.min(total,start+max-1);
    if (end-start+1<max) start=Math.max(1,end-max+1);
    if (start>1){ out.push(1); if (start>2) out.push('…'); }
    for(let i=start;i<=end;i++) out.push(i);
    if (end<total){ if (end<total-1) out.push('…'); out.push(total); }
    return out;
  }
  function markSortHeaders(){
    document.querySelectorAll('th.sortable').forEach(th=>{
      th.querySelector('.sort-ind')?.remove();
      if (th.dataset.key===sortKey){
        const span=document.createElement('span');
        span.className='sort-ind inline-block ml-1 text-neutral-400';
        span.innerHTML = (sortDir==='asc')?'▲':'▼';
        th.appendChild(span);
      }
    });
  }

  // ========== Modal handling ==========
  const detailModal=document.getElementById('detailModal');
  const dmCloseBtns=detailModal.querySelectorAll('.dm-close');
  const dm = {
    idDisp:document.getElementById('dm_id_disp'),
    status:document.getElementById('dm_status'),
    tglPesan:document.getElementById('dm_tgl_pesan'),
    tglMinta:document.getElementById('dm_tgl_minta'),

    nama:document.getElementById('dm_nama'),
    rs:document.getElementById('dm_rs'),
    jk:document.getElementById('dm_jk'),
    dokter:document.getElementById('dm_dokter'),
    noRegis:document.getElementById('dm_no_regis'),
    email:document.getElementById('dm_email'),
    telp:document.getElementById('dm_telp'),

    gol:document.getElementById('dm_gol'),
    rhesus:document.getElementById('dm_rhesus'),
    produk:document.getElementById('dm_produk'),
    jumlah:document.getElementById('dm_jumlah'),

    alasan:document.getElementById('dm_alasan'),
    gejala:document.getElementById('dm_gejala'),
    cek:document.getElementById('dm_cek'),

    suamiIstri:document.getElementById('dm_suami_istri'),
    diagnosa:document.getElementById('dm_diagnosa'),
    pernahSerologi:document.getElementById('dm_pernah_serologi'),
    lokasiSerologi:document.getElementById('dm_lokasi_serologi'),
    tglSerologi:document.getElementById('dm_tgl_serologi'),
    tglTransfusi:document.getElementById('dm_tgl_transfusi'),
    hasilSerologi:document.getElementById('dm_hasil_serologi'),
  };

  function fmt(v){ return v ? v : '-'; }
  function openDetail(payload){
    try{
      dm.idDisp.textContent = payload.id ?? '-';
      dm.status.textContent = (payload.status ?? '-').toString().replace(/^./,c=>c.toUpperCase());
      dm.tglPesan.textContent = fmt(payload.tanggal_pemesanan ?? payload.tanggal);
      dm.tglMinta.textContent = fmt(payload.tanggal_permintaan ?? payload.tanggal);

      dm.nama.textContent = payload.nama_pasien ?? '-';
      dm.rs.textContent = payload.rs_pemesan ?? '-';
      dm.jk.textContent = labelJK(payload.jenis_kelamin);
      dm.dokter.textContent = payload.nama_dokter ?? '-';
      dm.noRegis.textContent = payload.no_regis_rs ?? '-';
      dm.email.textContent = payload.email ?? '-';
      dm.telp.textContent = payload.nomor_telepon ?? '-';
      dm.suamiIstri.textContent = payload.nama_suami_istri ?? '-';

      dm.gol.textContent = payload.gol_darah ?? '-';
      dm.rhesus.textContent = payload.rhesus ?? '-';
      dm.produk.textContent = productLabel(payload.produk);
      dm.jumlah.textContent = jumlahLabel(payload.jumlah_kantong);

      dm.alasan.textContent = payload.alasan_transfusi ?? '-';
      dm.gejala.textContent = payload.gejala_transfusi ?? '-';
      dm.cek.textContent = yaTidak(payload.cek_transfusi);

      dm.diagnosa.textContent = payload.diagnosa_klinik ?? '-';
      dm.pernahSerologi.textContent = yaTidak(payload.pernah_serologi);
      dm.lokasiSerologi.textContent = payload.lokasi_serologi ?? '-';
      dm.tglSerologi.textContent = fmt(payload.tanggal_serologi);
      dm.tglTransfusi.textContent = fmt(payload.tanggal_transfusi);
      dm.hasilSerologi.textContent = payload.hasil_serologi ?? '-';

      detailModal.classList.remove('hidden');
      detailModal.classList.add('flex');
    }catch(e){
      console.error('Gagal buka detail:', e);
      alert('Terjadi kesalahan saat membuka detail.');
    }
  }
  function closeDetail(){
    detailModal.classList.add('hidden');
    detailModal.classList.remove('flex');
  }
  dmCloseBtns.forEach(b=>b.addEventListener('click', closeDetail));
  detailModal.addEventListener('click', (e)=>{ if(e.target===detailModal) closeDetail(); });

  function onDetailClick(e){
    const btn = e.currentTarget;
    const payload = JSON.parse(btn.dataset.payload || '{}');
    openDetail(payload);
  }
  function onDeleteClick(e){
    const id = e.currentTarget.dataset.id;
    const form = document.getElementById('deleteForm');
    if (confirm('Hapus entri riwayat ini? Tindakan tidak dapat dibatalkan.')){
      form.action = `{{ url('admin/riwayat') }}/${encodeURIComponent(id)}`;
      form.submit();
    }
  }

  // ========== Master render ==========
  function renderAll(){
    const filtered=getFiltered();
    const sorted=getSorted(filtered);
    const {slice,total,pages}=getPaged(sorted);
    renderTable(slice);
    renderCards(slice);
    renderPagination(total,pages);
    markSortHeaders();
  }

  // ========== Init ==========
  document.addEventListener('DOMContentLoaded', ()=>{
    document.getElementById('searchInput').addEventListener('input', ()=>{ currentPage=1; renderAll(); });

    // filter menu
    const btn = document.getElementById('filterBtn');
    const menu = document.getElementById('filterMenu');
    const apply = document.getElementById('applyBtn');
    const reset = document.getElementById('resetBtn');
    const statusSelect = document.getElementById('statusSelect');

    btn.addEventListener('click', (e)=>{ e.stopPropagation(); menu.classList.toggle('hidden'); });
    document.addEventListener('click', (e)=>{ if(!menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden'); });
    apply.addEventListener('click', ()=>{ menu.classList.add('hidden'); currentPage=1; renderAll(); });
    reset.addEventListener('click', ()=>{ statusSelect.value=''; currentPage=1; renderAll(); });

    document.getElementById('pageSize').addEventListener('change', (e)=>{
      pageSize = Number(e.target.value)||10; currentPage=1; renderAll();
    });

    document.querySelectorAll('th.sortable').forEach(th=>{
      th.addEventListener('click', ()=>{
        const key=th.dataset.key;
        if (sortKey===key) sortDir = (sortDir==='asc'?'desc':'asc');
        else { sortKey=key; sortDir='asc'; }
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
