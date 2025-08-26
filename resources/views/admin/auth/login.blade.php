@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
  <h1 class="text-lg font-semibold mb-4">Login Admin</h1>
  <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-3">
    @csrf
    <div>
      <label class="block text-sm">Email</label>
      <input type="email" name="email" class="mt-1 w-full border rounded p-2" value="{{ old('email') }}">
      @error('email')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block text-sm">Password</label>
      <input type="password" name="password" class="mt-1 w-full border rounded p-2">
    </div>
    <label class="inline-flex items-center"><input type="checkbox" name="remember" class="rounded mr-2"> Remember me</label>
    <button class="w-full bg-blue-600 text-white py-2 rounded">Masuk</button>
  </form>
</div>
@endsection
