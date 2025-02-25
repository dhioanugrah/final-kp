<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class WarehousePurchaseSeeder extends Seeder
{
    public function run()
    {
        // Buat roles warehouse dan purchase (kalau belum ada)
        $roles = ['warehouse', 'purchase'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Buat user untuk warehouse dan purchase
        $users = [
            [
                'name' => 'Novan Raditya',
                'email' => 'novan@example.com',
                'password' => '12345678',
                'role' => 'warehouse'
            ],
            [
                'name' => 'Ayu Aulia Mardita',
                'email' => 'ayu@example.com',
                'password' => '12345678',
                'role' => 'purchase'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']], // Cek berdasarkan email biar gak dobel
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']) // Update kalau sudah ada
                ]
            );

            $user->syncRoles([$userData['role']]); // Pastikan user hanya punya role yang sesuai
        }
    }
}
