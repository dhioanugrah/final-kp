<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Buat role admin kalau belum ada
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Buat user admin
        $user = User::updateOrCreate(
            ['email' => 'admin@hit.com'], // Cek berdasarkan email biar tidak dobel
            [
                'name' => 'Admin',
                'password' => Hash::make('Minimal8') // Hash password
            ]
        );

        // Pastikan user memiliki role admin
        $user->syncRoles([$role->name]);
    }
}
