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
        User::firstOrCreate(['email' => 'admin'], [
            'name'       => 'Admin Master MAI',
            'department' => 'Management / Executive',
            'role'       => 'MASTER',
            'status'     => 'Aktif',
            'password'   => bcrypt('admin'),
        ]);

        // 2. Budi User (Production)
        User::firstOrCreate(['email' => 'budi_user'], [
            'name'       => 'Budi Santoso',
            'department' => 'Production',
            'role'       => 'USER',
            'status'     => 'Aktif',
            'password'   => bcrypt('user'),
        ]);

        // 3. Suherman (Pemeriksa)
        User::firstOrCreate(['email' => 'suherman'], [
            'name'       => 'Suherman',
            'department' => 'Quality Assurance',
            'role'       => 'PEMERIKSA',
            'status'     => 'Aktif',
            'password'   => bcrypt('suherman'),
        ]);

        // 4. Joko Widodo (Warehouse)
        User::firstOrCreate(['email' => 'joko_wh'], [
            'name'       => 'Joko Widodo',
            'department' => 'Warehouse Logistik',
            'role'       => 'WAREHOUSE',
            'status'     => 'Aktif',
            'password'   => bcrypt('warehouse'),
        ]);

        // 5. Admin Consumable (Default login)
        User::firstOrCreate(['email' => 'admin_consumable'], [
            'name'       => 'Admin Consumable',
            'department' => 'Production',
            'role'       => 'MASTER',
            'status'     => 'Aktif',
            'password'   => bcrypt('admin'),
        ]);

        // Populate creator information for any existing FormItems
        foreach (\App\Models\FormItem::whereNull('created_by_name')->get() as $item) {
            $parts = explode('/', $item->form_number);
            $deptTag = count($parts) >= 2 ? $parts[1] : 'PRODUCTION';
            $user = User::where('department', 'like', '%' . $deptTag . '%')->first() ?? User::first();
            $item->update([
                'user_id'         => $user?->id,
                'created_by_name' => $user?->name ?? 'User',
                'created_by_dept' => $user?->department ?? $deptTag,
            ]);
        }
    }
}
