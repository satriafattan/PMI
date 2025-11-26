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
            ['email' => 'simphonydarah@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('simphony2025'),
                'is_super_admin' => true
            ]
        );
    }
}
