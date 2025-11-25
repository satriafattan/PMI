@extends('layouts.admin')

@section('title', 'Detail Darah - Kadaluwarsa')

@section('content')
  @php
    $rows = $rows ?? collect();
  @endphp

  <div class="space-y-4">
    <div class="space-y-1">
      <h1 class="text-2xl font-semibold md:text-3xl">Detail Darah - Kadaluwarsa</h1>
      <p class="text-sm text-neutral-500">Data darah yang sudah kadaluwarsa</p>
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
               placeholder="Cari ID darah atau produk..."
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
        <a href="{{ route('admin.detail-darah.kadaluwarsa.export') }}"
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
        <table class="w-full text-sm">
          <thead class="border-b border-neutral-200 bg-neutral-50">
            <tr>
              <th class="px-4 py-3 text-left font-medium text-neutral-700">ID Darah</th>
              <th class="px-4 py-3 text-left font-medium text-neutral-700">Golongan</th>
              <th class="px-4 py-3 text-left font-medium text-neutral-700">Rhesus</th>
              <th class="px-4 py-3 text-left font-medium text-neutral-700">Produk</th>
              <th class="px-4 py-3 text-left font-medium text-neutral-700">Tgl Masuk</th>
              <th class="px-4 py-3 text-left font-medium text-neutral-700">Tgl Kadaluwarsa</th>
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
        return (row.id || '').toLowerCase().includes(s) ||
          (row.produk || '').toLowerCase().includes(s);
      });

      const total = filtered.length;
      const pages = Math.ceil(total / size) || 1;
      page = Math.min(page, pages);

      const start = (page - 1) * size;
      const slice = filtered.slice(start, start + size);

      const tbody = document.getElementById('tableBody');
      if (slice.length === 0) {
        tbody.innerHTML =
          '<tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">Tidak ada data kadaluwarsa</td></tr>';
      } else {
        tbody.innerHTML = slice.map(row => `
          <tr class="hover:bg-neutral-50">
            <td class="px-4 py-3">${row.id || '-'}</td>
            <td class="px-4 py-3">
              <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-pink-50 text-sm font-semibold text-pink-600">
                ${row.gol || '-'}
              </span>
            </td>
            <td class="px-4 py-3">${row.rh || '-'}</td>
            <td class="px-4 py-3">
              <span class="inline-flex rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">${row.produk || '-'}</span>
            </td>
            <td class="px-4 py-3">${formatDate(row.masuk)}</td>
            <td class="px-4 py-3">
              <span class="inline-flex rounded-md bg-rose-50 px-2 py-1 text-xs font-medium text-rose-700">${formatDate(row.exp)}</span>
            </td>
          </tr>
        `).join('');
      }

      document.getElementById('pageInfo').textContent =
        `Menampilkan ${start + 1}-${Math.min(start + size, total)} dari ${total} data`;

      const pagination = document.getElementById('pagination');
      pagination.innerHTML = '';

      if (pages > 1) {
        for (let i = 1; i <= pages; i++) {
          const btn = document.createElement('button');
          btn.textContent = i;
          btn.className =
            `px-3 py-1 rounded-md text-sm ${i === page ? 'bg-neutral-900 text-white' : 'bg-white border border-neutral-200 text-neutral-700 hover:bg-neutral-50'}`;
          btn.addEventListener('click', () => {
            page = i;
            renderTable();
          });
          pagination.appendChild(btn);
        }
      }
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
  </script>
@endsection
