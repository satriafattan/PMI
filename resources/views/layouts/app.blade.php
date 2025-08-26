<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? config('app.name') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('menuBtn');
    const menu = document.getElementById('mobileMenu');
    if (btn && menu) {
      btn.addEventListener('click', () => menu.classList.toggle('hidden'));
    }
  });
</script>

  <main class="max-w-12xl mx-auto p-4">
    @if(session('success'))
      <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif
    {{ $slot ?? '' }}
    @yield('content')
  </main>
  
</body>
</html>
