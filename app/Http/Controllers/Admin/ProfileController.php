<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the admin profile page.
     */
    public function index()
    {
        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Update the admin profile.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Update nama dan email
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        // Jika user ingin ganti password
        if (!empty($validated['password'])) {
            // Validasi password lama
            if (!Hash::check($validated['current_password'], $admin->password)) {
                return back()->withErrors([
                    'current_password' => 'Password lama tidak sesuai.',
                ])->withInput();
            }

            // Update password baru
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
