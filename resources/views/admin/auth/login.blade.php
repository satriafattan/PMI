@extends('layouts.app')

<main class="flex min-h-screen items-center justify-center bg-gray-50 px-6 py-12">
  <div class="w-full max-w-md rounded-xl bg-white p-8 shadow-lg">
    <div class="mb-8 text-center">
      <div class="mx-auto mb-3 h-12 w-12 rounded-full bg-red-600 grid place-items-center text-white font-bold">
        ADM
      </div>
      <h1 class="text-2xl font-bold text-gray-800">Login Admin</h1>
      <p class="mt-1 text-sm text-gray-500">Silakan masuk untuk mengelola sistem</p>
    </div>

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

    <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
      @csrf

      {{-- Email --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
          class="mt-1 w-full rounded-lg border border-gray-300 p-2.5 focus:border-red-500 focus:ring-2 focus:ring-red-500" />
      </div>

      {{-- Password --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" required
          class="mt-1 w-full rounded-lg border border-gray-300 p-2.5 focus:border-red-500 focus:ring-2 focus:ring-red-500" />
      </div>

      {{-- Remember Me --}}
      <div class="flex items-center justify-between">
        <label class="flex items-center gap-2 text-sm text-gray-600">
          <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
          Ingat saya
        </label>
      </div>

      {{-- Submit --}}
      <button type="submit"
        class="w-full rounded-lg bg-red-600 px-4 py-2.5 font-medium text-white shadow hover:bg-red-700">
        Masuk
      </button>
    </form>
  </div>
</main>

<x-footer />

