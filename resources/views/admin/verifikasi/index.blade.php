{{-- resources/views/admin/verifikasi/index.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        @if ($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="space-y-1">
            <h1 class="text-2xl font-semibold md:text-3xl">Verifikasi Pemesanan</h1>
            <p class="text-sm text-neutral-500">Kelola dan verifikasi permintaan darah dari rumah sakit</p>
        </div>

        @php
            if (!function_exists('blood_pill')) {
                function blood_pill($g)
                {
                    $isRed = in_array($g, ['A+', 'A-', 'AB+', 'AB-']);
                    $cls = $isRed
                        ? 'bg-rose-50 text-rose-600 border-rose-100'
                        : 'bg-sky-50 text-sky-700 border-sky-100';
                    return '<span class="' .
                        $cls .
                        ' inline-flex h-6 items-center justify-center rounded-full border px-2 text-xs font-semibold">' .
                        $g .
                        '</span>';
                }
            }

            if (!function_exists('product_pill')) {
                function product_pill($p)
                {
                    return '<span class="inline-flex items-center rounded-full border border-sky-200 bg-sky-50 px-2.5 py-0.5 text-xs text-sky-700">' .
                        $p .
                        '</span>';
                }
            }
        @endphp

        @php
            // --- Filter state & mapping status ---
            $q = request('q', '');
            $statusQ = request('status', '');
            $golQ = request('gol', '');
            $perPage = (int) request('per_page', 10);

            $statusMap = [
                'approved' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                'pending' => 'bg-amber-50 text-amber-700 border border-amber-200',
                'rejected' => 'bg-rose-50 text-rose-700 border border-rose-200',
            ];
        @endphp

        {{-- Toolbar (form GET) --}}
        <form id="filterForm" method="GET" class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                    <svg class="size-5 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                            d="m21 21-4.3-4.3M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" />
                    </svg>
                </span>
                <input name="q" value="{{ $q }}" type="text"
                    class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-3 text-sm placeholder-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-900/10"
                    placeholder="Cari nama pasien atau rumah sakit..." />
            </div>

            {{-- Filter dropdown --}}
            <div class="relative">
                <button type="button" id="filterBtn"
                    class="inline-flex items-center justify-center rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm hover:bg-neutral-50">
                    <svg class="size-5 text-neutral-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                            d="M3 6h18M6 12h12M10 18h4" />
                    </svg>
                </button>
                <div id="filterMenu"
                    class="absolute right-0 z-20 mt-2 hidden w-64 rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-neutral-500">Status</label>
                            <select id="statusSelect"
                                class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                                <option value="" {{ $statusQ === '' ? 'selected' : '' }}>Semua</option>
                                <option value="approved" {{ $statusQ === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ $statusQ === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ $statusQ === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-neutral-500">Golongan Darah</label>
                            <select id="golSelect"
                                class="mt-1 w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm">
                                @php $gOpts = [''=>'Semua','A+'=>'A+','A-'=>'A-','B+'=>'B+','B-'=>'B-','AB+'=>'AB+','AB-'=>'AB-','O+'=>'O+','O-'=>'O-']; @endphp
                                @foreach ($gOpts as $val => $lab)
                                    <option value="{{ $val }}" {{ $golQ === $val ? 'selected' : '' }}>
                                        {{ $lab }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="button" id="resetBtn"
                                class="text-sm text-neutral-600 hover:underline">Reset</button>
                            <button type="button" id="applyBtn"
                                class="rounded-lg bg-neutral-900 px-3 py-1.5 text-sm text-white hover:bg-neutral-800">Terapkan</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hidden inputs --}}
            <input type="hidden" name="status" id="statusInput" value="{{ $statusQ }}">
            <input type="hidden" name="gol" id="golInput" value="{{ $golQ }}">
            <input type="hidden" name="per_page" id="perPageInput" value="{{ $perPage }}">

            {{-- Page size --}}
            <div class="flex items-center gap-2 sm:ml-auto">
                <label for="pageSize" class="text-sm text-neutral-600">Baris:</label>
                <select id="pageSize" class="rounded-xl border border-neutral-200 bg-white px-2 py-2 text-sm">
                    @foreach ([5, 10, 20] as $opt)
                        <option value="{{ $opt }}" {{ $perPage === $opt ? 'selected' : '' }}>{{ $opt }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        {{-- TABLE (≥ md) --}}
        <div class="hidden overflow-hidden rounded-2xl border border-neutral-200 bg-white md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-neutral-50 text-neutral-600">
                        <tr class="text-left">
                            <th class="px-4 py-3 font-medium">Nama Pasien</th>
                            <th class="px-4 py-3 font-medium">RS Pemesan</th>
                            <th class="px-4 py-3 font-medium">Golongan Darah</th>
                            <th class="px-4 py-3 font-medium">Rhesus</th>
                            <th class="px-4 py-3 font-medium">Tanggal Pemesanan</th>
                            <th class="px-4 py-3 font-medium">Produk Darah</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemesanan as $o)
                            @php
                                $statusClass =
                                    $statusMap[$o->status] ??
                                    'bg-neutral-100 text-neutral-700 border border-neutral-200';

                                $tglBaris = \Illuminate\Support\Carbon::parse(
                                    optional($o->verifikasiTerakhir)->tanggal_permintaan ?? $o->created_at,
                                )->toDateString();

                                // Payload lengkap utk modal
                                $payload = [
                                    'id' => $o->id,
                                    'status' => $o->status,
                                    'tanggal_pemesanan' =>
                                        optional($o->tanggal_pemesanan)->toDateString() ??
                                        ($o->tanggal_pemesanan ?? null),
                                    'tanggal_permintaan' =>
                                        optional($o->tanggal_permintaan)->toDateString() ??
                                        ($o->tanggal_permintaan ?? null),

                                    // Identitas pasien & RS
                                    'nama_pasien' => $o->nama_pasien,
                                    'rs_pemesan' => $o->rs_pemesan,
                                    'jenis_kelamin' => $o->jenis_kelamin, // L/P
                                    'nama_dokter' => $o->nama_dokter,
                                    'email' => $o->email,
                                    'nomor_telepon' => $o->nomor_telepon,
                                    'no_regis_rs' => $o->no_regis_rs,

                                    // Kebutuhan darah
                                    'gol_darah' => $o->gol_darah,
                                    'rhesus' => $o->rhesus,
                                    'produk' => $o->produk,
                                    'jumlah_kantong' => $o->jumlah_kantong,
                                    'alasan_tambahan' => $o->alasan_tambahan,

                                    // Alasan & pemeriksaan
                                    'alasan_transfusi' => $o->alasan_transfusi,
                                    'gejala_transfusi' => $o->gejala_transfusi,
                                    'cek_transfusi' => (bool) $o->cek_transfusi,

                                    // Serologi
                                    'nama_suami_istri' => $o->nama_suami_istri,
                                    'diagnosa_klinik' => $o->diagnosa_klinik,
                                    'pernah_serologi' => $o->pernah_serologi, // 'Ya' / 'Tidak'
                                    'lokasi_serologi' => $o->lokasi_serologi,
                                    'tanggal_serologi' =>
                                        optional($o->tanggal_serologi)->toDateString() ??
                                        ($o->tanggal_serologi ?? null),
                                    'tanggal_transfusi' =>
                                        optional($o->tanggal_transfusi)->toDateString() ??
                                        ($o->tanggal_transfusi ?? null),
                                    'hasil_serologi' => $o->hasil_serologi,

                                    'tanggal' => $tglBaris, // fallback tampilan
                                ];
                            @endphp

                            <tr class="border-t border-neutral-100 hover:bg-neutral-50/60">
                                <td class="px-4 py-3">{{ $o->nama_pasien }}</td>
                                <td class="px-4 py-3">{{ $o->rs_pemesan }}</td>
                                <td class="px-4 py-3">{!! $o->gol_darah ? blood_pill($o->gol_darah) : '-' !!}</td>
                                <td class="px-4 py-3">{{ $o->rhesus }}</td>
                                <td class="px-4 py-3">{{ \Illuminate\Support\Carbon::parse($tglBaris)->format('d-m-Y') }}
                                </td>
                                <td class="px-4 py-3">{!! $o->produk ? product_pill($o->produk) : '-' !!}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="{{ $statusClass }} inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium">
                                        {{ ucfirst($o->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <button type="button"
                                        class="lihat-detail-btn w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50"
                                        data-action="{{ route('admin.verifikasi.store', $o) }}"
                                        data-payload='@json($payload)'>
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-neutral-500">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- CARDS (mobile) --}}
        <div class="space-y-3 md:hidden">
            @forelse($pemesanan as $o)
                @php
                    $statusClass =
                        $statusMap[$o->status] ?? 'bg-neutral-100 text-neutral-700 border border-neutral-200';
                    $tgl = optional($o->verifikasiTerakhir)->tanggal_permintaan ?? $o->created_at;
                    $tglBaris = \Illuminate\Support\Carbon::parse($tgl)->toDateString();
                    $payloadMobile = [
                        'id' => $o->id,
                        'status' => $o->status,
                        'tanggal' => $tglBaris,
                        'tanggal_pemesanan' =>
                            optional($o->tanggal_pemesanan)->toDateString() ?? ($o->tanggal_pemesanan ?? null),
                        'tanggal_permintaan' =>
                            optional($o->tanggal_permintaan)->toDateString() ?? ($o->tanggal_permintaan ?? null),
                        'nama_pasien' => $o->nama_pasien,
                        'rs_pemesan' => $o->rs_pemesan,
                        'jenis_kelamin' => $o->jenis_kelamin,
                        'nama_dokter' => $o->nama_dokter,
                        'email' => $o->email,
                        'nomor_telepon' => $o->nomor_telepon,
                        'no_regis_rs' => $o->no_regis_rs,
                        'gol_darah' => $o->gol_darah,
                        'rhesus' => $o->rhesus,
                        'produk' => $o->produk,
                        'jumlah_kantong' => $o->jumlah_kantong,
                        'alasan_transfusi' => $o->alasan_transfusi,
                        'alasan_tambahan' => $o->alasan_tambahan,
                        'gejala_transfusi' => $o->gejala_transfusi,
                        'cek_transfusi' => (bool) $o->cek_transfusi,
                        'nama_suami_istri' => $o->nama_suami_istri,
                        'diagnosa_klinik' => $o->diagnosa_klinik,
                        'pernah_serologi' => $o->pernah_serologi,
                        'lokasi_serologi' => $o->lokasi_serologi,
                        'tanggal_serologi' =>
                            optional($o->tanggal_serologi)->toDateString() ?? ($o->tanggal_serologi ?? null),
                        'tanggal_transfusi' =>
                            optional($o->tanggal_transfusi)->toDateString() ?? ($o->tanggal_transfusi ?? null),
                        'hasil_serologi' => $o->hasil_serologi,
                    ];
                @endphp

                <div class="rounded-2xl border border-neutral-200 bg-white p-4">
                    <div class="flex items-start justify-between">
                        <p class="font-medium">{{ $o->nama_pasien }}</p>
                        <span
                            class="{{ $statusClass }} inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium">
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
                        <button type="button"
                            class="lihat-detail-btn rounded-lg border border-neutral-200 bg-white px-3 py-1.5 text-xs text-neutral-700 hover:bg-neutral-50"
                            data-action="{{ route('admin.verifikasi.store', $o) }}"
                            data-payload='@json($payloadMobile)'>
                            Lihat detail
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center text-neutral-500">Tidak ada data.</div>
            @endforelse
        </div>

        {{-- Pagination footer --}}
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div class="text-sm text-neutral-600">
                @if ($pemesanan->total() > 0)
                    Menampilkan {{ $pemesanan->firstItem() }}–{{ $pemesanan->lastItem() }} dari {{ $pemesanan->total() }}
                    data
                @else
                    Tidak ada data
                @endif
            </div>
            <div>
                {{ $pemesanan->withQueryString()->links() }}
            </div>
        </div>
    </div>

    {{-- ===== Modal: Detail Pemesanan (sesuai mockup) ===== --}}
    <div id="detailModal" class="fixed inset-0 z-40 hidden items-center justify-center bg-black/20 p-4">
        <div class="w-full max-w-4xl rounded-3xl border border-neutral-200 bg-white shadow-xl">
            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-neutral-100 px-6 py-4">
                <h3 class="text-xl font-semibold">Detail Pemesanan</h3>
                <button type="button" class="dm-close rounded-lg p-1 text-neutral-500 hover:bg-neutral-100">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                            d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5">
                <div class="max-h-[70vh] overflow-auto pr-1 space-y-8">
                    {{-- A. Pasien & RS --}}
                    <section>
                        <h4 class="mb-3 text-sm font-semibold tracking-wide text-neutral-700">A. Pasien & RS</h4>
                        <dl class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm">
                            <dt class="text-neutral-500">Rumah Sakit</dt>
                            <dd id="dm_rs" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Jenis Kelamin</dt>
                            <dd id="dm_jk" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">No. Registrasi</dt>
                            <dd id="dm_no_regis" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Nama Dokter</dt>
                            <dd id="dm_dokter" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Nama Pasien</dt>
                            <dd id="dm_nama" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Suami/Istri</dt>
                            <dd id="dm_suami_istri" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Telepon</dt>
                            <dd id="dm_telp" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Email</dt>
                            <dd id="dm_email" class="font-medium text-neutral-900 break-all">-</dd>
                        </dl>
                    </section>

                    {{-- B. Detail Klinis --}}
                    <section>
                        <h4 class="mb-3 text-sm font-semibold tracking-wide text-neutral-700">B. Detail Klinis</h4>
                        <dl class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm">
                            <dt class="text-neutral-500">Tgl Diperlukan</dt>
                            <dd id="dm_tgl_minta" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Pernah Serologi</dt>
                            <dd id="dm_pernah_serologi" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Diagnosa</dt>
                            <dd id="dm_diagnosa" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Lokasi Serologi</dt>
                            <dd id="dm_lokasi_serologi" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Tgl Serologi</dt>
                            <dd id="dm_tgl_serologi" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Tgl Transfusi</dt>
                            <dd id="dm_tgl_transfusi" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Alasan Transfusi</dt>
                            <dd id="dm_alasan" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Hasil Serologi</dt>
                            <dd id="dm_hasil_serologi" class="font-medium text-neutral-900">-</dd>
                        </dl>
                    </section>

                    {{-- C. Permintaan Darah --}}
                    <section>
                        <h4 class="mb-3 text-sm font-semibold tracking-wide text-neutral-700">C. Permintaan Darah</h4>
                        <dl class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm">
                            <dt class="text-neutral-500">Jenis Darah</dt>
                            <dd id="dm_produk" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Golongan Darah</dt>
                            <dd id="dm_gol" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Rhesus</dt>
                            <dd id="dm_rhesus" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Jumlah Kantong</dt>
                            <dd id="dm_jumlah" class="font-medium text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Alasan Tambahan</dt>
                            <dd id="dm_gejala" class="font-medium text-neutral-900">—</dd>
                            <dt class="text-neutral-500">Cek Transfusi</dt>
                            <dd id="dm_cek" class="font-medium text-neutral-900">-</dd>
                        </dl>
                    </section>

                    {{-- Meta ringkas --}}
                    <section>
                        <dl class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm">
                            <dt class="text-neutral-500">Status Saat Ini</dt>
                            <dd id="dm_status" class="font-semibold text-neutral-900">-</dd>
                            <dt class="text-neutral-500">Tgl. Pemesanan</dt>
                            <dd id="dm_tgl_pesan" class="font-medium text-neutral-900">-</dd>
                        </dl>
                    </section>
                </div>

                {{-- Form POST verifikasi --}}
                <form id="dm_form" method="POST" action="#" class="hidden">
                    @csrf
                    <input type="hidden" name="status" id="dm_status_input" value="">
                    <input type="hidden" name="tanggal_permintaan" id="dm_tanggal_input" value="">
                </form>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between gap-3 border-t border-neutral-100 px-6 py-4">
                <button type="button"
                    class="dm-close rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                    Tutup
                </button>
                <div id="dm_action_buttons" class="flex items-center gap-2">
                    <button type="button" id="dm_reject"
                        class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700 hover:bg-rose-100">
                        Tolak
                    </button>
                    <button type="button" id="dm_approve"
                        class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-100">
                        Terima
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Modal: Konfirmasi ===== --}}
    <div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 p-4">
        <div class="w-full max-w-md rounded-2xl border border-neutral-200 bg-white shadow-xl">
            <div class="px-5 py-4">
                <h4 id="cm_title" class="text-base font-semibold">Konfirmasi</h4>
                <p id="cm_desc" class="mt-1 text-sm text-neutral-600">Apakah Anda yakin?</p>
            </div>
            <div class="flex items-center justify-end gap-2 border-t border-neutral-100 px-5 py-3">
                <button type="button" id="cm_cancel"
                    class="rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50">Batal</button>
                <button type="button" id="cm_ok"
                    class="rounded-lg bg-neutral-900 px-3 py-2 text-sm text-white hover:bg-neutral-800">Ya,
                    lanjutkan</button>
            </div>
        </div>
    </div>

    {{-- ===== JS: filter + modal detail + konfirmasi ===== --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            /* ==== Filter & page size ==== */
            const btn = document.getElementById('filterBtn');
            const menu = document.getElementById('filterMenu');
            const apply = document.getElementById('applyBtn');
            const reset = document.getElementById('resetBtn');
            const statusSelect = document.getElementById('statusSelect');
            const golSelect = document.getElementById('golSelect');
            const statusInput = document.getElementById('statusInput');
            const golInput = document.getElementById('golInput');
            const form = document.getElementById('filterForm');
            const pageSize = document.getElementById('pageSize');
            const perInput = document.getElementById('perPageInput');

            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });
            document.addEventListener('click', (e) => {
                if (!menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden');
            });
            apply.addEventListener('click', () => {
                statusInput.value = statusSelect.value || '';
                golInput.value = golSelect.value || '';
                menu.classList.add('hidden');
                form.submit();
            });
            reset.addEventListener('click', () => {
                statusSelect.value = '';
                golSelect.value = '';
                statusInput.value = '';
                golInput.value = '';
                form.submit();
            });
            pageSize.addEventListener('change', () => {
                perInput.value = pageSize.value;
                form.submit();
            });

            /* ==== Modal Detail ==== */
            const detailModal = document.getElementById('detailModal');
            const confirmModal = document.getElementById('confirmModal');
            const dmCloseBtns = detailModal.querySelectorAll('.dm-close');
            const dmApprove = document.getElementById('dm_approve');
            const dmReject = document.getElementById('dm_reject');
            const dmActionButtons = document.getElementById('dm_action_buttons');
            const dmForm = document.getElementById('dm_form');
            const dmStatusInput = document.getElementById('dm_status_input');
            const dmTanggalInput = document.getElementById('dm_tanggal_input');

            // Elemen detail
            const dm = {
                status: document.getElementById('dm_status'),
                tglPesan: document.getElementById('dm_tgl_pesan'),
                tglMinta: document.getElementById('dm_tgl_minta'),

                nama: document.getElementById('dm_nama'),
                rs: document.getElementById('dm_rs'),
                jk: document.getElementById('dm_jk'),
                dokter: document.getElementById('dm_dokter'),
                noRegis: document.getElementById('dm_no_regis'),
                email: document.getElementById('dm_email'),
                telp: document.getElementById('dm_telp'),

                gol: document.getElementById('dm_gol'),
                rhesus: document.getElementById('dm_rhesus'),
                produk: document.getElementById('dm_produk'),
                jumlah: document.getElementById('dm_jumlah'),

                alasan: document.getElementById('dm_alasan'),
                gejala: document.getElementById('dm_gejala'),
                cek: document.getElementById('dm_cek'),

                suamiIstri: document.getElementById('dm_suami_istri'),
                diagnosa: document.getElementById('dm_diagnosa'),
                pernahSerologi: document.getElementById('dm_pernah_serologi'),
                lokasiSerologi: document.getElementById('dm_lokasi_serologi'),
                tglSerologi: document.getElementById('dm_tgl_serologi'),
                tglTransfusi: document.getElementById('dm_tgl_transfusi'),
                hasilSerologi: document.getElementById('dm_hasil_serologi')
            };

            function fmt(v) {
                if (!v) return '-';
                return v;
            }

            function yaTidak(v) {
                if (v === true) return 'Ya';
                if (v === false) return 'Tidak';
                const s = String(v || '').toLowerCase();
                if (s === 'ya') return 'Ya';
                if (s === 'tidak') return 'Tidak';
                return '-';
            }

            function labelJK(v) {
                if (!v) return '-';
                return v === 'L' ? 'Laki-laki' : (v === 'P' ? 'Perempuan' : v);
            }

            function productLabel(code) {
                const map = {
                    WB: 'WB: Whole Blood',
                    PRC: 'PRC: Packed Red Cell',
                    TC: 'TC: Thrombocyte Concentrate',
                    FFP: 'FFP: Fresh Frozen Plasma',
                    AHF: 'AHF: Anti Hemophilic Factor',
                    LP: 'LP: Leukocyte Poor',
                    TCA: 'TCA: Thrombocyte Apheresis',
                    PK: 'PK: Platelet Kriopresipitat'
                };
                return map[code] || code || '-';
            }

            function jumlahLabel(v) {
                const n = Number(v || 0);
                return n > 0 ? `${n} kantong` : '-';
            }

            function openDetail(payload, actionUrl) {
                try {
                    // Status & tanggal
                    dm.status && (dm.status.textContent = (payload.status ?? '-').toString().replace(/^./, c => c
                        .toUpperCase()));
                    dm.tglPesan && (dm.tglPesan.textContent = fmt(payload.tanggal_pemesanan ?? payload.tanggal));
                    dm.tglMinta && (dm.tglMinta.textContent = fmt(payload.tanggal_permintaan ?? payload.tanggal));

                    // Identitas
                    dm.nama && (dm.nama.textContent = payload.nama_pasien ?? '-');
                    dm.rs && (dm.rs.textContent = payload.rs_pemesan ?? '-');
                    dm.jk && (dm.jk.textContent = labelJK(payload.jenis_kelamin));
                    dm.dokter && (dm.dokter.textContent = payload.nama_dokter ?? '-');
                    dm.noRegis && (dm.noRegis.textContent = payload.no_regis_rs ?? '-');
                    dm.noRekap && (dm.noRekap.textContent = payload.no_rekap_rs ?? '-');
                    dm.email && (dm.email.textContent = payload.email ?? '-');
                    dm.telp && (dm.telp.textContent = payload.nomor_telepon ?? '-');

                    // Kebutuhan darah
                    dm.gol && (dm.gol.textContent = payload.gol_darah ?? '-');
                    dm.rhesus && (dm.rhesus.textContent = payload.rhesus ?? '-');
                    dm.produk && (dm.produk.textContent = productLabel(payload.produk));
                    dm.jumlah && (dm.jumlah.textContent = jumlahLabel(payload.jumlah_kantong));

                    // Alasan & pemeriksaan
                    dm.alasan && (dm.alasan.textContent = payload.alasan_transfusi ?? '-');
                    dm.gejala && (dm.gejala.textContent = (payload.alasan_tambahan ?? '').toString().trim() || '—');
                    dm.cek && (dm.cek.textContent = yaTidak(payload.cek_transfusi));

                    // Serologi
                    dm.suamiIstri && (dm.suamiIstri.textContent = payload.nama_suami_istri ?? '-');
                    dm.diagnosa && (dm.diagnosa.textContent = payload.diagnosa_klinik ?? '-');
                    dm.pernahSerologi && (dm.pernahSerologi.textContent = yaTidak(payload.pernah_serologi));
                    dm.lokasiSerologi && (dm.lokasiSerologi.textContent = payload.lokasi_serologi ?? '-');
                    dm.tglSerologi && (dm.tglSerologi.textContent = fmt(payload.tanggal_serologi));
                    dm.tglTransfusi && (dm.tglTransfusi.textContent = fmt(payload.tanggal_transfusi));
                    dm.hasilSerologi && (dm.hasilSerologi.textContent = payload.hasil_serologi ?? '-');

                    // Set form action & default tanggal_permintaan untuk POST verifikasi
                    dmForm.action = actionUrl || '#';
                    dmTanggalInput.value = (payload.tanggal_permintaan ?? payload.tanggal ?? new Date()
                    .toISOString().slice(0, 10));

                    // HIDE tombol Terima/Tolak jika status sudah approved atau rejected
                    const currentStatus = (payload.status || '').toLowerCase();
                    if (currentStatus === 'approved' || currentStatus === 'rejected') {
                        dmActionButtons.classList.add('hidden');
                    } else {
                        dmActionButtons.classList.remove('hidden');
                    }

                    detailModal.classList.remove('hidden');
                    detailModal.classList.add('flex');
                } catch (e) {
                    console.error('Gagal membuka modal detail:', e);
                    alert('Terjadi kesalahan saat membuka detail.');
                }
            }

            function closeDetail() {
                detailModal.classList.add('hidden');
                detailModal.classList.remove('flex');
            }
            dmCloseBtns.forEach(b => b.addEventListener('click', closeDetail));
            detailModal.addEventListener('click', (e) => {
                if (e.target === detailModal) closeDetail();
            });

            // Bind semua tombol "Lihat detail"
            document.querySelectorAll('.lihat-detail-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const payload = JSON.parse(btn.dataset.payload || '{}');
                    const actionUrl = btn.dataset.action;
                    openDetail(payload, actionUrl);
                });
            });

            /* ==== Modal Konfirmasi ==== */
            const cmTitle = document.getElementById('cm_title');
            const cmDesc = document.getElementById('cm_desc');
            const cmCancel = document.getElementById('cm_cancel');
            const cmOk = document.getElementById('cm_ok');
            let cmNext = null;

            function openConfirm(title, desc, onOk) {
                cmTitle.textContent = title || 'Konfirmasi';
                cmDesc.textContent = desc || 'Apakah Anda yakin?';
                cmNext = onOk || null;
                confirmModal.classList.remove('hidden');
                confirmModal.classList.add('flex');
            }

            function closeConfirm() {
                confirmModal.classList.add('hidden');
                confirmModal.classList.remove('flex');
                cmNext = null;
            }
            cmCancel.addEventListener('click', closeConfirm);
            confirmModal.addEventListener('click', (e) => {
                if (e.target === confirmModal) closeConfirm();
            });
            cmOk.addEventListener('click', () => {
                if (typeof cmNext === 'function') cmNext();
                closeConfirm();
            });

            // Tombol approve / reject → konfirmasi → submit
            dmApprove.addEventListener('click', () => {
                openConfirm(
                    'Setujui Pemesanan',
                    'Anda akan MENYETUJUI pemesanan ini. Lanjutkan?',
                    () => {
                        dmStatusInput.value = 'approved';
                        dmForm.submit();
                    }
                );
            });
            dmReject.addEventListener('click', () => {
                openConfirm(
                    'Tolak Pemesanan',
                    'Anda akan MENOLAK pemesanan ini. Lanjutkan?',
                    () => {
                        dmStatusInput.value = 'rejected';
                        dmForm.submit();
                    }
                );
            });
        });
    </script>

    <style>
        th.sortable:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
@endsection
