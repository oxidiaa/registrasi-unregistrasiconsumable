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
            'department' => 'Warehouse Consumable',
            'role'       => 'MASTER',
            'status'     => 'Aktif',
            'password'   => bcrypt('admin'),
        ]);

        // 1b. Master User
        User::firstOrCreate(['email' => 'master'], [
            'name'       => 'Master Administrator',
            'department' => 'Warehouse Consumable',
            'role'       => 'MASTER',
            'status'     => 'Aktif',
            'password'   => bcrypt('master'),
        ]);

        // 2. User Pembuat Form (Production)
        User::firstOrCreate(['email' => 'budi_user'], [
            'name'       => 'Budi Santoso (User)',
            'department' => 'Production',
            'role'       => 'User',
            'status'     => 'Aktif',
            'password'   => bcrypt('user'),
        ]);

        // 3. Staff Approver (Approval Tahap 1)
        User::firstOrCreate(['email' => 'staff'], [
            'name'       => 'Suherman (Staff Approver)',
            'department' => 'Production',
            'role'       => 'Staff',
            'status'     => 'Aktif',
            'password'   => bcrypt('staff'),
        ]);

        // 4. Accounting Approver (Approval Tahap 2)
        User::firstOrCreate(['email' => 'accounting'], [
            'name'       => 'Hendra (Accounting Approver)',
            'department' => 'Accounting',
            'role'       => 'Accounting',
            'status'     => 'Aktif',
            'password'   => bcrypt('accounting'),
        ]);

        // 5. Warehouse Consumable (Final Registrasi)
        User::firstOrCreate(['email' => 'warehouse'], [
            'name'       => 'Joko Widodo (Warehouse Consumable)',
            'department' => 'PPIC Warehouse',
            'role'       => 'Warehouse Consumable',
            'status'     => 'Aktif',
            'password'   => bcrypt('warehouse'),
        ]);

        // 6. Admin Consumable (Alternative Login)
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
