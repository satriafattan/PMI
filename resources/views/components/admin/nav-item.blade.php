@props(['route', 'icon' => ''])

@php
  $isActive = request()->routeIs($route);
  $active   = $isActive ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-300';

  // Safety: kalau nama rute salah, jangan fatal error
  $href = \Illuminate\Support\Facades\Route::has($route) ? route($route) : '#';
@endphp

<a href="{{ $href }}" class="px-3 py-2 rounded-lg flex items-center gap-3 {{ $active }}">
  <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path d="{{ $icon }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"/>
  </svg>
  <span class="text-sm">{{ $slot }}</span>
</a>
