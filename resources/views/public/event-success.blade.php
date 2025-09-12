@extends('layouts.app')

@section('content')
  <div class="mx-auto max-w-3xl rounded-lg bg-white px-6 py-16 text-center shadow-lg">
    <h1 class="text-3xl font-extrabold text-green-600">Pengajuan Jadwal Diterima</h1>
    <p class="mt-4 text-lg">Terima kasih, <b class="text-blue-600">{{ $schedule->nama }}</b>. Pengajuan untuk
      <b class="text-blue-600">{{ $schedule->jenis_event }}</b> pada
      <b class="text-blue-600">{{ $schedule->tanggal_event->format('Y-m-d') }}</b> telah kami terima.
    </p>
    <p class="text-md mt-2">Status awal: <span class="font-semibold text-yellow-600">Pending</span>.</p>
    <a href="{{ route('home') }}"
       class="mt-6 inline-block rounded bg-red-600 px-4 py-2 text-white transition hover:bg-red-500">Kembali ke Beranda</a>
  </div>
@endsection
