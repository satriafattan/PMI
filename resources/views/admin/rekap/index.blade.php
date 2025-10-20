@extends('layouts.admin')
@section('title', 'Stok Darah')

@section('content')
  <div class="px-6 pb-10 pt-6">
    <h1 class="text-2xl font-bold">Stok Darah</h1>

    {{-- Flash --}}
    @if (session('success'))
      <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
        {{ session('success') }}
      </div>
    @endif

    {{-- Cards ringkasan --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5">
        <div class="text-sm text-emerald-700">Unit Aktif (belum keluar)</div>
        <div class="mt-2 text-2xl font-bold text-emerald-800">
          {{ $activeUnits }} <span class="text-base font-medium">unit</span>
        </div>
      </div>
      <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
        <div class="text-sm text-slate-600">Total Unit</div>
        <div class="mt-2 text-2xl font-bold text-slate-800">
          {{ $totalUnits }} <span class="text-base font-medium">unit</span>
        </div>
      </div>
      <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-5">
        <div class="text-sm text-yellow-700">Keluar (bulan ini)</div>
        <div class="mt-2 text-2xl font-bold text-yellow-800">
          {{ $outThisMonth }} <span class="text-base font-medium">unit</span>
        </div>
      </div>
      <div class="rounded-xl border border-red-200 bg-red-50 p-5">
        <div class="text-sm text-red-700">Golongan Terdata</div>
        <div class="mt-2 text-2xl font-bold text-red-800">
          {{ $bloodGroupsCount }} <span class="text-base font-medium">tipe</span>
        </div>
      </div>
    </div>

    {{-- Toolbar --}}
    <div class="mt-8 flex flex-wrap items-center gap-3">
      <a href="{{ route('admin.rekap-stok.create') }}"
         class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">
        Tambah Stok
      </a>

      <form method="GET"
            action="{{ route('admin.rekap-stok.index') }}"
            class="relative">
        <input type="text"
               name="q"
               value="{{ request('q') }}"
               placeholder="Cari ID/Komponen/Keterangan"
               class="h-10 w-72 rounded-lg border border-slate-300 px-3 pr-9 focus:border-slate-400 focus:outline-none">
        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
      </form>

      <div class="ml-auto text-sm text-slate-500">
        Menampilkan {{ $rekap->firstItem() ?? 0 }}–{{ $rekap->lastItem() ?? 0 }} dari {{ $rekap->total() }} data
      </div>
    </div>

    {{-- Table rekap_stok --}}
    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200">
      <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50">
          <tr>
            <th class="px-4 py-3 text-left font-semibold text-slate-700">ID Darah</th>
            <th class="px-4 py-3 text-left font-semibold text-slate-700">Komponen</th>
            <th class="px-4 py-3 text-center font-semibold text-slate-700">Gol.</th>
            <th class="px-4 py-3 text-center font-semibold text-slate-700">Rhesus</th>
            <th class="px-4 py-3 text-center font-semibold text-slate-700">Tgl Masuk</th>
            <th class="px-4 py-3 text-center font-semibold text-slate-700">Tgl Keluar</th>
            <th class="px-4 py-3 text-left font-semibold text-slate-700">Keterangan</th>
            <th class="px-4 py-3 text-center font-semibold text-slate-700">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse ($rekap as $row)
            <tr class="odd:bg-white even:bg-slate-50/50">
              <td class="px-4 py-3 font-medium text-slate-800">{{ $row->id_darah }}</td>
              <td class="px-4 py-3 text-slate-800">{{ $row->komponen }}</td>
              <td class="px-4 py-3 text-center">{{ $row->gol_darah }}</td>
              <td class="px-4 py-3 text-center">{{ $row->rhesus }}</td>
              <td class="px-4 py-3 text-center">{{ optional($row->tanggal_masuk)->format('d M Y') }}</td>
              <td class="px-4 py-3 text-center">
                @if ($row->tanggal_keluar)
                  {{ optional($row->tanggal_keluar)->format('d M Y') }}
                @else
                  <span class="rounded bg-emerald-50 px-2 py-1 text-xs text-emerald-700">Aktif</span>
                @endif
              </td>
              <td class="px-4 py-3">{{ $row->keterangan ?? '-' }}</td>
              <td class="px-4 py-3">
                <div class="flex items-center justify-center gap-2">
                  <a href="{{ route('admin.rekap-stok.edit', $row->id) }}"
                     class="rounded border border-slate-300 px-2 py-1 text-slate-700 hover:bg-slate-100"
                     title="Edit">✏️</a>
                  <form method="POST"
                        action="{{ route('admin.rekap-stok.destroy', $row->id) }}"
                        onsubmit="return confirm('Hapus data ini?')">
                    @csrf @method('DELETE')
                    <button class="rounded border border-slate-300 px-2 py-1 text-red-600 hover:bg-red-50"
                            title="Hapus">🗑️</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8"
                  class="px-4 py-6 text-center text-slate-500">Belum ada data stok darah.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-6">
      {{ $rekap->withQueryString()->links() }}
    </div>
  </div>
@endsection
