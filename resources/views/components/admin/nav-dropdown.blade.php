@props(['icon' => '', 'label' => '', 'items' => []])

@php
  $isActive = false;
  foreach ($items as $item) {
      if (request()->routeIs($item['route'] ?? '')) {
          $isActive = true;
          break;
      }
  }
  $active = $isActive ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-300';
@endphp

<div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }"
     class="space-y-1">
  <button @click="open = !open"
          class="{{ $active }} flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2 text-left">
    <div class="flex items-center gap-3">
      <svg xmlns="http://www.w3.org/2000/svg"
           class="size-5"
           fill="none"
           viewBox="0 0 24 24"
           stroke="currentColor">
        <path d="{{ $icon }}"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.6" />
      </svg>
      <span class="text-sm">{{ $label }}</span>
    </div>
    <svg class="size-4 transition-transform"
         :class="{ 'rotate-180': open }"
         viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor">
      <path stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M19 9l-7 7-7-7" />
    </svg>
  </button>

  <div x-show="open"
       x-collapse
       class="space-y-1 pl-8">
    @foreach ($items as $item)
      @php
        $itemActive = request()->routeIs($item['route'] ?? '');
        $itemClass = $itemActive ? 'bg-slate-700 text-white' : 'hover:bg-slate-800/40 text-slate-300';
        $href = \Illuminate\Support\Facades\Route::has($item['route'] ?? '') ? route($item['route']) : '#';
      @endphp
      <a href="{{ $href }}"
         class="{{ $itemClass }} flex items-center gap-2 rounded-lg px-3 py-2 text-sm">
        {{ $item['label'] ?? '' }}
      </a>
    @endforeach
  </div>
</div>
