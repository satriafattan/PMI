@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Verifikasi Pemesanan</h1>

<div class="bg-white rounded shadow overflow-x-auto">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-2">ID</th><th class="p-2">Pasien</th><th class="p-2">Pemesan</th>
        <th class="p-2">Gol/Rh</th><th class="p-2">Produk</th><th class="p-2">Status</th><th class="p-2">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pemesanan as $p)
      <tr class="border-t">
        <td class="p-2">{{ $p->id }}</td>
        <td class="p-2">{{ $p->nama_pasien }}</td>
        <td class="p-2">{{ $p->nama_pemesan }}</td>
        <td class="p-2">{{ $p->gol_darah }}/{{ $p->rhesus }}</td>
        <td class="p-2">{{ $p->produk }}</td>
        <td class="p-2">{{ optional($p->verifikasi)->status ?? 'belum' }}</td>
        <td class="p-2">
          <form action="{{ route('admin.verifikasi.store',$p) }}" method="POST" class="inline">
            @csrf
            <select name="status" class="border rounded p-1">
              @foreach(['pending','approved','rejected'] as $s)
                <option value="{{ $s }}" @selected(optional($p->verifikasi)->status===$s)>{{ $s }}</option>
              @endforeach
            </select>
            <button class="ml-2 px-2 py-1 bg-blue-600 text-white rounded">Simpan</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="mt-4">{{ $pemesanan->links() }}</div>
@endsection
