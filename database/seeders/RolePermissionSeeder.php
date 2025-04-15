<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Buat roles
        $roles = [
            'superadmin',
            'checker_1',
            'checker_2',
            'direktur'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Buat permissions
        $permissions = [
            'approve checker 1',
            'approve checker 2',
            'approve direktur'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions ke role
        Role::where('name', 'checker_1')->first()->givePermissionTo('approve checker 1');
        Role::where('name', 'checker_2')->first()->givePermissionTo('approve checker 2');
        Role::where('name', 'direktur')->first()->givePermissionTo('approve direktur');

        // Buat user dan assign role
        $users = [
            [
                'name' => 'Muhammad Syaifullah',
                'email' => 'syaifullah@example.com',
                'password' => '12345678',
                'role' => 'checker_1'
            ],
            [
                'name' => 'Dony Hartanto',
                'email' => 'dony@example.com',
                'password' => '12345678',
                'role' => 'checker_2'
            ],
            [
                'name' => 'Adi Nugroho',
                'email' => 'adi@example.com',
                'password' => '12345678',
                'role' => 'direktur'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt($userData['password']),
                    'raw_password' => $userData['password'],

                ]
            );

            $user->syncRoles([$userData['role']]); // syncRoles biar gak numpuk role kalau seeder dijalankan lagi
        }
    }
}
