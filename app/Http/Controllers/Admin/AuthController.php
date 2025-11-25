<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\AdminResetPasswordMail;
use App\Models\Admin;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->filled('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard')
                ->with('success', 'Selamat datang kembali!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Menampilkan form lupa password
     */
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Mengirim link reset password ke email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Cek apakah email admin ada
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan dalam sistem.',
            ])->onlyInput('email');
        }

        // Generate token
        $token = Str::random(64);

        // Simpan token ke database (hapus token lama jika ada)
        DB::table('admin_password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        DB::table('admin_password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Kirim email
        try {
            Mail::to($request->email)->send(new AdminResetPasswordMail($token, $request->email));

            return back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Gagal mengirim email. Silakan coba lagi nanti.',
            ])->onlyInput('email');
        }
    }

    /**
     * Menampilkan form reset password
     */
    public function showResetPasswordForm($token)
    {
        return view('admin.auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Cari token di database
        $resetRecord = DB::table('admin_password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors([
                'email' => 'Token reset password tidak valid atau sudah kadaluarsa.',
            ])->onlyInput('email');
        }

        // Verifikasi token
        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors([
                'email' => 'Token reset password tidak valid.',
            ])->onlyInput('email');
        }

        // Cek apakah token sudah lebih dari 1 jam (3600 detik)
        if (now()->diffInSeconds($resetRecord->created_at) > 3600) {
            return back()->withErrors([
                'email' => 'Token reset password sudah kadaluarsa. Silakan request ulang.',
            ])->onlyInput('email');
        }

        // Update password admin
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return back()->withErrors([
                'email' => 'Admin tidak ditemukan.',
            ])->onlyInput('email');
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        // Hapus token
        DB::table('admin_password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect()->route('admin.login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }
}
