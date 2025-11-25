@extends('layouts.admin')

@section('title', 'Informasi Detail Darah')

@section('content')
@php
    $rows = $rows ?? collect();
    $historyRows = $historyRows ?? collect();
    $expiredRows = $expiredRows ?? collect();
    
    $kompAll = ['WB', 'PRC', 'TC', 'FFP', 'CRYO', 'LP', 'TCA', 'CP'];
    $kompOpts = collect($rows)->pluck('komponen')->filter()->unique()->values()->all();
    
    if (empty($kompOpts)) {
        $kompOpts = $kompAll;
    } else {
        $kompOpts = collect($kompOpts)->merge($kompAll)->unique()->values()->all();
    }
@endphp

<div class="space-y-4">
    <div class="space-y-1">
        <h1 class="text-2xl font-semibold md:text-3xl">Informasi Detail Darah</h1>
        <p class="text-sm text-neutral-500">Data stok unit darah yang tersedia, keluar, dan kadaluwarsa</p>
    </div>

    {{-- Tab Navigation dengan warna hitam --}}
    <div class="inline-flex rounded-xl border border-neutral-200 bg-white p-1 shadow-sm">
        <button id="btnAvail" type="button" class="tab-btn active">
            Tersedia
        </button>
        <button id="btnUnavail" type="button" class="tab-btn">
            Keluar
        </button>
        <button id="btnExpired" type="button" class="tab-btn">
            Kadaluwarsa
        </button>
    </div>

    {{-- SECTION 1: TERSEDIA --}}
    <section id="secAvail" class="space-y-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="relative flex-1">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input id="searchInput" type="text" placeholder="Cari ID darah atau komponen..."
                    class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm focus:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/10">
            </div>
            
            <div class="flex items-center gap-2">
                <label class="text-sm text-neutral-600">Tampilkan:</label>
                <select id="pageSize" class="rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm focus:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/10">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 border-b border-neutral-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">ID Darah</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Golongan</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Rhesus</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Produk</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Tgl Masuk</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Tgl Kadaluwarsa</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y divide-neutral-100"></tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div id="pageInfo" class="text-sm text-neutral-600"></div>
            <div id="pagination" class="flex gap-1"></div>
        </div>
    </section>

    {{-- SECTION 2: KELUAR --}}
    <section id="secUnavail" class="hidden space-y-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="relative flex-1">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input id="searchInput2" type="text" placeholder="Cari ID darah..."
                    class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm focus:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/10">
            </div>
            
            <div class="flex items-center gap-2">
                <label class="text-sm text-neutral-600">Tampilkan:</label>
                <select id="pageSize2" class="rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm focus:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/10">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 border-b border-neutral-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">ID Darah</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Golongan</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Rhesus</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Produk</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Penerima</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Status</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody2" class="divide-y divide-neutral-100"></tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div id="pageInfo2" class="text-sm text-neutral-600"></div>
            <div id="pagination2" class="flex gap-1"></div>
        </div>
    </section>

    {{-- SECTION 3: KADALUWARSA --}}
    <section id="secExpired" class="hidden space-y-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="relative flex-1">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input id="searchInput3" type="text" placeholder="Cari ID darah..."
                    class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm focus:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/10">
            </div>
            
            <div class="flex items-center gap-2">
                <label class="text-sm text-neutral-600">Tampilkan:</label>
                <select id="pageSize3" class="rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm focus:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/10">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 border-b border-neutral-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">ID Darah</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Golongan</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Rhesus</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Produk</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Tgl Masuk</th>
                            <th class="px-4 py-3 text-left font-medium text-neutral-700">Tgl Kadaluwarsa</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody3" class="divide-y divide-neutral-100"></tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div id="pageInfo3" class="text-sm text-neutral-600"></div>
            <div id="pagination3" class="flex gap-1"></div>
        </div>
    </section>
</div>

<style>
.tab-btn {
    padding: 0.5rem 1.25rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #525252;
    transition: all 0.2s;
}

.tab-btn:hover {
    background-color: #f5f5f5;
}

.tab-btn.active {
    background-color: #171717;
    color: white;
}
</style>

<script>
// Data dari controller
const dataAvail = @json($rows ?? []);
const dataHistory = @json($historyRows ?? []);
const dataExpired = @json($expiredRows ?? []);

console.log('Data Tersedia:', dataAvail.length);
console.log('Data Keluar:', dataHistory.length);
console.log('Data Kadaluwarsa:', dataExpired.length);

// Tab Management
function switchTab(tab) {
    document.querySelectorAll('[id^="sec"]').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    if (tab === 'avail') {
        document.getElementById('secAvail').classList.remove('hidden');
        document.getElementById('btnAvail').classList.add('active');
        renderTable1();
    } else if (tab === 'unavail') {
        document.getElementById('secUnavail').classList.remove('hidden');
        document.getElementById('btnUnavail').classList.add('active');
        renderTable2();
    } else if (tab === 'expired') {
        document.getElementById('secExpired').classList.remove('hidden');
        document.getElementById('btnExpired').classList.add('active');
        renderTable3();
    }
}

document.getElementById('btnAvail').addEventListener('click', () => switchTab('avail'));
document.getElementById('btnUnavail').addEventListener('click', () => switchTab('unavail'));
document.getElementById('btnExpired').addEventListener('click', () => switchTab('expired'));

// TABLE 1: TERSEDIA
let page1 = 1, size1 = 10, search1 = '';

function renderTable1() {
    const filtered = dataAvail.filter(row => {
        if (!search1) return true;
        const s = search1.toLowerCase();
        return (row.id_darah || '').toLowerCase().includes(s) ||
               (row.komponen || '').toLowerCase().includes(s);
    });
    
    const total = filtered.length;
    const pages = Math.ceil(total / size1) || 1;
    page1 = Math.min(page1, pages);
    
    const start = (page1 - 1) * size1;
    const slice = filtered.slice(start, start + size1);
    
    const tbody = document.getElementById('tableBody');
    if (slice.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">Tidak ada data tersedia</td></tr>';
    } else {
        tbody.innerHTML = slice.map(row => `
            <tr class="hover:bg-neutral-50">
                <td class="px-4 py-3">${row.id_darah || '-'}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-pink-50 text-sm font-semibold text-pink-600">
                        ${row.gol_darah || '-'}
                    </span>
                </td>
                <td class="px-4 py-3">${row.rhesus || '-'}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">${row.komponen || '-'}</span>
                </td>
                <td class="px-4 py-3">${row.tgl_masuk || '-'}</td>
                <td class="px-4 py-3">${row.tgl_kadaluarsa || '-'}</td>
            </tr>
        `).join('');
    }
    
    document.getElementById('pageInfo').textContent = `Menampilkan ${start + 1}-${Math.min(start + size1, total)} dari ${total} data`;
    
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    
    if (pages > 1) {
        for (let i = 1; i <= pages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `px-3 py-1 rounded-md text-sm ${i === page1 ? 'bg-neutral-900 text-white' : 'bg-white border border-neutral-200 text-neutral-700 hover:bg-neutral-50'}`;
            btn.addEventListener('click', () => {
                page1 = i;
                renderTable1();
            });
            pagination.appendChild(btn);
        }
    }
}

document.getElementById('searchInput').addEventListener('input', (e) => {
    search1 = e.target.value;
    page1 = 1;
    renderTable1();
});

document.getElementById('pageSize').addEventListener('change', (e) => {
    size1 = parseInt(e.target.value);
    page1 = 1;
    renderTable1();
});

// TABLE 2: KELUAR
let page2 = 1, size2 = 10, search2 = '';

function renderTable2() {
    const filtered = dataHistory.filter(row => {
        if (!search2) return true;
        const s = search2.toLowerCase();
        return (row.id || '').toLowerCase().includes(s);
    });
    
    const total = filtered.length;
    const pages = Math.ceil(total / size2) || 1;
    page2 = Math.min(page2, pages);
    
    const start = (page2 - 1) * size2;
    const slice = filtered.slice(start, start + size2);
    
    const tbody = document.getElementById('tableBody2');
    if (slice.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">Tidak ada data</td></tr>';
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
                <td class="px-4 py-3">${row.penerima || '-'}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex rounded-md px-2 py-1 text-xs font-medium ${row.status === 'Dispensed' ? 'bg-green-50 text-green-700' : 'bg-neutral-50 text-neutral-700'}">${row.status || '-'}</span>
                </td>
            </tr>
        `).join('');
    }
    
    document.getElementById('pageInfo2').textContent = `Menampilkan ${start + 1}-${Math.min(start + size2, total)} dari ${total} data`;
    
    const pagination = document.getElementById('pagination2');
    pagination.innerHTML = '';
    
    if (pages > 1) {
        for (let i = 1; i <= pages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `px-3 py-1 rounded-md text-sm ${i === page2 ? 'bg-neutral-900 text-white' : 'bg-white border border-neutral-200 text-neutral-700 hover:bg-neutral-50'}`;
            btn.addEventListener('click', () => {
                page2 = i;
                renderTable2();
            });
            pagination.appendChild(btn);
        }
    }
}

document.getElementById('searchInput2').addEventListener('input', (e) => {
    search2 = e.target.value;
    page2 = 1;
    renderTable2();
});

document.getElementById('pageSize2').addEventListener('change', (e) => {
    size2 = parseInt(e.target.value);
    page2 = 1;
    renderTable2();
});

// TABLE 3: KADALUWARSA
let page3 = 1, size3 = 10, search3 = '';

function renderTable3() {
    const filtered = dataExpired.filter(row => {
        if (!search3) return true;
        const s = search3.toLowerCase();
        return (row.id || '').toLowerCase().includes(s);
    });
    
    const total = filtered.length;
    const pages = Math.ceil(total / size3) || 1;
    page3 = Math.min(page3, pages);
    
    const start = (page3 - 1) * size3;
    const slice = filtered.slice(start, start + size3);
    
    const tbody = document.getElementById('tableBody3');
    if (slice.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">Tidak ada data kadaluwarsa</td></tr>';
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
                <td class="px-4 py-3">${row.masuk || '-'}</td>
                <td class="px-4 py-3">
                    <span class="text-red-600 font-medium">${row.exp || '-'}</span>
                </td>
            </tr>
        `).join('');
    }
    
    document.getElementById('pageInfo3').textContent = `Menampilkan ${start + 1}-${Math.min(start + size3, total)} dari ${total} data`;
    
    const pagination = document.getElementById('pagination3');
    pagination.innerHTML = '';
    
    if (pages > 1) {
        for (let i = 1; i <= pages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `px-3 py-1 rounded-md text-sm ${i === page3 ? 'bg-neutral-900 text-white' : 'bg-white border border-neutral-200 text-neutral-700 hover:bg-neutral-50'}`;
            btn.addEventListener('click', () => {
                page3 = i;
                renderTable3();
            });
            pagination.appendChild(btn);
        }
    }
}

document.getElementById('searchInput3').addEventListener('input', (e) => {
    search3 = e.target.value;
    page3 = 1;
    renderTable3();
});

document.getElementById('pageSize3').addEventListener('change', (e) => {
    size3 = parseInt(e.target.value);
    page3 = 1;
    renderTable3();
});

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    switchTab('avail');
});
</script>
@endsection
