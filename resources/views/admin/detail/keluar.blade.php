@extends('layouts.admin')

@section('title', 'Detail Darah - Keluar')

@section('content')
  @php
    $rows = $rows ?? collect();
  @endphp

  <div class="space-y-4">
    <div class="space-y-1">
      <h1 class="text-2xl font-semibold md:text-3xl">Detail Darah - Keluar</h1>
      <p class="text-sm text-neutral-500">Data darah yang sudah keluar</p>
    </div>

    {{-- Filter & Search --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div class="relative flex-1">
        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
          <svg class="h-5 w-5 text-neutral-400"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </span>
        <input id="searchInput"
               type="text"
               placeholder="Cari ID darah..."
               class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm focus:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/10">
      </div>

      <div class="flex items-center gap-2">
        <label class="text-sm text-neutral-600">Tampilkan:</label>
        <select id="pageSize"
                class="rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm focus:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/10">
          <option value="10"
                  selected>10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
      </div>

      {{-- Export --}}
      <div class="flex items-center gap-2">
        <a href="{{ route('admin.detail-darah.keluar.export') }}"
           class="inline-flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700 hover:bg-emerald-100">
          <svg class="size-4"
               viewBox="0 0 24 24"
               fill="none"
               stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="1.6"
                  d="m4 4 6 6m0 0L4 16m6-6h10" />
          </svg>
          Excel
        </a>
      </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table id="keluarTable"
               class="w-full text-sm">
          <thead class="border-b border-neutral-200 bg-neutral-50">
            <tr>
              <x-sortable-th column="id_darah"
                             label="ID Darah" />
              <x-sortable-th column="golongan"
                             label="Golongan" />
              <x-sortable-th column="rhesus"
                             label="Rhesus" />
              <x-sortable-th column="produk"
                             label="Produk" />
              <x-sortable-th column="rumah_sakit"
                             label="Rumah Sakit" />
              <x-sortable-th column="tgl_keluar"
                             label="Tgl Keluar" />
              <x-sortable-th column="status"
                             label="Status" />
            </tr>
          </thead>
          <tbody id="tableBody"
                 class="divide-y divide-neutral-100"></tbody>
        </table>
      </div>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center justify-between">
      <div id="pageInfo"
           class="text-sm text-neutral-600"></div>
      <div id="pagination"
           class="flex gap-1"></div>
    </div>
  </div>

  <script>
    const data = @json($rows);
    let page = 1,
      size = 10,
      search = '';

    function formatDate(d) {
      if (!d) return '-';
      const parts = d.split('-');
      return parts.length === 3 ? `${parts[2]}-${parts[1]}-${parts[0]}` : d;
    }

    function renderTable() {
      const filtered = data.filter(row => {
        if (!search) return true;
        const s = search.toLowerCase();
        return (row.id || '').toLowerCase().includes(s);
      });

      const total = filtered.length;
      const pages = Math.ceil(total / size) || 1;
      page = Math.min(page, pages);

      const start = (page - 1) * size;
      const slice = filtered.slice(start, start + size);

      const tbody = document.getElementById('tableBody');
      if (slice.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="px-4 py-8 text-center text-neutral-500">Tidak ada data</td></tr>';
      } else {
        tbody.innerHTML = slice.map(row => `
          <tr class="hover:bg-neutral-50">
            <td class="px-4 py-3" data-id_darah="${row.id || ''}">${row.id || '-'}</td>
            <td class="px-4 py-3" data-golongan="${row.gol || ''}">
              <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-pink-50 text-sm font-semibold text-pink-600">
                ${row.gol || '-'}
              </span>
            </td>
            <td class="px-4 py-3" data-rhesus="${row.rh || ''}">${row.rh || '-'}</td>
            <td class="px-4 py-3" data-produk="${row.produk || ''}">
              <span class="inline-flex rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">${row.produk || '-'}</span>
            </td>
            <td class="px-4 py-3" data-rumah_sakit="${row.penerima || ''}">${row.penerima || '-'}</td>
            <td class="px-4 py-3" data-tgl_keluar="${row.keluar || ''}">${formatDate(row.keluar)}</td>
            <td class="px-4 py-3" data-status="${row.status || ''}">
              <span class="inline-flex rounded-md px-2 py-1 text-xs font-medium ${row.status === 'Dispensed' ? 'bg-green-50 text-green-700' : 'bg-neutral-50 text-neutral-700'}">${row.status || '-'}</span>
            </td>
          </tr>
        `).join('');
      }

      document.getElementById('pageInfo').textContent =
        `Menampilkan ${start + 1}-${Math.min(start + size, total)} dari ${total} data`;

      renderPagination(total, pages);
    }

    function getPageRange(totalPages, current, max = 5) {
      const out = [];
      const half = Math.floor(max / 2);
      let start = Math.max(1, current - half),
        end = Math.min(totalPages, start + max - 1);
      if (end - start + 1 < max) start = Math.max(1, end - max + 1);
      if (start > 1) {
        out.push(1);
        if (start > 2) out.push('…');
      }
      for (let i = start; i <= end; i++) out.push(i);
      if (end < totalPages) {
        if (end < totalPages - 1) out.push('…');
        out.push(totalPages);
      }
      return out;
    }

    function renderPagination(total, pages) {
      const cont = document.getElementById('pagination');
      const info = document.getElementById('pageInfo');
      const start = total === 0 ? 0 : (page - 1) * size + 1;
      const end = Math.min(page * size, total);
      info.textContent = total > 0 ? `Menampilkan ${start}-${end} dari ${total} data` : 'Tidak ada data';
      
      if (pages <= 1) {
        cont.innerHTML = '';
        return;
      }

      const btn = (lab, goto, disabled = false, active = false) => `
        <button class="min-w-9 h-9 px-3 rounded-lg border text-sm ${active ? 'bg-neutral-900 text-white border-neutral-900' : 'bg-white border-neutral-200 text-neutral-700 hover:bg-neutral-50'} ${disabled ? 'opacity-50 cursor-not-allowed' : ''}"
                ${disabled ? 'disabled' : ''} data-page="${goto}">${lab}</button>`;
      
      let html = btn('«', page - 1, page === 1);
      const range = getPageRange(pages, page, 5);
      range.forEach(p => html += (p === '…') ? `<span class="px-2 text-neutral-400">…</span>` : btn(p, p, false, p === page));
      html += btn('»', page + 1, page === pages);
      cont.innerHTML = html;
      
      cont.querySelectorAll('button[data-page]:not([disabled])').forEach(b => {
        b.addEventListener('click', (e) => {
          e.preventDefault();
          const newPage = parseInt(b.dataset.page);
          if (!isNaN(newPage) && newPage >= 1 && newPage <= pages && newPage !== page) {
            page = newPage;
            renderTable();
            window.scrollTo({ top: 0, behavior: 'smooth' });
          }
        });
      });
    }

    document.getElementById('searchInput').addEventListener('input', (e) => {
      search = e.target.value;
      page = 1;
      renderTable();
    });

    document.getElementById('pageSize').addEventListener('change', (e) => {
      size = parseInt(e.target.value);
      page = 1;
      renderTable();
    });

    renderTable();

    // Initialize Table Sorter
    if (typeof TableSorter !== 'undefined') {
      new TableSorter('#keluarTable');
    }
  </script>
@endsection
