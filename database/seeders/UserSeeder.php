<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Supervisor',
            'email' => 'supervisor@gmail.com',
            'no_telepon' => '081234567890',
            'password' => bcrypt('password'),
            'role' => 'supervisor',
        ]);

        User::create([
            'name' => 'dimas (Tenaga Kerja)',
            'email' => 'fedev1305@gmail.com',
            'no_telepon' => '081234567891',
            'password' => bcrypt('password'),
            'role' => 'tenagakerja',
        ]);
    }
}