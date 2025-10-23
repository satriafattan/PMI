@extends('layouts.admin')

@section('content')
<div class="space-y-6">
      {{-- Header --}}
      <div class="space-y-1">
        <h1 class="text-2xl md:text-3xl font-semibold">Verifikasi Pemesanan</h1>
        <p class="text-sm text-neutral-500">Kelola dan verifikasi permintaan darah dari rumah sakit</p>
      </div>

      @php
        $q       = request('q', '');
        $statusQ = request('status', '');
        $golQ    = request('gol', '');
        $perPage = (int) request('per_page', 10);
        $statusMap = [
          'approved' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
          'pending'  => 'bg-amber-50 text-amber-700 border border-amber-200',
          'rejected' => 'bg-rose-50 text-rose-700 border border-rose-200',
        ];
        function blood_pill($g) {
          $isRed = in_array($g, ['A+','A-','AB+','AB-']);
          $cls = $isRed
            ? 'bg-rose-50 text-rose-600 border-rose-100'
            : 'bg-sky-50 text-sky-700 border-sky-100';
          return '<span class="inline-flex items-center justify-center h-6 px-2 rounded-full text-xs font-semibold border '.$cls.'">'.$g.'</span>';
        }
        function product_pill($p) {
          return '<span class="inline-flex items-center rounded-full border border-sky-200 bg-sky-50 px-2.5 py-0.5 text-xs text-sky-700">'.$p.'</span>';
        }
      @endphp

      {{-- Toolbar (form GET) --}}
      <form id="filterForm" method="GET" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
        <div class="relative flex-1">
          <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
            <svg class="size-5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                    d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/>
            </svg>
          </span>
          <input name="q" value="{{ $q }}" type="text"
            class="w-full rounded-xl border border-neutral-200 bg-white pl-11 pr-3 py-2.5 text-sm placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/10 focus:border-neutral-300"
            placeholder="Cari nama pasien atau rumah sakit..." />
        </div>

        {{-- Filter dropdown --}}
        <div class="relative">
          <button type="button" id="filterBtn"
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
                  <option value=""  {{ $statusQ==='' ? 'selected' : '' }}>Semua</option>
                  <option value="approved" {{ $statusQ==='approved' ? 'selected' : '' }}>Approved</option>
                  <option value="pending"  {{ $statusQ==='pending'  ? 'selected' : '' }}>Pending</option>
                  <option value="rejected" {{ $statusQ==='rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
              </div>
              <div>
                <label class="text-xs font-medium text-neutral-500">Golongan Darah</label>
                <select id="golSelect"
                        class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                  @php $gOpts = [''=>'Semua','A+'=>'A+','A-'=>'A-','B+'=>'B+','B-'=>'B-','AB+'=>'AB+','AB-'=>'AB-','O+'=>'O+','O-'=>'O-']; @endphp
                  @foreach($gOpts as $val=>$lab)
                    <option value="{{ $val }}" {{ $golQ===$val ? 'selected' : '' }}>{{ $lab }}</option>
                  @endforeach
                </select>
              </div>
              <div class="flex items-center justify-between">
                <button type="button" id="resetBtn" class="text-sm text-neutral-600 hover:underline">Reset</button>
                <button type="button" id="applyBtn" class="rounded-lg bg-neutral-900 text-white text-sm px-3 py-1.5 hover:bg-neutral-800">Terapkan</button>
              </div>
            </div>
          </div>
        </div>

        {{-- Hidden inputs untuk filter --}}
        <input type="hidden" name="status" id="statusInput" value="{{ $statusQ }}">
        <input type="hidden" name="gol"    id="golInput"    value="{{ $golQ }}">
        <input type="hidden" name="per_page" id="perPageInput" value="{{ $perPage }}">

        {{-- Page size --}}
        <div class="flex items-center gap-2 sm:ml-auto">
          <label for="pageSize" class="text-sm text-neutral-600">Baris:</label>
          <select id="pageSize" class="rounded-xl border border-neutral-200 bg-white px-2 py-2 text-sm">
            @foreach([5,10,20] as $opt)
              <option value="{{ $opt }}" {{ $perPage===$opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
          </select>
         
        </div>
      </form>

  {{-- TABLE (≥ md) --}}
  <div class="hidden md:block rounded-2xl border border-neutral-200 bg-white overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-50 text-neutral-600">
          <tr class="text-left">
            <th class="px-4 py-3 font-medium">Nama Pasien</th>
            <th class="px-4 py-3 font-medium">RS Pemesan</th>
            <th class="px-4 py-3 font-medium">Golongan Darah</th>
            <th class="px-4 py-3 font-medium">Tanggal Permintaan</th>
            <th class="px-4 py-3 font-medium">Produk Darah</th>
            <th class="px-4 py-3 font-medium">Status</th>
            <th class="px-4 py-3 font-medium">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($pemesanan as $o)
          @php
            $statusClass = $statusMap[$o->status] ?? 'bg-neutral-100 text-neutral-700 border border-neutral-200';
            $tgl = optional($o->verifikasiTerakhir)->tanggal_permintaan ?? $o->tanggal_pemesanan;
          @endphp
          <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
            <td class="px-4 py-3">{{ $o->nama_pasien }}</td>
            <td class="px-4 py-3">{{ $o->rs_pemesan }}</td>
            <td class="px-4 py-3">{!! $o->gol_darah ? blood_pill($o->gol_darah) : '-' !!}</td>
            <td class="px-4 py-3">{{ \Illuminate\Support\Carbon::parse($tgl)->format('d-m-Y') }}</td>
            <td class="px-4 py-3">{!! $o->produk ? product_pill($o->produk) : '-' !!}</td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
                {{ ucfirst($o->status) }}
              </span>
            </td>
            <td class="px-4 py-3">
              @if($o->status === 'pending')
                <div class="flex items-center gap-2">
                  {{-- APPROVE --}}
                  <form method="POST" action="{{ route('admin.verifikasi.store', $o) }}">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button class="px-3 py-1.5 text-xs rounded-lg border border-blue-200 text-blue-700 bg-blue-50 hover:bg-blue-100">
                      Terima
                    </button>
                  </form>
                  {{-- REJECT --}}
                  <form method="POST" action="{{ route('admin.verifikasi.store', $o) }}">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button class="px-3 py-1.5 text-xs rounded-lg border border-rose-200 text-rose-700 bg-rose-50 hover:bg-rose-100">
                      Tolak
                    </button>
                  </form>
                </div>
              @elseif($o->status === 'approved')
                <span class="inline-flex items-center rounded-lg bg-neutral-100 text-neutral-600 px-3 py-1 text-xs">Disetujui</span>
              @else
                <span class="inline-flex items-center rounded-lg bg-neutral-100 text-neutral-600 px-3 py-1 text-xs">Ditolak</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- CARDS (mobile) --}}
  <div class="md:hidden space-y-3">
    @forelse($pemesanan as $o)
      @php
        $statusClass = $statusMap[$o->status] ?? 'bg-neutral-100 text-neutral-700 border border-neutral-200';
        $tgl = optional($o->verifikasiTerakhir)->tanggal_permintaan ?? $o->tanggal_pemesanan;
      @endphp
      <div class="rounded-2xl border border-neutral-200 bg-white p-4">
        <div class="flex items-start justify-between">
          <p class="font-medium">{{ $o->nama_pasien }}</p>
          <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
            {{ ucfirst($o->status) }}
          </span>
        </div>
        <p class="text-xs text-neutral-500">{{ $o->rs_pemesan }}</p>
        <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
          <div class="text-neutral-500">Golongan</div>
          <div>{!! $o->gol_darah ? blood_pill($o->gol_darah) : '-' !!}</div>

          <div class="text-neutral-500">Tanggal</div>
          <div>{{ \Illuminate\Support\Carbon::parse($tgl)->format('d-m-Y') }}</div>

          <div class="text-neutral-500">Produk</div>
          <div>{!! $o->produk ? product_pill($o->produk) : '-' !!}</div>
        </div>

        <div class="mt-3">
          @if($o->status === 'pending')
            <div class="flex items-center gap-2">
              <form method="POST" action="{{ route('admin.verifikasi.store', $o) }}">
                @csrf
                <input type="hidden" name="status" value="approved">
                <button class="px-3 py-1.5 text-xs rounded-lg border border-blue-200 text-blue-700 bg-blue-50 hover:bg-blue-100">
                  Terima
                </button>
              </form>
              <form method="POST" action="{{ route('admin.verifikasi.store', $o) }}">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <button class="px-3 py-1.5 text-xs rounded-lg border border-rose-200 text-rose-700 bg-rose-50 hover:bg-rose-100">
                  Tolak
                </button>
              </form>
            </div>
          @elseif($o->status === 'approved')
            <span class="inline-flex items-center rounded-lg bg-neutral-100 text-neutral-600 px-3 py-1 text-xs">Disetujui</span>
          @else
            <span class="inline-flex items-center rounded-lg bg-neutral-100 text-neutral-600 px-3 py-1 text-xs">Ditolak</span>
          @endif
        </div>
      </div>
    @empty
      <div class="text-center text-neutral-500">Tidak ada data.</div>
    @endforelse
  </div>

  {{-- Pagination footer --}}
  <div class="flex flex-col sm:flex-row gap-3 sm:items-center justify-between">
    <div class="text-sm text-neutral-600">
      @if($pemesanan->total() > 0)
        Menampilkan {{ $pemesanan->firstItem() }}–{{ $pemesanan->lastItem() }} dari {{ $pemesanan->total() }} data
      @else
        Tidak ada data
      @endif
    </div>
    <div>
      {{ $pemesanan->withQueryString()->links() }}
    </div>
  </div>
</div>

{{-- ===== Vanilla JS kecil (dropdown & filter) ===== --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const btn   = document.getElementById('filterBtn');
    const menu  = document.getElementById('filterMenu');
    const apply = document.getElementById('applyBtn');
    const reset = document.getElementById('resetBtn');
    const statusSelect = document.getElementById('statusSelect');
    const golSelect    = document.getElementById('golSelect');

    const statusInput = document.getElementById('statusInput');
    const golInput    = document.getElementById('golInput');
    const form        = document.getElementById('filterForm');

    btn.addEventListener('click', (e) => { e.stopPropagation(); menu.classList.toggle('hidden'); });
    document.addEventListener('click', (e) => { if (!menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden'); });

    apply.addEventListener('click', () => {
      statusInput.value = statusSelect.value || '';
      golInput.value    = golSelect.value || '';
      menu.classList.add('hidden');
      form.submit();
    });

    reset.addEventListener('click', () => {
      statusSelect.value = '';
      golSelect.value    = '';
      statusInput.value  = '';
      golInput.value     = '';
      form.submit();
    });

    // per_page handler
    const pageSize = document.getElementById('pageSize');
    const perInput = document.getElementById('perPageInput');
    pageSize.addEventListener('change', () => {
      perInput.value = pageSize.value;
      form.submit();
    });
  });
</script>

<style>
  th.sortable:hover { background-color: rgba(0,0,0,0.02); }
</style>
@endsection
