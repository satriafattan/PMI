<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport"
        content="width=device-width, initial-scale=1">
  <title>{{ $title ?? config('app.name') }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @stack('styles')
</head>

<body class="bg-white">

  <main>
    @if (session('success'))
      <div class="mx-auto mb-4 max-w-7xl rounded border border-green-200 bg-green-50 px-4 py-2 text-green-800">
        {{ session('success') }}
      </div>
    @endif
    {{ $slot ?? '' }}
    @yield('content')
  </main> 

  {{-- Custom Scripts --}}
  @stack('scripts')

</body>

</html>
