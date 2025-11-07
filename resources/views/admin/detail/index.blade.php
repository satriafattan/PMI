{{-- resources/views/admin/detail/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Informasi Detail Darah')

@section('content')
  @php
    // Pastikan variabel dari controller tersedia
    // $rows: koleksi/array unit darah tersedia
    // $historyRows: koleksi/array riwayat (keluar/dll)

    $rows = $rows ?? collect();
    $historyRows = $historyRows ?? collect();

    // Kumpulkan opsi komponen dari data nyata; fallback jika kosong
    $kompOpts = collect($rows)->pluck('komponen')->filter()->unique()->values()->all();
    if (empty($kompOpts)) {
        $kompOpts = ['PRC', 'WB', 'TRC', 'FFP', 'TC', 'AHF', 'LP', 'TCA', 'PK'];
    }
  @endphp

  <div class="space-y-4">
    <div class="space-y-1">
      <h1 class="text-2xl font-semibold md:text-3xl">Informasi Detail Darah</h1>
      <p class="text-sm text-neutral-500">Data stok unit darah yang tersedia, keluar, dan kadaluwarsa</p>
    </div>

    <div class="inline-flex rounded-2xl border border-neutral-200 bg-white p-1">
      <button id="btnAvail"
              type="button"
              class="tabbtn is-active">Tersedia</button>
      <button id="btnUnavail"
              type="button"
              class="tabbtn">Keluar</button>
      <button id="btnExpired"
              type="button"
              class="tabbtn">Kadaluwarsa</button>
    </div>
  </div>

  {{-- ========================= --}}
  {{-- SECTION: TABEL 1 (Tersedia) --}}
  {{-- ========================= --}}
  <section id="secAvail"
           class="mt-6 space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div class="flex w-full items-center gap-2 sm:flex-1">
        <div class="relative flex-1">
          <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
            <svg class="size-5 text-neutral-400"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.6"
                    d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" />
            </svg>
          </span>
          <input id="searchInput"
                 type="text"
                 class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-900/10"
                 placeholder="Cari ID darah atau komponen…">
        </div>

        <div class="relative">
          <button id="filterBtn"
                  type="button"
                  class="inline-flex items-center justify-center rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50">
            <svg class="size-5 text-neutral-600"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.6"
                    d="M3 6h18M6 12h12M10 18h4" />
            </svg>
          </button>
          <div id="filterMenu"
               class="absolute right-0 z-20 mt-2 hidden w-[22rem] rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
            <div class="grid gap-3 sm:grid-cols-2">
              <div>
                <label class="text-xs font-medium text-neutral-500">Golongan</label>
                <select id="golSelect"
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                  <option value="">Semua</option>
                  @foreach (['A', 'B', 'AB', 'O'] as $g)
                    <option value="{{ $g }}">{{ $g }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="text-xs font-medium text-neutral-500">Rhesus</label>
                <select id="rhesusSelect"
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                  <option value="">Semua</option>
                  <option value="Rh+">Rh+</option>
                  <option value="Rh-">Rh-</option>
                </select>
              </div>
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
              <div>
                <label class="text-xs font-medium text-neutral-500">Tgl Masuk (dari)</label>
                <input type="date"
                       id="masukFrom"
                       class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              </div>
              <div>
                <label class="text-xs font-medium text-neutral-500">Tgl Masuk (hingga)</label>
                <input type="date"
                       id="masukTo"
                       class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              </div>
              <div>
                <label class="text-xs font-medium text-neutral-500">Kadaluarsa (dari)</label>
                <input type="date"
                       id="expFrom"
                       class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              </div>
              <div>
                <label class="text-xs font-medium text-neutral-500">Kadaluarsa (hingga)</label>
                <input type="date"
                       id="expTo"
                       class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
              </div>
              <div class="flex items-center justify-between sm:col-span-2">
                <button type="button"
                        id="resetBtn"
                        class="text-sm text-neutral-600 hover:underline">Reset</button>
                <button type="button"
                        id="applyBtn"
                        class="rounded-lg bg-neutral-900 px-3 py-1.5 text-sm text-white hover:bg-neutral-800">Terapkan</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <label for="pageSize"
               class="text-sm text-neutral-600">Baris:</label>
        <select id="pageSize"
                class="rounded-xl border border-neutral-200 bg-white px-2 py-2 text-sm">
          <option>5</option>
          <option selected>10</option>
          <option>20</option>
        </select>
      </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-neutral-50 text-neutral-600">
            <tr class="text-left">
              <th data-key="id_darah"
                  class="sortable cursor-pointer select-none px-4 py-3 font-medium">ID Darah</th>
              <th data-key="gol_darah"
                  class="sortable cursor-pointer select-none px-4 py-3 font-medium">Golongan Darah</th>
              <th data-key="rhesus"
                  class="sortable cursor-pointer select-none px-4 py-3 font-medium">Rhesus</th>
              <th data-key="komponen"
                  class="sortable cursor-pointer select-none px-4 py-3 font-medium">Produk Darah</th>
              <th data-key="tgl_masuk"
                  class="sortable cursor-pointer select-none px-4 py-3 font-medium">Tanggal Masuk</th>
              <th data-key="tgl_kadaluarsa"
                  class="sortable cursor-pointer select-none px-4 py-3 font-medium">Tanggal Kadaluwarsa</th>
            </tr>
          </thead>
          <tbody id="tableBody"></tbody>
        </table>
      </div>
    </div>

    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
      <div id="pageInfo"
           class="text-sm text-neutral-600"></div>
      <div id="pagination"
           class="flex items-center gap-2"></div>
    </div>
  </section>

  {{-- ========================= --}}
  {{-- SECTION: TABEL 2 (Keluar / Tidak Tersedia) --}}
  {{-- ========================= --}}
  <section id="secUnavail"
           class="mt-6 hidden space-y-6">
    {{-- (toolbar & tabel sama seperti sebelumnya) --}}
    {{-- ... potongan toolbar Tabel 2 tidak diubah ... --}}

    <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-neutral-50 text-neutral-600">
            <tr class="text-left">
              <th data-hk="id"
                  class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">ID Darah</th>
              <th data-hk="gol"
                  class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">Golongan Darah</th>
              <th data-hk="rh"
                  class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">Rhesus</th>
              <th data-hk="produk"
                  class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">Produk Darah</th>
              <th data-hk="masuk"
                  class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">Tanggal Masuk</th>
              <th data-hk="exp"
                  class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">Tanggal Kadaluwarsa</th>
              <th data-hk="penerima"
                  class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">Penerima</th>
              <th data-hk="status"
                  class="hk-sortable cursor-pointer select-none px-4 py-3 font-medium">Status</th>
            </tr>
          </thead>
          <tbody id="hkTableBody"></tbody>
        </table>
      </div>
    </div>

    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
      <div id="hkPageInfo"
           class="text-sm text-neutral-600"></div>
      <div id="hkPagination"
           class="flex items-center gap-2"></div>
    </div>
  </section>

  <!-- SECTION: TABEL 3 (Kadaluwarsa) -->
  <section id="secExpired"
           class="mt-6 hidden space-y-6">
    <!-- (toolbar) -->
    <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-neutral-50 text-neutral-600">
            <tr class="text-left">
              <th data-ex="id"
                  class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">ID Darah</th>
              <th data-ex="gol"
                  class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">Golongan Darah</th>
              <th data-ex="rh"
                  class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">Rhesus</th>
              <th data-ex="produk"
                  class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">Produk Darah</th>
              <th data-ex="masuk"
                  class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">Tanggal Masuk</th>
              <th data-ex="exp"
                  class="ex-sortable cursor-pointer select-none px-4 py-3 font-medium">Tanggal Kadaluwarsa</th>
            </tr>
          </thead>
          <tbody id="exTableBody"></tbody>
        </table>
      </div>
    </div>

    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
      <div id="exPageInfo"
           class="text-sm text-neutral-600"></div>
      <div id="exPagination"
           class="flex items-center gap-2"></div>
    </div>
  </section>

  <script>
    /* ===== Utilities badge ===== */
    const dotGol = g =>
      `<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-rose-50 text-rose-500 text-xs font-semibold">${g}</span>`;
    const badgeProduk = p =>
      `<span class="inline-block rounded-full border border-sky-200 bg-sky-50 px-2 py-0.5 text-xs text-sky-700">${p}</span>`;

    function badgeStatus(s) {
      const map = {
        Approved: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        Pending: 'bg-amber-50 text-amber-700 border-amber-200',
        Rejected: 'bg-rose-50 text-rose-700 border-rose-200'
      };
      return `<span class="inline-block rounded-full px-3 py-0.5 text-xs border ${map[s]||'bg-neutral-50 text-neutral-600 border-neutral-200'}">${s}</span>`;
    }

    // Fungsi untuk format tanggal dari ISO ke DD-MM-YYYY
    function formatDate(dateString) {
      if (!dateString) return '-';
      const date = new Date(dateString);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = date.getFullYear();
      return `${day}-${month}-${year}`;
    }

    /* ===== Toggle 3 section ===== */
    const secAvail = document.getElementById('secAvail');
    const secUnavail = document.getElementById('secUnavail');
    const secExpired = document.getElementById('secExpired');
    const btnAvail = document.getElementById('btnAvail');
    const btnUnavail = document.getElementById('btnUnavail');
    const btnExpired = document.getElementById('btnExpired');

    function setTab(active) {
      if (secAvail) secAvail.classList.toggle('hidden', active !== 'avail');
      if (secUnavail) secUnavail.classList.toggle('hidden', active !== 'unavail');
      if (secExpired) secExpired.classList.toggle('hidden', active !== 'expired');
      [btnAvail, btnUnavail, btnExpired].forEach(b => b?.classList.remove('is-active'));
      (active === 'avail' ? btnAvail : active === 'unavail' ? btnUnavail : btnExpired)?.classList.add('is-active');
      ['filterMenu', 'hkFilterMenu', 'exFilterMenu'].forEach(id => document.getElementById(id)?.classList.add('hidden'));
    }
    btnAvail?.addEventListener('click', () => setTab('avail'));
    btnUnavail?.addEventListener('click', () => setTab('unavail'));
    btnExpired?.addEventListener('click', () => setTab('expired'));

    /* ====== DATA dari Controller (fallback ke array kosong) ====== */
    const rows = Array.isArray(@json($rows ?? [])) ? @json($rows ?? []) : [];
    const hkRows = Array.isArray(@json($historyRows ?? [])) ? @json($historyRows ?? []) : [];

    /* ====== TABEL 1 (Tersedia) ====== */
    let sortKey = 'id_darah',
      sortDir = 'asc',
      currentPage = 1,
      pageSize = 10;
    const toYmd = s => String(s || '');
    const inRange = (d, f, t) => {
      if (!d) return true;
      const dd = toYmd(d);
      if (f && dd < toYmd(f)) return false;
      if (t && dd > toYmd(t)) return false;
      return true;
    };

    function getFiltered() {
      const q = (document.getElementById('searchInput')?.value || '').toLowerCase().trim();
      const gol = document.getElementById('golSelect')?.value || '';
      const rh = document.getElementById('rhesusSelect')?.value || '';
      const kp = document.getElementById('kompSelect')?.value || '';
      const mf = document.getElementById('masukFrom')?.value || '';
      const mt = document.getElementById('masukTo')?.value || '';
      const ef = document.getElementById('expFrom')?.value || '';
      const et = document.getElementById('expTo')?.value || '';
      return rows.filter(o => {
        const hitQ = !q || String(o.id_darah).toLowerCase().includes(q) || String(o.komponen).toLowerCase().includes(
          q);
        const hitG = !gol || o.gol_darah === gol;
        const hitR = !rh || o.rhesus === rh;
        const hitK = !kp || o.komponen === kp;
        const hitM = inRange(o.tgl_masuk, mf, mt);
        const hitE = inRange(o.tgl_kadaluarsa, ef, et);
        return hitQ && hitG && hitR && hitK && hitM && hitE;
      });
    }

    function getSorted(data) {
      const cp = [...data];
      cp.sort((a, b) => {
        let va = a[sortKey],
          vb = b[sortKey];
        va = String(va ?? '').toLowerCase();
        vb = String(vb ?? '').toLowerCase();
        if (va < vb) return sortDir === 'asc' ? -1 : 1;
        if (va > vb) return sortDir === 'asc' ? 1 : -1;
        return 0;
      });
      return cp;
    }

    function getPaged(data) {
      const total = data.length,
        pages = Math.max(1, Math.ceil(total / pageSize));
      currentPage = Math.min(currentPage, pages);
      const start = (currentPage - 1) * pageSize;
      return {
        slice: data.slice(start, start + pageSize),
        total,
        pages
      };
    }

    function renderTable(data) {
      const tb = document.getElementById('tableBody');
      if (!tb) return;
      if (!data.length) {
        tb.innerHTML = `<tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>`;
        return;
      }
      tb.innerHTML = data.map(o => `
    <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
      <td class="px-4 py-3 font-medium text-neutral-800">${o.id_darah}</td>
      <td class="px-4 py-3">${dotGol(o.gol_darah)}</td>
      <td class="px-4 py-3">${o.rhesus}</td>
      <td class="px-4 py-3">${badgeProduk(o.komponen)}</td>
      <td class="px-4 py-3">${formatDate(o.tgl_masuk)}</td>
      <td class="px-4 py-3">${formatDate(o.tgl_kadaluarsa)}</td>
    </tr>
  `).join('');
    }

    function getPageRange(totalPages, current, max = 5) {
      const pages = [];
      const half = Math.floor(max / 2);
      let start = Math.max(1, current - half),
        end = Math.min(totalPages, start + max - 1);
      if (end - start + 1 < max) start = Math.max(1, end - max + 1);
      if (start > 1) {
        pages.push(1);
        if (start > 2) pages.push('…');
      }
      for (let i = start; i <= end; i++) pages.push(i);
      if (end < totalPages) {
        if (end < totalPages - 1) pages.push('…');
        pages.push(totalPages);
      }
      return pages;
    }

    function renderPagination(total, pages) {
      const cont = document.getElementById('pagination');
      const info = document.getElementById('pageInfo');
      if (!cont || !info) return;
      const start = total === 0 ? 0 : (currentPage - 1) * pageSize + 1;
      const end = Math.min(currentPage * pageSize, total);
      info.textContent = `Menampilkan ${start}-${end} dari ${total} data`;
      if (pages <= 1) {
        cont.innerHTML = '';
        return;
      }
      const btn = (label, page, disabled = false, active = false) => `
    <button class="min-w-9 h-9 px-3 rounded-lg border text-sm
                   ${active?'bg-neutral-900 text-white border-neutral-900':'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'}
                   ${disabled?'opacity-50 cursor-not-allowed':''}"
            ${disabled?'disabled':''} data-page="${page}">${label}</button>`;
      let html = '';
      html += btn('«', currentPage - 1, currentPage === 1);
      getPageRange(pages, currentPage, 5).forEach(p => {
        if (p === '…') html += `<span class="px-2 text-neutral-400">…</span>`;
        else html += btn(p, p, false, p === currentPage);
      });
      html += btn('»', currentPage + 1, currentPage === pages);
      cont.innerHTML = html;
      cont.querySelectorAll('button[data-page]').forEach(b => {
        b.addEventListener('click', () => {
          const p = Number(b.dataset.page);
          if (!Number.isNaN(p)) {
            currentPage = p;
            renderAll();
          }
        });
      });
    }

    function markSortHeaders() {
      document.querySelectorAll('th.sortable').forEach(th => {
        th.querySelector('.sort-ind')?.remove();
        if (th.dataset.key === sortKey) {
          const s = document.createElement('span');
          s.className = 'sort-ind inline-block ml-1 text-neutral-400';
          s.innerHTML = sortDir === 'asc' ? '▲' : '▼';
          th.appendChild(s);
        }
      });
    }

    function renderAll() {
      const filtered = getFiltered();
      const sorted = getSorted(filtered);
      const {
        slice,
        total,
        pages
      } = getPaged(sorted);
      renderTable(slice);
      renderPagination(total, pages);
      markSortHeaders();
    }

    /* ====== TABEL 2 (Keluar / Riwayat) ====== */
    let hkSortKey = 'id',
      hkSortDir = 'asc',
      hkCurrentPage = 1,
      hkPageSize = 10;
    const hkToYmd = s => String(s || '');
    const hkInRange = (d, f, t) => {
      if (!d) return true;
      const dd = hkToYmd(d);
      if (f && dd < hkToYmd(f)) return false;
      if (t && dd > hkToYmd(t)) return false;
      return true;
    };

    function hkGetFiltered() {
      const q = (document.getElementById('hkSearchInput')?.value || '').toLowerCase().trim();
      const g = document.getElementById('hkGolSelect')?.value || '';
      const rh = document.getElementById('hkRhesusSelect')?.value || '';
      const pr = document.getElementById('hkProdukSelect')?.value || '';
      const st = document.getElementById('hkStatusSelect')?.value || '';
      const mf = document.getElementById('hkMasukFrom')?.value || '';
      const mt = document.getElementById('hkMasukTo')?.value || '';
      return hkRows.filter(o => {
        const hitQ = !q || String(o.id).toLowerCase().includes(q) || String(o.penerima || '').toLowerCase().includes(
          q);
        const hitG = !g || o.gol === g,
          hitR = !rh || o.rh === rh,
          hitP = !pr || o.produk === pr,
          hitS = !st || o.status === st;
        const hitM = hkInRange(o.masuk, mf, mt);
        return hitQ && hitG && hitR && hitP && hitS && hitM;
      });
    }

    function hkGetSorted(data) {
      const cp = [...data];
      cp.sort((a, b) => {
        let va = a[hkSortKey],
          vb = b[hkSortKey];
        va = String(va ?? '').toLowerCase();
        vb = String(vb ?? '').toLowerCase();
        if (va < vb) return hkSortDir === 'asc' ? -1 : 1;
        if (va > vb) return hkSortDir === 'asc' ? 1 : -1;
        return 0;
      });
      return cp;
    }

    function hkGetPaged(data) {
      const total = data.length,
        pages = Math.max(1, Math.ceil(total / hkPageSize));
      hkCurrentPage = Math.min(hkCurrentPage, pages);
      const start = (hkCurrentPage - 1) * hkPageSize;
      return {
        slice: data.slice(start, start + hkPageSize),
        total,
        pages
      };
    }

    function hkRenderTable(data) {
      const tb = document.getElementById('hkTableBody');
      if (!tb) return;
      if (!data.length) {
        tb.innerHTML = `<tr><td colspan="8" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>`;
        return;
      }
      tb.innerHTML = data.map(o => `
    <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
      <td class="px-4 py-3 font-medium text-neutral-800">${o.id}</td>
      <td class="px-4 py-3">${dotGol(o.gol)}</td>
      <td class="px-4 py-3">${o.rh}</td>
      <td class="px-4 py-3">${badgeProduk(o.produk)}</td>
      <td class="px-4 py-3">${formatDate(o.masuk)}</td>
      <td class="px-4 py-3">${formatDate(o.exp)}</td>
      <td class="px-4 py-3">${o.penerima??''}</td>
      <td class="px-4 py-3">${badgeStatus(o.status||'-')}</td>
    </tr>
  `).join('');
    }

    function hkRange(totalPages, current, max = 5) {
      const pages = [];
      const half = Math.floor(max / 2);
      let start = Math.max(1, current - half),
        end = Math.min(totalPages, start + max - 1);
      if (end - start + 1 < max) start = Math.max(1, end - max + 1);
      if (start > 1) {
        pages.push(1);
        if (start > 2) pages.push('…');
      }
      for (let i = start; i <= end; i++) pages.push(i);
      if (end < totalPages) {
        if (end < totalPages - 1) pages.push('…');
        pages.push(totalPages);
      }
      return pages;
    }

    function hkRenderPagination(total, pages) {
      const cont = document.getElementById('hkPagination');
      const info = document.getElementById('hkPageInfo');
      if (!cont || !info) return;
      const start = total === 0 ? 0 : (hkCurrentPage - 1) * hkPageSize + 1;
      const end = Math.min(hkCurrentPage * hkPageSize, total);
      info.textContent = `Menampilkan ${start}-${end} dari ${total} data`;
      if (pages <= 1) {
        cont.innerHTML = '';
        return;
      }
      const btn = (label, page, disabled = false, active = false) => `
    <button class="min-w-9 h-9 px-3 rounded-lg border text-sm
                   ${active?'bg-neutral-900 text-white border-neutral-900':'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'}
                   ${disabled?'opacity-50 cursor-not-allowed':''}"
            ${disabled?'disabled':''} data-hkpage="${page}">${label}</button>`;
      let html = '';
      html += btn('«', hkCurrentPage - 1, hkCurrentPage === 1);
      hkRange(pages, hkCurrentPage, 5).forEach(p => {
        if (p === '…') html += `<span class="px-2 text-neutral-400">…</span>`;
        else html += btn(p, p, false, p === hkCurrentPage);
      });
      html += btn('»', hkCurrentPage + 1, hkCurrentPage === pages);
      cont.innerHTML = html;
      cont.querySelectorAll('button[data-hkpage]').forEach(b => {
        b.addEventListener('click', () => {
          const p = Number(b.dataset.hkpage);
          if (!Number.isNaN(p)) {
            hkCurrentPage = p;
            hkRenderAll();
          }
        });
      });
    }

    function hkMarkSortHeaders() {
      document.querySelectorAll('th.hk-sortable').forEach(th => {
        th.querySelector('.hk-ind')?.remove();
        if (th.dataset.hk === hkSortKey) {
          const s = document.createElement('span');
          s.className = 'hk-ind inline-block ml-1 text-neutral-400';
          s.innerHTML = hkSortDir === 'asc' ? '▲' : '▼';
          th.appendChild(s);
        }
      });
    }

    function hkRenderAll() {
      const filtered = hkGetFiltered();
      const sorted = hkGetSorted(filtered);
      const {
        slice,
        total,
        pages
      } = hkGetPaged(sorted);
      hkRenderTable(slice);
      hkRenderPagination(total, pages);
      hkMarkSortHeaders();
    }

    /* ====== TABEL 3 (Kadaluwarsa) ====== */
    // Ambil dari hkRows lalu filter yang exp < hari ini
    const todayYmd = new Date().toISOString().slice(0, 10);
    const exRows = hkRows.filter(r => String(r.exp || '') < todayYmd);

    let exSortKey = 'id',
      exSortDir = 'asc',
      exCurrentPage = 1,
      exPageSize = 10;
    const exToYmd = s => String(s || '');
    const exInRange = (d, f, t) => {
      if (!d) return true;
      const dd = exToYmd(d);
      if (f && dd < exToYmd(f)) return false;
      if (t && dd > exToYmd(t)) return false;
      return true;
    };

    function exGetFiltered() {
      const q = (document.getElementById('exSearchInput')?.value || '').toLowerCase().trim();
      const g = document.getElementById('exGolSelect')?.value || '';
      const rh = document.getElementById('exRhesusSelect')?.value || '';
      const pr = document.getElementById('exProdukSelect')?.value || '';
      const mf = document.getElementById('exMasukFrom')?.value || '';
      const mt = document.getElementById('exMasukTo')?.value || '';
      return exRows.filter(o => {
        const hitQ = !q || String(o.id).toLowerCase().includes(q);
        const hitG = !g || o.gol === g;
        const hitR = !rh || o.rh === rh;
        const hitP = !pr || o.produk === pr;
        const hitM = exInRange(o.masuk, mf, mt);
        return hitQ && hitG && hitR && hitP && hitM;
      });
    }

    function exGetSorted(data) {
      const cp = [...data];
      cp.sort((a, b) => {
        let va = a[exSortKey],
          vb = b[exSortKey];
        va = String(va ?? '').toLowerCase();
        vb = String(vb ?? '').toLowerCase();
        if (va < vb) return exSortDir === 'asc' ? -1 : 1;
        if (va > vb) return exSortDir === 'asc' ? 1 : -1;
        return 0;
      });
      return cp;
    }

    function exGetPaged(data) {
      const total = data.length,
        pages = Math.max(1, Math.ceil(total / exPageSize));
      exCurrentPage = Math.min(exCurrentPage, pages);
      const start = (exCurrentPage - 1) * exPageSize;
      return {
        slice: data.slice(start, start + exPageSize),
        total,
        pages
      };
    }

    function exRenderTable(data) {
      const tb = document.getElementById('exTableBody');
      if (!tb) return;
      if (!data.length) {
        tb.innerHTML = `<tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td></tr>`;
        return;
      }
      tb.innerHTML = data.map(o => `
    <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
      <td class="px-4 py-3 font-medium text-neutral-800">${o.id}</td>
      <td class="px-4 py-3">${dotGol(o.gol)}</td>
      <td class="px-4 py-3">${o.rh}</td>
      <td class="px-4 py-3">${badgeProduk(o.produk)}</td>
      <td class="px-4 py-3">${formatDate(o.masuk)}</td>
      <td class="px-4 py-3">${formatDate(o.exp)}</td>
    </tr>
  `).join('');
    }

    function exRange(totalPages, current, max = 5) {
      const pages = [];
      const half = Math.floor(max / 2);
      let start = Math.max(1, current - half),
        end = Math.min(totalPages, start + max - 1);
      if (end - start + 1 < max) start = Math.max(1, end - max + 1);
      if (start > 1) {
        pages.push(1);
        if (start > 2) pages.push('…');
      }
      for (let i = start; i <= end; i++) pages.push(i);
      if (end < totalPages) {
        if (end < totalPages - 1) pages.push('…');
        pages.push(totalPages);
      }
      return pages;
    }

    function exRenderPagination(total, pages) {
      const cont = document.getElementById('exPagination');
      const info = document.getElementById('exPageInfo');
      if (!cont || !info) return;
      const start = total === 0 ? 0 : (exCurrentPage - 1) * exPageSize + 1;
      const end = Math.min(exCurrentPage * exPageSize, total);
      info.textContent = `Menampilkan ${start}-${end} dari ${total} data`;
      if (pages <= 1) {
        cont.innerHTML = '';
        return;
      }
      const btn = (label, page, disabled = false, active = false) => `
    <button class="min-w-9 h-9 px-3 rounded-lg border text-sm
                   ${active?'bg-neutral-900 text-white border-neutral-900':'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'}
                   ${disabled?'opacity-50 cursor-not-allowed':''}"
            ${disabled?'disabled':''} data-expage="${page}">${label}</button>`;
      let html = '';
      html += btn('«', exCurrentPage - 1, exCurrentPage === 1);
      exRange(pages, exCurrentPage, 5).forEach(p => {
        if (p === '…') html += `<span class="px-2 text-neutral-400">…</span>`;
        else html += btn(p, p, false, p === exCurrentPage);
      });
      html += btn('»', exCurrentPage + 1, exCurrentPage === pages);
      cont.innerHTML = html;
      cont.querySelectorAll('button[data-expage]').forEach(b => {
        b.addEventListener('click', () => {
          const p = Number(b.dataset.expage);
          if (!Number.isNaN(p)) {
            exCurrentPage = p;
            exRenderAll();
          }
        });
      });
    }

    function exMarkSortHeaders() {
      document.querySelectorAll('th.ex-sortable').forEach(th => {
        th.querySelector('.ex-ind')?.remove();
        if (th.dataset.ex === exSortKey) {
          const s = document.createElement('span');
          s.className = 'ex-ind inline-block ml-1 text-neutral-400';
          s.innerHTML = exSortDir === 'asc' ? '▲' : '▼';
          th.appendChild(s);
        }
      });
    }

    function exRenderAll() {
      const filtered = exGetFiltered();
      const sorted = exGetSorted(filtered);
      const {
        slice,
        total,
        pages
      } = exGetPaged(sorted);
      exRenderTable(slice);
      exRenderPagination(total, pages);
      exMarkSortHeaders();
    }

    /* ===== Mount ===== */
    document.addEventListener('DOMContentLoaded', () => {
      setTab('avail'); // default

      // Tabel 1
      document.getElementById('searchInput')?.addEventListener('input', () => {
        currentPage = 1;
        renderAll();
      });
      const btn = document.getElementById('filterBtn'),
        menu = document.getElementById('filterMenu');
      const apply = document.getElementById('applyBtn'),
        reset = document.getElementById('resetBtn');
      btn?.addEventListener('click', (e) => {
        e.stopPropagation();
        menu?.classList.toggle('hidden');
      });
      document.addEventListener('click', (e) => {
        if (menu && btn && !menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden');
      });
      apply?.addEventListener('click', () => {
        menu?.classList.add('hidden');
        currentPage = 1;
        renderAll();
      });
      reset?.addEventListener('click', () => {
        ['golSelect', 'rhesusSelect', 'kompSelect', 'masukFrom', 'masukTo', 'expFrom', 'expTo'].forEach(id => {
          const el = document.getElementById(id);
          if (el) el.value = '';
        });
        currentPage = 1;
        renderAll();
      });
      document.getElementById('pageSize')?.addEventListener('change', (e) => {
        pageSize = Number(e.target.value) || 10;
        currentPage = 1;
        renderAll();
      });
      document.querySelectorAll('th.sortable').forEach(th => {
        th.addEventListener('click', () => {
          const key = th.dataset.key;
          if (sortKey === key) sortDir = (sortDir === 'asc' ? 'desc' : 'asc');
          else {
            sortKey = key;
            sortDir = 'asc';
          }
          renderAll();
        });
      });
      renderAll();

      // Tabel 2
      document.getElementById('hkSearchInput')?.addEventListener('input', () => {
        hkCurrentPage = 1;
        hkRenderAll();
      });
      const hkBtn = document.getElementById('hkFilterBtn'),
        hkMenu = document.getElementById('hkFilterMenu');
      const hkApply = document.getElementById('hkApplyBtn'),
        hkReset = document.getElementById('hkResetBtn');
      hkBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        hkMenu?.classList.toggle('hidden');
      });
      document.addEventListener('click', (e) => {
        if (hkMenu && hkBtn && !hkMenu.contains(e.target) && !hkBtn.contains(e.target)) hkMenu.classList.add(
          'hidden');
      });
      hkApply?.addEventListener('click', () => {
        hkMenu?.classList.add('hidden');
        hkCurrentPage = 1;
        hkRenderAll();
      });
      hkReset?.addEventListener('click', () => {
        ['hkGolSelect', 'hkRhesusSelect', 'hkProdukSelect', 'hkStatusSelect', 'hkMasukFrom', 'hkMasukTo'].forEach(
          id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
          });
        hkCurrentPage = 1;
        hkRenderAll();
      });
      document.getElementById('hkPageSize')?.addEventListener('change', (e) => {
        hkPageSize = Number(e.target.value) || 10;
        hkCurrentPage = 1;
        hkRenderAll();
      });
      document.querySelectorAll('th.hk-sortable').forEach(th => {
        th.addEventListener('click', () => {
          const key = th.dataset.hk;
          if (hkSortKey === key) hkSortDir = (hkSortDir === 'asc' ? 'desc' : 'asc');
          else {
            hkSortKey = key;
            hkSortDir = 'asc';
          }
          hkRenderAll();
        });
      });
      hkRenderAll();

      // Tabel 3
      document.getElementById('exSearchInput')?.addEventListener('input', () => {
        exCurrentPage = 1;
        exRenderAll();
      });
      const exBtn = document.getElementById('exFilterBtn'),
        exMenu = document.getElementById('exFilterMenu');
      const exApply = document.getElementById('exApplyBtn'),
        exReset = document.getElementById('exResetBtn');
      exBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        exMenu?.classList.toggle('hidden');
      });
      document.addEventListener('click', (e) => {
        if (exMenu && exBtn && !exMenu.contains(e.target) && !exBtn.contains(e.target)) exMenu.classList.add(
          'hidden');
      });
      exApply?.addEventListener('click', () => {
        exMenu?.classList.add('hidden');
        exCurrentPage = 1;
        exRenderAll();
      });
      exReset?.addEventListener('click', () => {
        ['exGolSelect', 'exRhesusSelect', 'exProdukSelect', 'exMasukFrom', 'exMasukTo'].forEach(id => {
          const el = document.getElementById(id);
          if (el) el.value = '';
        });
        exCurrentPage = 1;
        exRenderAll();
      });
      document.getElementById('exPageSize')?.addEventListener('change', (e) => {
        exPageSize = Number(e.target.value) || 10;
        exCurrentPage = 1;
        exRenderAll();
      });
      document.querySelectorAll('th.ex-sortable').forEach(th => {
        th.addEventListener('click', () => {
          const key = th.dataset.ex;
          if (exSortKey === key) exSortDir = (exSortDir === 'asc' ? 'desc' : 'asc');
          else {
            exSortKey = key;
            exSortDir = 'asc';
          }
          exRenderAll();
        });
      });
      exRenderAll();
    });
  </script>

  <style>
    th.sortable:hover,
    th.hk-sortable:hover,
    th.ex-sortable:hover {
      background-color: rgba(0, 0, 0, 0.02);
    }

    .tabbtn {
      padding: .5rem 1rem;
      font-size: .875rem;
      border-radius: .75rem;
      border: 1px solid transparent;
      color: #525252;
      background: transparent;
      transition: background-color .15s ease, color .15s ease, border-color .15s ease;
    }

    .tabbtn.is-active {
      background: #171717;
      color: #fff;
      border-color: #171717;
    }

    .tabbtn:not(.is-active):hover {
      background: #f5f5f5;
    }
  </style>
@endsection
