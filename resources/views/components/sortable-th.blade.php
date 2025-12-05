@props(['column', 'label', 'currentSort' => null, 'currentDirection' => 'asc'])

@php
  $isActive = $currentSort === $column;
  $nextDirection = $isActive && $currentDirection === 'asc' ? 'desc' : 'asc';
@endphp

<th {{ $attributes->merge(['class' => 'px-4 py-3 font-medium cursor-pointer select-none hover:bg-neutral-100 transition-colors group']) }}
    data-sort="{{ $column }}"
    data-direction="{{ $nextDirection }}"
    role="button"
    tabindex="0"
    aria-sort="{{ $isActive ? ($currentDirection === 'asc' ? 'ascending' : 'descending') : 'none' }}">
  <div class="flex items-center gap-2">
    <span>{{ $label }}</span>
    <div class="flex flex-col">
      @if ($isActive)
        @if ($currentDirection === 'asc')
          <svg class="h-4 w-4 text-red-600"
               fill="currentColor"
               viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                  d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                  clip-rule="evenodd" />
          </svg>
        @else
          <svg class="h-4 w-4 text-red-600"
               fill="currentColor"
               viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                  clip-rule="evenodd" />
          </svg>
        @endif
      @else
        <svg class="h-4 w-4 text-neutral-400 opacity-0 transition-opacity group-hover:opacity-100"
             fill="currentColor"
             viewBox="0 0 20 20">
          <path
                d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
        </svg>
      @endif
    </div>
  </div>
</th>
