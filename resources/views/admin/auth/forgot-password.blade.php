@extends('layouts.app')

<main class="flex min-h-screen items-center justify-center bg-gray-50 px-6 py-12">
  <div class="w-full max-w-md rounded-xl bg-white p-8 shadow-lg">
    <div class="mb-8 text-center">
      <div class="mx-auto mb-3">
        <img src="{{ asset('images/simphony-logo.png') }}"
             alt="Logo SIMPHONY"
             class="mx-auto h-32 w-32 object-contain">
      </div>
      <h1 class="text-2xl font-bold text-gray-800">Lupa Password</h1>
      <p class="mt-1 text-sm text-gray-500">Masukkan email Anda untuk reset password</p>
    </div>

    {{-- Success message --}}
    @if (session('success'))
      <div class="mb-6 rounded bg-green-100 p-3 text-sm text-green-700">
        {{ session('success') }}
      </div>
    @endif

    {{-- Error message --}}
    @if ($errors->any())
      <div class="mb-6 rounded bg-red-100 p-3 text-sm text-red-700">
        <ul class="list-disc pl-4">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST"
          action="{{ route('admin.forgot-password.submit') }}"
          class="space-y-5">
      @csrf

      {{-- Email --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email"
               name="email"
               value="{{ old('email') }}"
               required
               autofocus
               class="mt-1 w-full rounded-lg border border-gray-300 p-2.5 focus:border-red-500 focus:ring-2 focus:ring-red-500" />
        <p class="mt-1 text-xs text-gray-500">Kami akan mengirimkan link reset password ke email Anda</p>
      </div>

      {{-- Submit --}}
      <button type="submit"
              class="w-full rounded-lg bg-red-600 px-4 py-2.5 font-medium text-white shadow hover:bg-red-700">
        Kirim Link Reset Password
      </button>

      {{-- Back to Login --}}
      <div class="text-center">
        <a href="{{ route('admin.login') }}"
           class="text-sm text-red-600 hover:text-red-700">
          ← Kembali ke Login
        </a>
      </div>
    </form>
  </div>
</main>
