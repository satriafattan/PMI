<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // Cek apakah request ke halaman admin
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            // Default redirect untuk user biasa (jika ada)
            return route('home');
        }

        return null;
    }
}
