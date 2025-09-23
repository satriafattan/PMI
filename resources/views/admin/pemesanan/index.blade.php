@extends('layouts.admin')
@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Pemesanan Darah</h1>
        <a href="{{ route('admin.pemesanan.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Tambah</a>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">ID</th>
                    <th class="p-2 text-left">Tanggal</th>
                    <th class="p-2 text-left">Pasien</th>
                    <th class="p-2 text-left">Gol/Rh</th>
                    <th class="p-2 text-left">Produk</th>
                    <th class="p-2 text-left">Kantong</th>
                    <th class="p-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $it)
                    <tr class="border-t">
                        <td class="p-2">{{ $it->id }}</td>
                        <td class="p-2">{{ $it->tanggal_pemesanan }}</td>
                        <td class="p-2">{{ $it->nama_pasien }}</td>
                        <td class="p-2">{{ $it->gol_darah }}/{{ $it->rhesus }}</td>
                        <td class="p-2">{{ $it->produk }}</td>
                        <td class="p-2">{{ $it->jumlah_kantong }}</td>
                        <td class="p-2 space-x-2">
                            <a class="text-blue-600" href="{{ route('admin.verifikasi.index') }}">Verifikasi</a>
                            <a class="text-yellow-700" href="{{ route('admin.pemesanan.edit', $it) }}">Edit</a>
                            <form action="{{ route('admin.pemesanan.destroy', $it) }}" method="POST" class="inline"
                                onsubmit="return confirm('Hapus pemesanan?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
@endsection
