@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Riwayat Pemesanan</h1>
<div class="bg-white rounded shadow overflow-x-auto">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-2">ID</th><th class="p-2">Nama</th><th class="p-2">Tanggal</th>
        <th class="p-2">Gol/Rh</th><th class="p-2">Kantong</th><th class="p-2">Produk</th><th class="p-2">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $it)
      <tr class="border-t">
        <td class="p-2">{{ $it->id }}</td>
        <td class="p-2">{{ $it->nama }}</td>
        <td class="p-2">{{ $it->tanggal }}</td>
        <td class="p-2">{{ $it->gol_darah }}/{{ $it->rhesus }}</td>
        <td class="p-2">{{ $it->jumlah_kantong }}</td>
        <td class="p-2">{{ $it->produk }}</td>
        <td class="p-2">{{ $it->aksi }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="mt-4">{{ $items->links() }}</div>
@endsection
