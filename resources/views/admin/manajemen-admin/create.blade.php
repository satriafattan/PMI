{{-- resources/views/admin/admins/create.blade.php --}}
@extends('layouts.admin')

@section('content')
  <div class="mx-auto max-w-2xl space-y-6">
    {{-- Header --}}
    <div>
      <a href="{{ route('admin.admins.index') }}"
         class="mb-4 inline-flex items-center text-sm text-neutral-600 hover:text-neutral-800">
        <svg class="mr-2 h-4 w-4"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Daftar Admin
      </a>
      <h1 class="text-2xl font-semibold md:text-3xl">Tambah Admin Baru</h1>
      <p class="mt-1 text-sm text-neutral-500">Buat akun administrator baru untuk sistem</p>
    </div>

    {{-- Form Card --}}
    <div class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm">
      <form action="{{ route('admin.admins.store') }}"
            method="POST"
            class="space-y-5">
        @csrf

        {{-- Nama --}}
        <div>
          <label for="name"
                 class="mb-1 block text-sm font-medium text-neutral-700">
            Nama Lengkap <span class="text-rose-600">*</span>
          </label>
          <input type="text"
                 id="name"
                 name="name"
                 value="{{ old('name') }}"
                 class="{{ $errors->has('name') ? 'border-rose-300 focus:border-rose-500' : 'border-neutral-200 focus:border-blue-500' }} w-full rounded-xl border-2 bg-white px-4 py-2.5 text-sm outline-none transition-colors"
                 placeholder="Masukkan nama lengkap"
                 required
                 autofocus>
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
                 value="{{ old('email') }}"
                 class="{{ $errors->has('email') ? 'border-rose-300 focus:border-rose-500' : 'border-neutral-200 focus:border-blue-500' }} w-full rounded-xl border-2 bg-white px-4 py-2.5 text-sm outline-none transition-colors"
                 placeholder="admin@example.com"
                 required>
          @error('email')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
          @enderror
          <p class="mt-1 text-xs text-neutral-500">Email akan digunakan untuk login</p>
        </div>

        {{-- Password --}}
        <div>
          <label for="password"
                 class="mb-1 block text-sm font-medium text-neutral-700">
            Password <span class="text-rose-600">*</span>
          </label>
          <div class="relative">
            <input type="password"
                   id="password"
                   name="password"
                   class="{{ $errors->has('password') ? 'border-rose-300 focus:border-rose-500' : 'border-neutral-200 focus:border-blue-500' }} w-full rounded-xl border-2 bg-white px-4 py-2.5 pr-10 text-sm outline-none transition-colors"
                   placeholder="Minimal 8 karakter"
                   required>
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
          <p class="mt-1 text-xs text-neutral-500">Minimal 8 karakter, gunakan kombinasi huruf dan angka</p>
        </div>

        {{-- Konfirmasi Password --}}
        <div>
          <label for="password_confirmation"
                 class="mb-1 block text-sm font-medium text-neutral-700">
            Konfirmasi Password <span class="text-rose-600">*</span>
          </label>
          <div class="relative">
            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   class="{{ $errors->has('password_confirmation') ? 'border-rose-300 focus:border-rose-500' : 'border-neutral-200 focus:border-blue-500' }} w-full rounded-xl border-2 bg-white px-4 py-2.5 pr-10 text-sm outline-none transition-colors"
                   placeholder="Ulangi password"
                   required>
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

        {{-- Super Admin Checkbox --}}
        <div>
          <label class="flex cursor-pointer items-start gap-3">
            <input type="checkbox"
                   id="is_super_admin"
                   name="is_super_admin"
                   value="1"
                   {{ old('is_super_admin') ? 'checked' : '' }}
                   class="mt-0.5 h-4 w-4 rounded border-neutral-300 text-purple-600 focus:ring-2 focus:ring-purple-500">
            <div class="flex-1">
              <span class="text-sm font-medium text-neutral-700">Jadikan Super Admin</span>
              <p class="mt-0.5 text-xs text-neutral-500">
                Super Admin memiliki akses penuh untuk menambah, mengedit, dan menghapus admin lain
              </p>
            </div>
          </label>
        </div>

        {{-- Info Box --}}
        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">
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
              <p class="mb-1 font-medium">Informasi Penting:</p>
              <ul class="list-inside list-disc space-y-1 text-blue-700">
                <li>Admin baru akan memiliki akses penuh ke dashboard</li>
                <li>Pastikan email yang digunakan valid dan aktif</li>
                <li>Simpan kredensial login dengan aman</li>
              </ul>
            </div>
          </div>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-3 border-t border-neutral-200 pt-4">
          <a href="{{ route('admin.admins.index') }}"
             class="flex-1 rounded-lg border border-neutral-300 px-4 py-2.5 text-center text-sm font-medium text-neutral-700 transition-colors hover:bg-neutral-50">
            Batal
          </a>
          <button type="submit"
                  class="flex-1 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700">
            Simpan Admin
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
