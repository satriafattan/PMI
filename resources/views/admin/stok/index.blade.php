{{-- resources/views/admin/stok/index.blade.php --}}
@extends('layouts.admin')
@section('content')
  @if (session('success'))
    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
      {{ session('success') }}
    </div>
  @endif

  <div class="space-y-8">
    {{-- ===== Summary cards ===== --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
        <p class="text-xs font-medium text-emerald-700">Total Stok Tersedia</p>
        <div class="mt-2 text-2xl font-semibold text-emerald-800">
          <span id="sumTotal">0</span> <span class="text-base font-medium">unit</span>
        </div>
      </div>
      <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
        <p class="text-xs font-medium text-amber-700">Stok Menipis</p>
        <div class="mt-2 text-2xl font-semibold text-amber-800">
          <span id="sumLow">0</span> <span class="text-base font-medium">unit</span>
        </div>
      </div>
      <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4">
        <p class="text-xs font-medium text-rose-700">Stok Kritis</p>
        <div class="mt-2 text-2xl font-semibold text-rose-800">
          <span id="sumCritical">0</span> <span class="text-base font-medium">unit</span>
        </div>
      </div>
      <div class="rounded-2xl border border-neutral-200 bg-neutral-50 p-4">
        <p class="text-xs font-medium text-neutral-600">Total Produk</p>
        <div class="mt-2 text-2xl font-semibold text-neutral-800">
          <span id="sumProducts">0</span> <span class="text-base font-medium">item</span>
        </div>
      </div>
    </div>

    {{-- ===== Title & toolbar ===== --}}
    <div class="space-y-4">
      <h1 class="text-2xl font-semibold md:text-3xl">Stok Darah</h1>

      {{-- Toolbar: search & filter full width, tombol tambah di kanan --}}
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        {{-- Search + filter (kiri) --}}
        <div class="flex w-full items-center gap-2 sm:flex-1">
          {{-- Search bar: full width --}}
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
                   placeholder="Cari produk… (mis. PRC, WB, TC)" />
          </div>

          {{-- Filter dropdown trigger --}}
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
            {{-- Dropdown --}}
            <div id="filterMenu"
                 class="absolute right-0 z-20 mt-2 hidden w-56 rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
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
                  <button id="resetBtn"
                          type="button"
                          class="text-sm text-neutral-600 hover:underline">Reset</button>
                  <button id="applyBtn"
                          type="button"
                          class="rounded-lg bg-neutral-900 px-3 py-1.5 text-sm text-white hover:bg-neutral-800">
                    Terapkan
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Tombol Tambah Stok (kanan) --}}
        <button id="openAddModalBtn"
                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 sm:ml-4">
          Tambah Stok
        </button>
      </div>
    </div>

    {{-- ===== TABLE (≥ md) ===== --}}
    <div class="hidden overflow-hidden rounded-2xl border border-neutral-200 bg-white md:block">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-neutral-50 text-neutral-600">
            <tr>
              <th class="w-10 px-4 py-3 text-center"></th>
              <th class="w-1/6 px-4 py-3 text-left">Produk</th>
              <th class="w-1/6 px-4 py-3 text-center">A</th>
              <th class="w-1/6 px-4 py-3 text-center">AB</th>
              <th class="w-1/6 px-4 py-3 text-center">B</th>
              <th class="w-1/6 px-4 py-3 text-center">O</th>
              <th class="w-1/6 px-4 py-3 text-center">Total</th>
            </tr>
          </thead>
          <tbody id="tableBody"></tbody>
        </table>
      </div>
    </div>

    {{-- ===== CARDS (mobile) ===== --}}
    <div id="cardsContainer"
         class="space-y-3 md:hidden"></div>
  </div>
  {{-- @dd($grouped) --}}
  {{-- ===== Modal Edit Stok Darah ===== --}}
  <div id="produkModal"
       class="fixed inset-0 z-[9999] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/40"
         data-close></div>
    <div class="relative z-10 w-[96%] max-w-4xl rounded-2xl bg-white shadow-xl">
      <div class="flex items-center justify-between border-b border-neutral-200 p-4">
        <div>
          <h3 id="produkTitle"
              class="text-lg font-semibold">Detail Stok</h3>
          <p class="text-xs text-neutral-500">FEFO: kadaluarsa terdekat di atas</p>
        </div>
        <button class="text-neutral-400 hover:text-neutral-600"
                data-close>✕</button>
      </div>
      <div class="max-h-[72vh] overflow-y-auto p-4">
        <div id="produkBody">
          <div class="px-3">
            <table class="min-w-full text-sm">
              <thead class="bg-neutral-50 text-neutral-600">
                <tr class="text-left">
                  <th class="px-4 py-3 font-medium">ID</th>
                  <th class="px-4 py-3 font-medium">Rhesus</th>
                  <th class="px-4 py-3 font-medium">Jumlah</th>
                  <th class="px-4 py-3 font-medium">Tgl Masuk</th>
                  <th class="px-4 py-3 font-medium">Tgl Kadaluarsa</th>
                  <th class="px-4 py-3 font-medium">Ditambahkan</th>
                </tr>
              </thead>
              <tbody id="riwayatBody"
                     class="divide-y divide-neutral-100">
                <!-- Riwayat stok akan ditampilkan di sini -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== MODAL: Tambah Stok ===== --}}
  <div id="addModal"
       class="fixed inset-0 z-[10000] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/40"
         id="addModalBackdrop"></div>

    <div class="relative z-10 w-[92%] max-w-lg rounded-2xl bg-white shadow-xl">
      <div class="flex items-center justify-between border-b border-neutral-200 p-4">
        <h3 class="text-lg font-semibold text-neutral-800">Tambah Stok Darah</h3>
        <button id="closeAddModalBtn"
                class="text-neutral-400 hover:text-neutral-600">✕</button>
      </div>

      <form id="addStockForm"
            class="p-4 sm:p-6"
            action="{{ route('admin.stok-darah.store') }}"
            method="POST">
        @csrf
        <div class="grid gap-4">
          {{-- Produk --}}
          <div>
            <label class="text-sm font-medium text-neutral-700">Produk <span class="text-rose-600">*</span></label>
            <select name="produk"
                    id="produk"
                    class="@error('produk') border-rose-300 @enderror mt-1 w-full rounded-xl border bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                    required>
              <option value=""
                      disabled
                      {{ old('produk') ? '' : 'selected' }}>Pilih Produk</option>
              {{-- @foreach (['FFP: Fresh Frozen Plasma', 'AHF: Cryoprecipitated AHF', 'LP: Liquid Plasma', 'TC Aferesis', 'PK'] as $opt) --}}
              <option value="WB"
                      {{ old('produk') ? 'selected' : '' }}>WB: Whole Blood</option>
              <option value="PRC"
                      {{ old('produk') ? 'selected' : '' }}>PRC: Packed Red Cell</option>
              <option value="TC"
                      {{ old('produk') ? 'selected' : '' }}>TC: Thrombocyte Concentrate</option>
              <option value="FFP"
                      {{ old('produk') ? 'selected' : '' }}>FFP: Fresh Frozen Plasma</option>
              <option value="CRYO"
                      {{ old('produk') ? 'selected' : '' }}>CRYO: Cryoprecipitated Anti-Hemophilic Factor</option>
              <option value="LP"
                      {{ old('produk') ? 'selected' : '' }}>LP: Liquid Plasma</option>
              <option value="TCA"
                      {{ old('produk') ? 'selected' : '' }}>TCA: Thrombocyte Apheresis</option>
              <option value="CP"
                      {{ old('produk') ? 'selected' : '' }}>CP: Convalescent Plasma</option>
              {{-- @endforeach --}}
            </select>
            @error('produk')
              <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Golongan --}}
          <div>
            <label class="text-sm font-medium text-neutral-700">Golongan <span class="text-rose-600">*</span></label>
            <select name="gol_darah"
                    id="gol_darah"
                    class="@error('gol_darah') border-rose-300 @enderror mt-1 w-full rounded-xl border bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                    required>
              <option value=""
                      disabled
                      {{ old('gol_darah') ? '' : 'selected' }}>Pilih Golongan</option>
              @foreach (['A', 'AB', 'B', 'O'] as $gol)
                <option value="{{ $gol }}"
                        {{ old('gol_darah') === $gol ? 'selected' : '' }}>{{ $gol }}</option>
              @endforeach
            </select>
            @error('gol_darah')
              <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Rhesus -->
          <div>
            <label class="text-sm font-medium text-neutral-700">
              Rhesus <span class="text-rose-600">*</span>
            </label>
            <select name="rhesus"
                    id="rhesus"
                    class="@error('rhesus') border-rose-300 @enderror mt-1 w-full rounded-xl border bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                    required>
              <option value=""
                      disabled
                      {{ old('rhesus') ? '' : 'selected' }}>Pilih Rhesus</option>
              <option value="Rh+"
                      {{ old('rhesus') === 'Rh+' ? 'selected' : '' }}>Positif (+)</option>
              <option value="Rh-"
                      {{ old('rhesus') === 'Rh-' ? 'selected' : '' }}>Negatif (-)</option>

            </select>
            @error('rhesus')
              <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Tanggal --}}
          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label class="text-sm font-medium text-neutral-700">Tanggal Masuk <span
                      class="text-rose-600">*</span></label>
              <input type="date"
                     id="tgl_masuk"
                     name="tgl_masuk"
                     value="{{ old('tgl_masuk') }}"
                     class="@error('tgl_masuk') border-rose-300 @enderror mt-1 w-full rounded-xl border bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                     required>
              @error('tgl_masuk')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="text-sm font-medium text-neutral-700">Tanggal Kadaluwarsa <span
                      class="text-rose-600">*</span></label>
              <input type="date"
                     id="tgl_kadaluarsa"
                     name="tgl_kadaluarsa"
                     value="{{ old('tgl_kadaluarsa') }}"
                     class="@error('tgl_kadaluarsa') border-rose-300 @enderror mt-1 w-full rounded-xl border bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                     required>
              @error('tgl_kadaluarsa')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
              @enderror
            </div>
          </div>

          {{-- Jumlah --}}
          <div>
            <label class="text-sm font-medium text-neutral-700">Jumlah (unit) <span
                    class="text-rose-600">*</span></label>
            <input type="number"
                   id="jumlah"
                   name="jumlah"
                   min="1"
                   step="1"
                   value="{{ old('jumlah', 1) }}"
                   class="@error('jumlah') border-rose-300 @enderror mt-1 w-full rounded-xl border bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                   required>
            @error('jumlah')
              <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-4">
          <button type="button"
                  id="cancelAddBtn"
                  class="rounded-lg border border-neutral-300 px-4 py-2 text-neutral-700 hover:bg-neutral-50">
            Batal
          </button>
          <button type="submit"
                  class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- ===== Data awal dari controller ===== --}}
  <script>
    const LOW_TH = 50,
      CRIT_TH = 20;
    // Ambil data dari controller
    let products = @json($summary);
    let riwayatStok = @json($riwayat);
    let sortKey = '',
      sortDir = 'asc';

    const iconEdit = (produk, golDarah) => `
      <button data-open="produkModal" data-produk="${produk}" data-goldarah="${golDarah}"
              class="text-blue-600 hover:text-blue-800 p-1 rounded-md hover:bg-blue-50">
        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                d="M12 20h9M16.5 3.5l4 4L7 21H3v-4L16.5 3.5z"/>
        </svg>
      </button>
    `;

    const iconChevronDown = `
      <svg class="size-5 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
      </svg>
    `;

    const iconHistory = `
      <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" 
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
    `;


    const iconTrash = () =>
      `<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M9 7V5h6v2m-8 0 1 12h8l1-12"/></svg>`;

    // Fungsi untuk format tanggal dari ISO ke DD-MM-YYYY
    function formatDate(dateString) {
      if (!dateString) return '-';
      const date = new Date(dateString);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = date.getFullYear();
      return `${day}-${month}-${year}`;
    }

    document.addEventListener('click', function(e) {
      // ===== OPEN MODAL =====
      const openTarget = e.target.closest('[data-open]');
      if (openTarget) {
        const modalId = openTarget.getAttribute('data-open');
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.classList.remove('hidden');
          modal.classList.add('flex');

          // Jika ini modal produk, tampilkan riwayat stok
          if (modalId === 'produkModal') {
            const produk = openTarget.getAttribute('data-produk');
            const golDarah = openTarget.getAttribute('data-goldarah');
            if (produk && golDarah) {
              showRiwayatStok(produk, golDarah);
            }
          }
        }
      }

      // ===== CLOSE MODAL =====
      if (e.target.matches('[data-close]') || e.target.hasAttribute('data-close')) {
        document.querySelectorAll(`#${e.target.closest('[id]')?.id}`).forEach(m => {
          m.classList.add('hidden');
        });
      }
    });


    const rowTotal = (r) => (r.A || 0) + (r.AB || 0) + (r.B || 0) + (r.O || 0);

    function pillFor(value) {
      let bgColor, textColor, borderColor;
      if (value >= 50) {
        bgColor = 'bg-emerald-100';
        textColor = 'text-emerald-700';
        borderColor = 'border-emerald-200';
      } else if (value >= 20) {
        bgColor = 'bg-amber-100';
        textColor = 'text-amber-700';
        borderColor = 'border-amber-200';
      } else {
        bgColor = 'bg-rose-100';
        textColor = 'text-rose-700';
        borderColor = 'border-rose-200';
      }

      return `
      <span class="${bgColor} ${textColor} ${borderColor} text-sm px-4 py-1.5 rounded-full inline-block min-w-[48px] text-center font-semibold border-2 pill-hover shadow-sm">
        ${value}
      </span>
    `;
    }

    function renderSummary() {
      let total = 0,
        low = 0,
        critical = 0;
      products.forEach(r => {
        total += rowTotal(r);
        ['A', 'AB', 'B', 'O'].forEach(k => {
          const v = r[k] || 0;
          if (v < CRIT_TH) critical += v;
          else if (v < LOW_TH) low += v;
        });
      });
      document.getElementById('sumTotal').textContent = total;
      document.getElementById('sumLow').textContent = low;
      document.getElementById('sumCritical').textContent = critical;
      document.getElementById('sumProducts').textContent = products.length;
    }

    function getFiltered() {
      const q = (document.getElementById('searchInput')?.value || '').toLowerCase().trim();
      const mode = document.getElementById('statusSelect')?.value || 'all';
      return products.filter(r => {
        const matchQ = !q || (r.produk || '').toLowerCase().includes(q);
        const anyLow = ['A', 'AB', 'B', 'O'].some(k => (r[k] || 0) < LOW_TH && (r[k] || 0) >= CRIT_TH);
        const anyCritical = ['A', 'AB', 'B', 'O'].some(k => (r[k] || 0) < CRIT_TH);
        let matchM = true;
        if (mode === 'low') matchM = anyLow;
        if (mode === 'critical') matchM = anyCritical;
        return matchQ && matchM;
      });
    }

    function getSorted(data) {
      if (!sortKey) return data;
      const cp = [...data];
      cp.sort((a, b) => {
        let va = (sortKey === 'total') ? rowTotal(a) : a[sortKey];
        let vb = (sortKey === 'total') ? rowTotal(b) : b[sortKey];
        if (typeof va === 'number' && typeof vb === 'number') return sortDir === 'asc' ? va - vb : vb - va;
        va = String(va ?? '').toLowerCase();
        vb = String(vb ?? '').toLowerCase();
        if (va < vb) return sortDir === 'asc' ? -1 : 1;
        if (va > vb) return sortDir === 'asc' ? 1 : -1;
        return 0;
      });
      return cp;
    }

    function renderTable(data) {
      const tb = document.getElementById('tableBody');
      if (!tb) return;
      if (data.length === 0) {
        tb.innerHTML =
          `<tr><td colspan="8" class="px-4 py-8 text-center text-neutral-500">Belum ada data. Tambahkan stok terlebih dahulu.</td></tr>`;
        return;
      }

      tb.innerHTML = data.map((r, index) => {
        const rowId = `row-${index}`;
        const detailsId = `details-${index}`;

        const golDarahCells = ['A', 'AB', 'B', 'O'].map(k => {
          const value = r[k] || 0;
          return `
            <td class="w-1/6 px-4 py-3 text-center">
              <span class="inline-block w-full">
                ${pillFor(value)}
              </span>
            </td>
          `;
        }).join('');

        // Buat baris detail untuk setiap golongan darah
        const detailRows = ['A', 'AB', 'B', 'O'].map(gol => {
          const key = `${r.produk}_${gol}`;
          const riwayat = riwayatStok[key] || [];
          const totalUnits = r[gol] || 0;

          return `
            <div class="mb-4 last:mb-0">
              <div class="mb-2 flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 text-white font-bold text-sm shadow-sm">
                    ${gol}
                  </span>
                  <h4 class="font-semibold text-neutral-800">Golongan ${gol}</h4>
                  <span class="text-xs text-neutral-500">(${totalUnits} unit)</span>
                </div>
                ${riwayat.length > 0 ? `
                            <span class="text-xs text-neutral-500 flex items-center gap-1">
                              ${iconHistory}
                              ${riwayat.length} riwayat
                            </span>
                          ` : ''}
              </div>
              
              ${riwayat.length > 0 ? `
                          <div class="rounded-lg border border-neutral-200 overflow-hidden">
                            <table class="w-full text-xs">
                              <thead class="bg-neutral-50 text-neutral-600">
                                <tr>
                                  <th class="px-3 py-2 text-left font-medium">ID</th>
                                  <th class="px-3 py-2 text-left font-medium">Rhesus</th>
                                  <th class="px-3 py-2 text-left font-medium">Jumlah</th>
                                  <th class="px-3 py-2 text-left font-medium">Tgl Masuk</th>
                                  <th class="px-3 py-2 text-left font-medium">Tgl Kadaluarsa</th>
                                  <th class="px-3 py-2 text-left font-medium">Ditambahkan</th>
                                </tr>
                              </thead>
                              <tbody class="divide-y divide-neutral-100 bg-white">
                                ${riwayat.map(item => `
                        <tr class="hover:bg-blue-50/30 transition-colors">
                          <td class="px-3 py-2 font-medium text-neutral-700">#${item.id}</td>
                          <td class="px-3 py-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${item.rhesus === 'Rh+' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700'}">
                              ${item.rhesus}
                            </span>
                          </td>
                          <td class="px-3 py-2 font-medium text-blue-600">${item.jumlah} unit</td>
                          <td class="px-3 py-2 text-neutral-600">${item.tgl_masuk}</td>
                          <td class="px-3 py-2 text-neutral-600">${item.tgl_kadaluarsa}</td>
                          <td class="px-3 py-2 text-neutral-500 text-xs">${item.created_at}</td>
                        </tr>
                      `).join('')}
                              </tbody>
                            </table>
                          </div>
                        ` : `
                          <div class="rounded-lg border border-dashed border-neutral-300 bg-neutral-50 px-4 py-6 text-center">
                            <p class="text-sm text-neutral-500">Belum ada riwayat penambahan stok untuk golongan ${gol}</p>
                          </div>
                        `}
            </div>
          `;
        }).join('');

        return `
          <tr id="${rowId}" class="border-t border-neutral-100 hover:bg-neutral-50/50 transition-colors cursor-pointer" data-row-toggle="${detailsId}">
            <td class="px-4 py-3 text-center">
              <button class="text-neutral-500 hover:text-neutral-700 transition-colors" data-chevron>
                ${iconChevronDown}
              </button>
            </td>
            <td class="px-4 py-3 text-left font-medium text-neutral-800">${r.produk}</td>
            ${golDarahCells}
            <td class="px-4 py-3 font-bold text-center text-neutral-800">${rowTotal(r)}</td>
          </tr>
          <tr id="${detailsId}" class="detail-row hidden border-t border-neutral-100">
            <td colspan="8" class="px-0 py-0">
              <div class="bg-gradient-to-br from-blue-50/50 via-white to-purple-50/30 px-6 py-5 border-l-4 border-blue-500">
                <div class="max-w-6xl">
                  <div class="mb-3 flex items-center gap-2">
                    <div class="h-6 w-1 bg-blue-500 rounded-full"></div>
                    <h3 class="text-base font-bold text-neutral-800">Detail Log Perubahan Stok: ${r.produk}</h3>
                  </div>
                  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    ${detailRows}
                  </div>
                </div>
              </div>
            </td>
          </tr>
        `;
      }).join('');

      // Attach click handlers untuk toggle
      attachToggleHandlers();
    }

    function renderCards(data) {
      const wrap = document.getElementById('cardsContainer');
      if (!wrap) return;
      if (data.length === 0) {
        wrap.innerHTML = `<div class="text-center text-neutral-500">Belum ada data.</div>`;
        return;
      }

      wrap.innerHTML = data.map((r, index) => {
        const cardId = `card-${index}`;
        const detailsId = `card-details-${index}`;

        // Detail untuk setiap golongan darah
        const detailContent = ['A', 'AB', 'B', 'O'].map(gol => {
          const key = `${r.produk}_${gol}`;
          const riwayat = riwayatStok[key] || [];
          const totalUnits = r[gol] || 0;

          return `
            <div class="border-t border-neutral-200 pt-3 first:border-0 first:pt-0">
              <div class="mb-2 flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 text-white font-bold text-xs">
                    ${gol}
                  </span>
                  <h5 class="font-semibold text-sm">Golongan ${gol}</h5>
                  <span class="text-xs text-neutral-500">(${totalUnits} unit)</span>
                </div>
              </div>
              
              ${riwayat.length > 0 ? `
                          <div class="space-y-2 mt-2">
                            ${riwayat.slice(0, 3).map(item => `
                    <div class="rounded-lg border border-neutral-200 bg-white p-2 text-xs">
                      <div class="flex justify-between items-start mb-1">
                        <span class="font-medium text-neutral-700">#${item.id}</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium ${item.rhesus === 'Rh+' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700'}">
                          ${item.rhesus}
                        </span>
                      </div>
                      <div class="text-neutral-600 space-y-0.5">
                        <div><span class="font-medium text-blue-600">${item.jumlah} unit</span></div>
                        <div>Masuk: ${item.tgl_masuk}</div>
                        <div>Exp: ${item.tgl_kadaluarsa}</div>
                      </div>
                    </div>
                  `).join('')}
                            ${riwayat.length > 3 ? `
                    <p class="text-xs text-center text-neutral-500">+${riwayat.length - 3} lainnya</p>
                  ` : ''}
                          </div>
                        ` : `
                          <p class="text-xs text-neutral-500 text-center py-2">Belum ada riwayat</p>
                        `}
            </div>
          `;
        }).join('');

        return `
          <div id="${cardId}" class="rounded-2xl border border-neutral-200 bg-white overflow-hidden">
            <div class="p-4">
              <div class="flex items-start justify-between cursor-pointer" data-card-toggle="${detailsId}">
                <div class="flex-1">
                  <p class="font-semibold text-neutral-800">${r.produk}</p>
                  <div class="mt-3 grid grid-cols-4 gap-2 text-sm">
                    <div class="text-neutral-500 text-xs">A</div><div>${pillFor(r.A||0)}</div>
                    <div class="text-neutral-500 text-xs">AB</div><div>${pillFor(r.AB||0)}</div>
                    <div class="text-neutral-500 text-xs">B</div><div>${pillFor(r.B||0)}</div>
                    <div class="text-neutral-500 text-xs">O</div><div>${pillFor(r.O||0)}</div>
                    <div class="text-neutral-500 text-xs">Total</div><div class="col-span-3 font-bold">${rowTotal(r)}</div>
                  </div>
                </div>
                <button class="text-neutral-500 hover:text-neutral-700 ml-2" data-chevron-card>
                  ${iconChevronDown}
                </button>
              </div>
            </div>
            
            <div id="${detailsId}" class="hidden bg-gradient-to-br from-blue-50/50 to-purple-50/30 px-4 py-3 border-t border-neutral-200">
              <h4 class="text-sm font-bold text-neutral-800 mb-3">Detail Log Perubahan</h4>
              <div class="space-y-3">
                ${detailContent}
              </div>
            </div>
          </div>
        `;
      }).join('');

      // Attach click handlers untuk mobile cards
      attachCardToggleHandlers();
    }

    function attachToggleHandlers() {
      document.querySelectorAll('[data-row-toggle]').forEach(row => {
        row.addEventListener('click', function(e) {
          // Jangan toggle jika klik pada button/link
          if (e.target.closest('button') || e.target.closest('a')) return;

          const detailsId = this.dataset.rowToggle;
          const detailsRow = document.getElementById(detailsId);
          const chevron = this.querySelector('[data-chevron] svg');

          if (detailsRow) {
            const isHidden = detailsRow.classList.contains('hidden');

            // Close all other detail rows
            document.querySelectorAll('.detail-row').forEach(dr => {
              if (dr !== detailsRow) {
                dr.classList.add('hidden');
              }
            });
            document.querySelectorAll('[data-chevron] svg').forEach(ch => {
              if (ch !== chevron) {
                ch.style.transform = 'rotate(0deg)';
              }
            });

            // Toggle current row
            if (isHidden) {
              detailsRow.classList.remove('hidden');
              if (chevron) chevron.style.transform = 'rotate(180deg)';
            } else {
              detailsRow.classList.add('hidden');
              if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
          }
        });
      });
    }

    function attachCardToggleHandlers() {
      document.querySelectorAll('[data-card-toggle]').forEach(trigger => {
        trigger.addEventListener('click', function() {
          const detailsId = this.dataset.cardToggle;
          const detailsDiv = document.getElementById(detailsId);
          const chevron = this.querySelector('[data-chevron-card] svg');

          if (detailsDiv) {
            detailsDiv.classList.toggle('hidden');
            if (chevron) {
              const isHidden = detailsDiv.classList.contains('hidden');
              chevron.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
            }
          }
        });
      });
    }

    function markSortHeaders() {
      document.querySelectorAll('th.sortable').forEach(th => {
        th.querySelector('.sort-ind')?.remove();
        if (th.dataset.key === sortKey) {
          const s = document.createElement('span');
          s.className = 'sort-ind ml-1 inline-block text-neutral-400';
          s.innerHTML = sortDir === 'asc' ? '▲' : '▼';
          th.appendChild(s);
        }
      });
    }

    function renderAll() {
      const filtered = getFiltered();
      const sorted = getSorted(filtered);
      renderTable(sorted);
      renderCards(sorted);
      renderSummary();
      markSortHeaders();
    }

    // ===== Modal helpers =====
    const addModal = document.getElementById('addModal');
    const addForm = document.getElementById('addStockForm');
    const produkSel = document.getElementById('produk');
    const golSel = document.getElementById('gol_darah');

    function openAddModal() {
      addModal?.classList.remove('hidden');
      addModal?.classList.add('flex');
    }

    function closeAddModal() {
      addModal?.classList.add('hidden');
      addModal?.classList.remove('flex');
      if (addForm) {
        addForm.reset();
        if (produkSel) produkSel.value = '';
        if (golSel) golSel.value = '';
      }
    }

    function showRiwayatStok(produk, golDarah) {
      const key = `${produk}_${golDarah}`;
      const riwayat = riwayatStok[key] || [];
      const tbody = document.getElementById('riwayatBody');
      const title = document.getElementById('produkTitle');

      if (title) {
        title.textContent = `Riwayat Stok: ${produk} (Golongan ${golDarah})`;
      }

      if (tbody) {
        if (riwayat.length === 0) {
          tbody.innerHTML =
            '<tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">Belum ada riwayat penambahan stok.</td></tr>';
          return;
        }

        tbody.innerHTML = riwayat.map(item => `
          <tr class="hover:bg-neutral-50/60">
            <td class="px-4 py-3 font-medium">#${item.id}</td>
            <td class="px-4 py-3">${item.rhesus}</td>
            <td class="px-4 py-3">${item.jumlah} unit</td>
            <td class="px-4 py-3">${formatDate(item.tgl_masuk)}</td>
            <td class="px-4 py-3">${formatDate(item.tgl_kadaluarsa)}</td>
            <td class="px-4 py-3">${formatDate(item.created_at)}</td>
          </tr>
        `).join('');
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      // search
      const searchInput = document.getElementById('searchInput');
      searchInput && searchInput.addEventListener('input', renderAll);

      // filter dropdown
      const btn = document.getElementById('filterBtn');
      const menu = document.getElementById('filterMenu');
      const apply = document.getElementById('applyBtn');
      const reset = document.getElementById('resetBtn');
      const statusSelect = document.getElementById('statusSelect');

      btn && btn.addEventListener('click', (e) => {
        e.stopPropagation();
        menu?.classList.toggle('hidden');
      });
      document.addEventListener('click', (e) => {
        if (menu && btn && !menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden');
      });
      apply && apply.addEventListener('click', () => {
        menu?.classList.add('hidden');
        renderAll();
      });
      reset && reset.addEventListener('click', () => {
        if (statusSelect) statusSelect.value = 'all';
        renderAll();
      });

      // sort header
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

      // modal open/close
      document.getElementById('openAddModalBtn')?.addEventListener('click', openAddModal);
      document.getElementById('closeAddModalBtn')?.addEventListener('click', closeAddModal);
      document.getElementById('cancelAddBtn')?.addEventListener('click', closeAddModal);
      document.getElementById('addModalBackdrop')?.addEventListener('click', closeAddModal);
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAddModal();
      });

      // render pertama
      renderAll();
    });
  </script>

  <style>
    th.sortable:hover {
      background-color: rgba(0, 0, 0, 0.02);
    }

    /* Animasi untuk detail rows */
    .detail-row {
      animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Smooth transition untuk chevron */
    [data-chevron] svg,
    [data-chevron-card] svg {
      transition: transform 0.3s ease;
    }

    /* Hover effect untuk rows yang bisa di-expand */
    [data-row-toggle]:hover {
      background-color: rgba(59, 130, 246, 0.05);
    }

    /* Glow effect untuk expanded row */
    .detail-row td {
      box-shadow: inset 0 2px 8px rgba(59, 130, 246, 0.1);
    }

    /* Styling untuk pills dengan hover effect */
    .pill-hover {
      transition: all 0.2s ease;
    }

    .pill-hover:hover {
      transform: scale(1.05);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Gradient background untuk header detail */
    .detail-header-gradient {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* Scroll smooth untuk table yang panjang */
    .detail-row td>div {
      max-height: 600px;
      overflow-y: auto;
    }

    .detail-row td>div::-webkit-scrollbar {
      width: 6px;
    }

    .detail-row td>div::-webkit-scrollbar-track {
      background: rgba(0, 0, 0, 0.05);
      border-radius: 10px;
    }

    .detail-row td>div::-webkit-scrollbar-thumb {
      background: rgba(59, 130, 246, 0.4);
      border-radius: 10px;
    }

    .detail-row td>div::-webkit-scrollbar-thumb:hover {
      background: rgba(59, 130, 246, 0.6);
    }
  </style>
@endsection
