@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-semibold">Rekap Stok</h1>
  <a href="{{ route('admin.rekap-stok.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Tambah</a>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-2">ID Darah</th><th class="p-2">Komponen</th>
        <th class="p-2">Gol/Rh</th><th class="p-2">Masuk</th><th class="p-2">Keluar</th>
        <th class="p-2">Ket</th><th class="p-2">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $it)
      <tr class="border-t">
        <td class="p-2">{{ $it->id_darah }}</td>
        <td class="p-2">{{ $it->komponen }}</td>
        <td class="p-2">{{ $it->gol_darah }}/{{ $it->rhesus }}</td>
        <td class="p-2">{{ $it->tanggal_masuk }}</td>
        <td class="p-2">{{ $it->tanggal_keluar ?? '-' }}</td>
        <td class="p-2">{{ $it->keterangan }}</td>
        <td class="p-2">
          <a class="text-yellow-700" href="{{ route('admin.rekap-stok.edit',$it) }}">Edit</a>
          <form action="{{ route('admin.rekap-stok.destroy',$it) }}" method="POST" class="inline"
                onsubmit="return confirm('Hapus data?')">@csrf @method('DELETE')
            <button class="text-red-600">Hapus</button></form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="mt-4">{{ $items->links() }}</div>
@endsection
