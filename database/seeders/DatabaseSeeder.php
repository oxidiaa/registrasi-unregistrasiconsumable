<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin Master
        User::create([
            'name'       => 'Admin Master MAI',
            'email'      => 'admin',
            'department' => 'Management / Executive',
            'role'       => 'MASTER',
            'status'     => 'Aktif',
            'password'   => bcrypt('admin'),
        ]);

        // 2. Budi User (Production)
        User::create([
            'name'       => 'Budi Santoso',
            'email'      => 'budi_user',
            'department' => 'Production',
            'role'       => 'USER',
            'status'     => 'Aktif',
            'password'   => bcrypt('user'),
        ]);

        // 3. Suherman (Pemeriksa)
        User::create([
            'name'       => 'Suherman',
            'email'      => 'suherman_spv',
            'department' => 'Quality Assurance',
            'role'       => 'PEMERIKSA',
            'status'     => 'Aktif',
            'password'   => bcrypt('pemeriksa'),
        ]);

        // 4. Joko Widodo (Warehouse)
        User::create([
            'name'       => 'Joko Widodo',
            'email'      => 'joko_wh',
            'department' => 'Warehouse Logistik',
            'role'       => 'WAREHOUSE',
            'status'     => 'Aktif',
            'password'   => bcrypt('warehouse'),
        ]);

        // 5. Admin Consumable (Default login)
        User::create([
            'name'       => 'Admin Consumable',
            'email'      => 'admin',
            'department' => 'Production',
            'role'       => 'MASTER',
            'status'     => 'Aktif',
            'password'   => bcrypt('admin'),
        ]);
    }
}
