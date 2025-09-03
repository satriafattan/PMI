@props([
  // warna background footer
  'bg' => 'bg-white',
  // paksa full-bleed kalau komponen diletakkan di dalam container max-w
  'bleed' => false,
  // daftar link nav (bisa dioverride dari pemanggil)
  'links' => [
    ['label' => 'Beranda',        'href' => url('/')],
    ['label' => 'Pemesanan',      'href' => url('/pemesanan')],
    ['label' => 'Stok Darah',     'href' => url('/stok')],
    ['label' => 'Tentang Kami',   'href' => url('/about')],
    ['label' => 'Kontak',         'href' => 'https://wa.me/628987311125'],
  ],
])

@php
  $bleedClass = $bleed
    ? 'relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen'
    : '';
@endphp

{{-- FOOTER – Chrome-style minimal, full width --}}
<footer class="border-t border-slate-200 {{ $bg }} w-full {{ $bleedClass }}">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col items-center gap-6">

      {{-- Logo + Title --}}
      <a href="{{ url('/') }}" class="flex items-center gap-2 text-slate-800">
        <div class="h-7 w-7 rounded-lg bg-red-600 grid place-items-center text-white text-xs font-bold">UDD</div>
        <span class="font-medium tracking-tight">PMI Provinsi Lampung</span>
      </a>

      {{-- Navigasi --}}
      <nav class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-[13px] text-slate-600">
        @foreach ($links as $link)
          <a href="{{ $link['href'] }}" class="hover:underline underline-offset-4">{{ $link['label'] }}</a>
        @endforeach
      </nav>

      <div class="w-full h-px bg-slate-200"></div>

      {{-- Info --}}
      <div class="flex flex-col items-center gap-2 text-[12px] text-slate-500">
        <p class="text-center">Indonesia • Unit Donor Darah PMI Provinsi Lampung</p>
        <p class="text-center">© {{ date('Y') }} UDD PMI Provinsi Lampung</p>
      </div>
    </div>
  </div>
</footer>
