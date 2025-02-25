<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Buat role superadmin (kalau belum ada)
        $role = Role::firstOrCreate(['name' => 'superadmin']);

        // Buat user superadmin
        $user = User::updateOrCreate(
            ['email' => 'superadmin@itk.com'], // Cek berdasarkan email biar tidak dobel
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Minimal8') // Hash password
            ]
        );

        // Pastikan user memiliki role superadmin
        $user->syncRoles([$role->name]);
    }
}
