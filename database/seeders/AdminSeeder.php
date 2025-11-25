<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Admin default
        Admin::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Super Admin', 'password' => Hash::make('password')]
        );

        // Tambahkan admin Anda di sini
        Admin::updateOrCreate(
            ['email' => 'jaflahx@gmail.com'],
            [
                'name' => 'Admin Jafla',
                'password' => Hash::make('password123') // Ganti dengan password yang Anda inginkan
            ]
        );
    }
}
