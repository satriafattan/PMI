<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buat akun admin baru secara interaktif';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('===========================================');
        $this->info('    BUAT ADMIN BARU - SIMPHONY');
        $this->info('===========================================');
        $this->newLine();

        // Input Nama
        $name = $this->ask('Nama Admin');

        // Input Email dengan validasi
        do {
            $email = $this->ask('Email Admin');

            $validator = Validator::make(['email' => $email], [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                $this->error('Email tidak valid! Silakan masukkan email yang benar.');
                continue;
            }

            // Cek apakah email sudah ada
            if (Admin::where('email', $email)->exists()) {
                $this->error('Email sudah terdaftar!');
                if (!$this->confirm('Apakah Anda ingin update password untuk email ini?', false)) {
                    continue;
                }
                $updating = true;
            } else {
                $updating = false;
            }

            break;
        } while (true);

        // Input Password dengan validasi
        do {
            $password = $this->secret('Password (minimal 8 karakter)');

            if (strlen($password) < 8) {
                $this->error('Password minimal 8 karakter!');
                continue;
            }

            $passwordConfirmation = $this->secret('Konfirmasi Password');

            if ($password !== $passwordConfirmation) {
                $this->error('Password tidak cocok! Silakan coba lagi.');
                continue;
            }

            break;
        } while (true);

        // Simpan atau update admin
        try {
            $admin = Admin::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make($password),
                ]
            );

            $this->newLine();
            $this->info('===========================================');
            if ($updating) {
                $this->info('✓ Admin berhasil diupdate!');
            } else {
                $this->info('✓ Admin berhasil dibuat!');
            }
            $this->info('===========================================');
            $this->newLine();
            $this->table(
                ['Field', 'Value'],
                [
                    ['Nama', $admin->name],
                    ['Email', $admin->email],
                    ['Password', '********** (ter-enkripsi)'],
                    ['Dibuat', $admin->created_at->format('d/m/Y H:i:s')],
                ]
            );
            $this->newLine();
            $this->info('Admin dapat login di: ' . url('/admin/login'));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Gagal membuat admin: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
