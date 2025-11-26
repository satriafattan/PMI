{{-- resources/views/admin/profile/index.blade.php --}}
@extends('layouts.admin')

@section('content')
  <div class="mx-auto max-w-3xl space-y-6">
    {{-- Header --}}
    <div>
      <h1 class="text-2xl font-semibold md:text-3xl">Profil Saya</h1>
      <p class="mt-1 text-sm text-neutral-500">Kelola informasi profil dan keamanan akun Anda</p>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
      <div class="rounded-xl border border-green-200 bg-green-50 p-4">
        <div class="flex items-center gap-3">
          <svg class="h-5 w-5 flex-shrink-0 text-green-600"
               viewBox="0 0 24 24"
               fill="none"
               stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
      </div>
    @endif

    {{-- Profile Card --}}
    <div class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm">
      <form action="{{ route('admin.profile.update') }}"
            method="POST"
            class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Profile Info Header --}}
        <div class="flex items-center gap-4 rounded-xl border border-neutral-200 bg-neutral-50 p-4">
          <div
               class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-2xl font-semibold text-white">
            {{ strtoupper(substr($admin->name, 0, 1)) }}
          </div>
          <div>
            <p class="font-semibold text-neutral-800">{{ $admin->name }}</p>
            <p class="text-sm text-neutral-600">{{ $admin->email }}</p>
            <p class="mt-1 text-xs text-neutral-500">
              Terdaftar sejak {{ $admin->created_at->format('d M Y') }}
            </p>
          </div>
        </div>

        {{-- Section: Informasi Dasar --}}
        <div>
          <h2 class="mb-4 text-lg font-semibold text-neutral-800">Informasi Dasar</h2>

          {{-- Nama --}}
          <div class="mb-4">
            <label for="name"
                   class="mb-1 block text-sm font-medium text-neutral-700">
              Nama Lengkap <span class="text-rose-600">*</span>
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   value="{{ old('name', $admin->name) }}"
                   class="{{ $errors->has('name') ? 'border-rose-300 focus:border-rose-500' : 'border-neutral-200 focus:border-blue-500' }} w-full rounded-xl border-2 bg-white px-4 py-2.5 text-sm outline-none transition-colors"
                   placeholder="Masukkan nama lengkap"
                   required>
            @error('name')
              <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Email --}}
          <div>
            <label for="email"
                   class="mb-1 block text-sm font-medium text-neutral-700">
              Email <span class="text-rose-600">*</span>
            </label>
            <input type="email"
                   id="email"
                   name="email"
                   value="{{ old('email', $admin->email) }}"
                   class="{{ $errors->has('email') ? 'border-rose-300 focus:border-rose-500' : 'border-neutral-200 focus:border-blue-500' }} w-full rounded-xl border-2 bg-white px-4 py-2.5 text-sm outline-none transition-colors"
                   placeholder="admin@example.com"
                   required>
            @error('email')
              <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- Divider --}}
        <div class="border-t border-neutral-200"></div>

        {{-- Section: Keamanan --}}
        <div>
          <h2 class="mb-2 text-lg font-semibold text-neutral-800">Keamanan Akun</h2>
          <p class="mb-4 text-sm text-neutral-500">
            Kosongkan jika tidak ingin mengubah password
          </p>

          {{-- Password Lama --}}
          <div class="mb-4">
            <label for="current_password"
                   class="mb-1 block text-sm font-medium text-neutral-700">
              Password Lama
            </label>
            <div class="relative">
              <input type="password"
                     id="current_password"
                     name="current_password"
                     class="{{ $errors->has('current_password') ? 'border-rose-300 focus:border-rose-500' : 'border-neutral-200 focus:border-blue-500' }} w-full rounded-xl border-2 bg-white px-4 py-2.5 pr-10 text-sm outline-none transition-colors"
                     placeholder="Masukkan password lama">
              <button type="button"
                      onclick="togglePassword('current_password')"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600">
                <svg id="current_password-eye"
                     class="h-5 w-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
            @error('current_password')
              <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Password Baru --}}
          <div class="mb-4">
            <label for="password"
                   class="mb-1 block text-sm font-medium text-neutral-700">
              Password Baru
            </label>
            <div class="relative">
              <input type="password"
                     id="password"
                     name="password"
                     class="{{ $errors->has('password') ? 'border-rose-300 focus:border-rose-500' : 'border-neutral-200 focus:border-blue-500' }} w-full rounded-xl border-2 bg-white px-4 py-2.5 pr-10 text-sm outline-none transition-colors"
                     placeholder="Minimal 8 karakter">
              <button type="button"
                      onclick="togglePassword('password')"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600">
                <svg id="password-eye"
                     class="h-5 w-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
            @error('password')
              <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Konfirmasi Password --}}
          <div>
            <label for="password_confirmation"
                   class="mb-1 block text-sm font-medium text-neutral-700">
              Konfirmasi Password Baru
            </label>
            <div class="relative">
              <input type="password"
                     id="password_confirmation"
                     name="password_confirmation"
                     class="{{ $errors->has('password_confirmation') ? 'border-rose-300 focus:border-rose-500' : 'border-neutral-200 focus:border-blue-500' }} w-full rounded-xl border-2 bg-white px-4 py-2.5 pr-10 text-sm outline-none transition-colors"
                     placeholder="Ulangi password baru">
              <button type="button"
                      onclick="togglePassword('password_confirmation')"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600">
                <svg id="password_confirmation-eye"
                     class="h-5 w-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
            @error('password_confirmation')
              <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Info Box --}}
          <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-4">
            <div class="flex gap-3">
              <svg class="h-5 w-5 flex-shrink-0 text-blue-600"
                   viewBox="0 0 24 24"
                   fill="none"
                   stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div class="text-sm text-blue-800">
                <p class="font-medium">Tips Keamanan:</p>
                <ul class="mt-1 list-inside list-disc space-y-1">
                  <li>Password minimal 8 karakter</li>
                  <li>Gunakan kombinasi huruf, angka, dan simbol</li>
                  <li>Jangan gunakan password yang mudah ditebak</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end gap-3 border-t border-neutral-200 pt-6">
          <button type="submit"
                  class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function togglePassword(fieldId) {
      const field = document.getElementById(fieldId);
      const icon = document.getElementById(`${fieldId}-eye`);

      if (field.type === 'password') {
        field.type = 'text';
        icon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
        `;
      } else {
        field.type = 'password';
        icon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
      }
    }
  </script>
@endsection
