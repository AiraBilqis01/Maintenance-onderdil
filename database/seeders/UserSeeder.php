<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Supervisor
        $supervisor = User::withTrashed()->firstOrCreate(
            ['email' => 'supervisor@gmail.com'],
            [
                'name' => 'Supervisor',
                'no_telepon' => '081234567890',
                'password' => Hash::make('password'),
                'role' => 'supervisor',
            ]
        );

        // Aktifkan kembali jika sebelumnya soft deleted
        if ($supervisor->trashed()) {
            $supervisor->restore();
        }

        // Akun Tenaga Kerja
        $tenagaKerja = User::withTrashed()->firstOrCreate(
            ['email' => 'khairanibilqis2003@gmail.com'],
            [
                'name' => 'Rani',
                'no_telepon' => '081234567891',
                'password' => Hash::make('password'),
                'role' => 'tenagakerja',
                'jenis_pelaksana' => 'Internal',
                'jenis_tugas' => 'keduanya',
                'keahlian' => 'Mekanik Mesin',
            ]
        );

        // Aktifkan kembali jika sebelumnya soft deleted
        if ($tenagaKerja->trashed()) {
            $tenagaKerja->restore();
        }
    }
}
