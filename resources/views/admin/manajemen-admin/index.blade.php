{{-- resources/views/admin/admins/index.blade.php --}}
@extends('layouts.admin')

@section('content')
  @if (session('success'))
    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
      {{ session('success') }}
    </div>
  @endif

  @if (session('error'))
    <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
      {{ session('error') }}
    </div>
  @endif

  <div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-semibold md:text-3xl">Manajemen Admin</h1>
        <p class="mt-1 text-sm text-neutral-500">Kelola akun administrator sistem</p>
      </div>

      @if (auth('admin')->user()->isSuperAdmin())
        <a href="{{ route('admin.admins.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700">
          <svg class="mr-2 h-5 w-5"
               viewBox="0 0 24 24"
               fill="none"
               stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4v16m8-8H4" />
          </svg>
          Tambah Admin
        </a>
      @endif
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
      <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
        <p class="text-xs font-medium text-blue-700">Total Admin</p>
        <div class="mt-2 text-2xl font-semibold text-blue-800">
          {{ $totalAdmins }} <span class="text-base font-medium">akun</span>
        </div>
      </div>
      <div class="rounded-2xl border border-purple-200 bg-purple-50 p-4">
        <p class="text-xs font-medium text-purple-700">Super Admin</p>
        <div class="mt-2 text-2xl font-semibold text-purple-800">
          {{ $superAdmins }} <span class="text-base font-medium">akun</span>
        </div>
      </div>
      <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
        <p class="text-xs font-medium text-emerald-700">Admin Biasa</p>
        <div class="mt-2 text-2xl font-semibold text-emerald-800">
          {{ $regularAdmins }} <span class="text-base font-medium">akun</span>
        </div>
      </div>
    </div>

    {{-- Table Desktop --}}
    <div class="hidden overflow-hidden rounded-2xl border border-neutral-200 bg-white md:block">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-neutral-50 text-neutral-600">
            <tr>
              <th class="w-16 px-4 py-3 text-left">No</th>
              <th class="px-4 py-3 text-left">Nama</th>
              <th class="px-4 py-3 text-left">Email</th>
              <th class="px-4 py-3 text-left">Role</th>
              <th class="px-4 py-3 text-left">Dibuat Pada</th>
              @if (auth('admin')->user()->isSuperAdmin())
                <th class="w-32 px-4 py-3 text-center">Aksi</th>
              @endif
            </tr>
          </thead>
          <tbody class="divide-y divide-neutral-100">
            @forelse ($admins as $index => $admin)
              <tr class="transition-colors hover:bg-neutral-50/50">
                <td class="px-4 py-3 font-medium text-neutral-700">{{ $admins->firstItem() + $index }}</td>
                <td class="px-4 py-3">
                  <div class="flex items-center gap-3">
                    <div
                         class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-purple-600 font-semibold text-white">
                      {{ strtoupper(substr($admin->name, 0, 1)) }}
                    </div>
                    <div>
                      <div class="font-medium text-neutral-800">{{ $admin->name }}</div>
                      @if ($admin->id === auth('admin')->id())
                        <span
                              class="mt-1 inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">
                          Anda
                        </span>
                      @endif
                    </div>
                  </div>
                </td>
                <td class="px-4 py-3 text-neutral-600">{{ $admin->email }}</td>
                <td class="px-4 py-3">
                  @if ($admin->isSuperAdmin())
                    <span
                          class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-1 text-xs font-semibold text-purple-700">
                      SUPER ADMIN
                    </span>
                  @else
                    <span
                          class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                      Admin
                    </span>
                  @endif
                </td>
                <td class="px-4 py-3 text-neutral-600">{{ $admin->created_at->format('d M Y') }}</td>
                @if (auth('admin')->user()->isSuperAdmin())
                  <td class="px-4 py-3">
                    <div class="flex items-center justify-center gap-2">
                      <a href="{{ route('admin.admins.edit', $admin) }}"
                         class="inline-flex items-center justify-center rounded-lg p-2 text-blue-600 transition-colors hover:bg-blue-50"
                         title="Edit">
                        <svg class="h-4 w-4"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor">
                          <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                      </a>

                      @if ($admin->id !== auth('admin')->id() && !$admin->isSuperAdmin())
                        <button onclick="confirmDelete({{ $admin->id }}, '{{ $admin->name }}', '{{ $admin->email }}')"
                                class="inline-flex items-center justify-center rounded-lg p-2 text-rose-600 transition-colors hover:bg-rose-50"
                                title="Hapus">
                          <svg class="h-4 w-4"
                               viewBox="0 0 24 24"
                               fill="none"
                               stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                          </svg>
                        </button>
                      @endif
                    </div>
                  </td>
                @endif
              </tr>
            @empty
              <tr>
                <td colspan="5"
                    class="px-4 py-8 text-center text-neutral-500">
                  Belum ada data admin.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Cards Mobile --}}
    <div class="space-y-3 md:hidden">
      @forelse ($admins as $admin)
        <div class="rounded-2xl border border-neutral-200 bg-white p-4">
          <div class="flex items-start gap-3">
            <div
                 class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-lg font-semibold text-white">
              {{ strtoupper(substr($admin->name, 0, 1)) }}
            </div>
            <div class="flex-1">
              <div class="font-medium text-neutral-800">{{ $admin->name }}</div>
              <div class="text-sm text-neutral-600">{{ $admin->email }}</div>
              <div class="mt-1 text-xs text-neutral-500">Dibuat: {{ $admin->created_at->format('d M Y') }}</div>
              <div class="mt-2 flex flex-wrap gap-1">
                @if ($admin->id === auth('admin')->id())
                  <span
                        class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">
                    Anda
                  </span>
                @endif
                @if ($admin->isSuperAdmin())
                  <span
                        class="inline-flex items-center rounded-full bg-purple-100 px-2 py-0.5 text-xs font-semibold text-purple-700">
                    SUPER ADMIN
                  </span>
                @else
                  <span
                        class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">
                    Admin
                  </span>
                @endif
              </div>
            </div>
          </div>

          @if (auth('admin')->user()->isSuperAdmin())
            <div class="mt-4 flex gap-2 border-t border-neutral-100 pt-4">
              <a href="{{ route('admin.admins.edit', $admin) }}"
                 class="inline-flex flex-1 items-center justify-center rounded-lg border border-blue-300 bg-blue-50 px-3 py-2 text-sm text-blue-700 transition-colors hover:bg-blue-100">
                <svg class="mr-2 h-4 w-4"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
              </a>

              @if ($admin->id !== auth('admin')->id() && !$admin->isSuperAdmin())
                <button onclick="confirmDelete({{ $admin->id }}, '{{ $admin->name }}', '{{ $admin->email }}')"
                        class="inline-flex flex-1 items-center justify-center rounded-lg border border-rose-300 bg-rose-50 px-3 py-2 text-sm text-rose-700 transition-colors hover:bg-rose-100">
                  <svg class="mr-2 h-4 w-4"
                       viewBox="0 0 24 24"
                       fill="none"
                       stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                  Hapus
                </button>
              @endif
            </div>
          @endif
        </div>
      @empty
        <div class="rounded-2xl border border-neutral-200 bg-white p-8 text-center text-neutral-500">
          Belum ada data admin.
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if ($admins->hasPages())
      <div class="flex justify-center">
        {{ $admins->links() }}
      </div>
    @endif
  </div>

  {{-- Modal Konfirmasi Hapus --}}
  <div id="deleteModal"
       class="fixed inset-0 z-[10001] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/50"
         onclick="closeDeleteModal()"></div>

    <div class="relative z-10 w-[92%] max-w-md rounded-2xl bg-white shadow-xl">
      <div class="flex items-center justify-between border-b border-neutral-200 p-4">
        <h3 class="text-lg font-semibold text-rose-700">Konfirmasi Hapus Admin</h3>
        <button onclick="closeDeleteModal()"
                class="text-neutral-400 hover:text-neutral-600">✕</button>
      </div>

      <div class="p-6">
        <div class="mb-4 flex items-center justify-center">
          <div class="rounded-full bg-rose-100 p-3">
            <svg class="h-8 w-8 text-rose-600"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
        </div>

        <p class="mb-4 text-center text-neutral-700">
          Apakah Anda yakin ingin menghapus admin ini?
        </p>

        <div class="rounded-lg bg-neutral-50 p-4 text-sm">
          <div class="space-y-2">
            <div class="flex justify-between">
              <span class="text-neutral-600">Nama:</span>
              <span class="font-medium text-neutral-800"
                    id="deleteAdminName">-</span>
            </div>
            <div class="flex justify-between">
              <span class="text-neutral-600">Email:</span>
              <span class="font-medium text-neutral-800"
                    id="deleteAdminEmail">-</span>
            </div>
          </div>
        </div>

        <p class="mt-4 text-center text-xs text-neutral-500">
          ⚠️ Tindakan ini tidak dapat dibatalkan!
        </p>
      </div>

      <form id="deleteAdminForm"
            method="POST"
            class="border-t border-neutral-200 p-4">
        @csrf
        @method('DELETE')
        <div class="flex gap-2">
          <button type="button"
                  onclick="closeDeleteModal()"
                  class="flex-1 rounded-lg border border-neutral-300 px-4 py-2 text-neutral-700 hover:bg-neutral-50">
            Batal
          </button>
          <button type="submit"
                  class="flex-1 rounded-lg bg-rose-600 px-4 py-2 font-medium text-white hover:bg-rose-700">
            Ya, Hapus
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function confirmDelete(id, name, email) {
      const modal = document.getElementById('deleteModal');
      const form = document.getElementById('deleteAdminForm');

      // Set detail admin di modal
      document.getElementById('deleteAdminName').textContent = name;
      document.getElementById('deleteAdminEmail').textContent = email;

      // Set action URL untuk form
      form.action = `{{ url('admin/admins') }}/${id}`;

      // Tampilkan modal
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeDeleteModal() {
      const modal = document.getElementById('deleteModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    // Close modal dengan ESC key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeDeleteModal();
      }
    });
  </script>
@endsection
