<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of admins.
     */
    public function index()
    {
        $admins = Admin::latest()->paginate(10);

        // Statistics
        $totalAdmins = Admin::count();
        $superAdmins = Admin::where('is_super_admin', true)->count();
        $regularAdmins = Admin::where('is_super_admin', false)->count();

        return view('admin.manajemen-admin.index', compact('admins', 'totalAdmins', 'superAdmins', 'regularAdmins'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create()
    {
        return view('admin.manajemen-admin.create');
    }

    /**
     * Store a newly created admin in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'is_super_admin' => ['boolean'],
        ]);

        Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_super_admin' => $validated['is_super_admin'] ?? false,
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function edit(Admin $admin)
    {
        return view('admin.manajemen-admin.edit', compact('admin'));
    }

    /**
     * Update the specified admin in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'is_super_admin' => ['boolean'],
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->is_super_admin = $validated['is_super_admin'] ?? false;

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin berhasil diperbarui!');
    }

    /**
     * Remove the specified admin from storage.
     */
    public function destroy(Admin $admin)
    {
        // Prevent deleting self
        if ($admin->id === auth('admin')->id()) {
            return redirect()
                ->route('admin.admins.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        // Prevent deleting super admin
        if ($admin->isSuperAdmin()) {
            return redirect()
                ->route('admin.admins.index')
                ->with('error', 'Super Admin tidak dapat dihapus!');
        }

        $admin->delete();

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin berhasil dihapus!');
    }
}
