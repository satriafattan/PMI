{{-- resources/views/components/navbar.blade.php --}}
<header class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">
      
      {{-- Logo --}}
      <a href="/" class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-xl bg-red-600 grid place-items-center text-white font-bold">UDD</div>
        <span class="font-bold">PMI Provinsi Lampung</span>
      </a>

      {{-- Menu desktop --}}
      <nav class="hidden md:flex items-center gap-8 text-sm">
        <a href="{{ url('/') }}" 
           class="{{ request()->is('/') ? 'text-red-600 font-bold' : 'hover:text-red-600 font-bold' }}">
           Beranda
        </a>
        <a href="{{ url('/pemesanan') }}" 
           class="{{ request()->is('pemesanan*') ? 'text-red-600 font-bold' : 'hover:text-red-600 font-bold' }}">
           Pemesanan
        </a>
        <a href="{{ url('/stok') }}" 
           class="{{ request()->is('stok*') ? 'text-red-600 font-bold' : 'hover:text-red-600 font-bold' }}">
           Stok darah
        </a>
        <a href="{{ url('/about') }}" 
           class="{{ request()->is('about*') ? 'text-red-600 font-bold' : 'hover:text-red-600 font-bold' }}">
           Tentang Kami
      
        <a href="{{ url('/jadwal-event') }}" 
           class="{{ request()->is('jadwal-event*') ? 'text-red-600 font-bold' : 'hover:text-red-600 font-bold' }}">
           Penjadwalan Event
       </a>
        <a href="{{ route('admin.login') }}"
         class="bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700">
         Login Admin
        </a>
      </nav>

      {{-- Toggle mobile --}}
      <button id="menuBtn" class="md:hidden p-2 rounded-lg border border-slate-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path fill-rule="evenodd" d="M3.75 5.25a.75.75 0 0 1 .75-.75h15a.75.75 0 0 1 0 1.5H4.5a.75.75 0 0 1-.75-.75Zm0 6a.75.75 0 0 1 .75-.75h15a.75.75 0 0 1 0 1.5H4.5a.75.75 0 0 1-.75-.75Zm0 6a.75.75 0 0 1 .75-.75h15a.75.75 0 0 1 0 1.5H4.5a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"/>
        </svg>
      </button>
    </div>

    {{-- Menu mobile --}}
    <div id="mobileMenu" class="hidden md:hidden pb-4">
      <nav class="grid gap-2 text-sm">
        <a href="{{ url('/') }}" 
           class="px-3 py-2 rounded-lg hover:bg-slate-50 {{ request()->is('/') ? 'bg-red-50 text-red-600' : '' }}">
           Beranda
        </a>
        <a href="{{ url('/pemesanan') }}" 
           class="px-3 py-2 rounded-lg hover:bg-slate-50 {{ request()->is('pemesanan*') ? 'bg-red-50 text-red-600' : '' }}">
           Pemesanan
        </a>
        <a href="{{ url('/stok') }}" 
           class="px-3 py-2 rounded-lg hover:bg-slate-50 {{ request()->is('stok*') ? 'bg-red-50 text-red-600' : '' }}">
           Stok darah
        </a>
        <a href="{{ url('/about') }}" 
           class="px-3 py-2 rounded-lg hover:bg-slate-50 {{ request()->is('about*') ? 'bg-red-50 text-red-600' : '' }}">
           Tentang Kami
        </a>

        <a href="{{ url('/jadwal-event') }}" 
           class="px-3 py-2 rounded-lg hover:bg-slate-50 {{ request()->is('jadwal-event*') ? 'bg-red-50 text-red-600' : '' }}">
           Penjadwalan Event
         
          <a href="{{ route('admin.login') }}"
             class="mt-2 px-3 py-2 rounded-lg bg-red-600 text-white text-center hover:bg-red-700">
             Login Admin
        </a>
      </nav>
    </div>
  </div>
</header>
