<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin default
        Admin::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_super_admin' => true
            ]
        );

        // Admin biasa
        Admin::updateOrCreate(
            ['email' => 'jaflahx@gmail.com'],
            [
                'name' => 'Admin Jafla',
                'password' => Hash::make('password123'),
                'is_super_admin' => false
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'admin2@gmail.com'],
            [
                'name' => 'Admin Heru',
                'password' => Hash::make('password123'),
                'is_super_admin' => false
            ]
        );
    }
}
