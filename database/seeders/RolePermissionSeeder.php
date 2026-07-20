<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@myori.my'], // Condition to check if the user exists
            [
                'name' => 'Super Admin',
                'password' => Hash::make('abcd1234'),
            ]
        );

        $permissions = [
            'can:view:audit',
            'can:view:permission',
            'can:create:permission',
            'can:update:permission',
            'can:delete:permission',
            'can:view:role',
            'can:create:role',
            'can:update:role',
            'can:delete:role',
            'can:view:user',
            'can:create:user',
            'can:update:user',
            'can:delete:user',
            'can:view:sticker',
            'can:create:sticker',
            'can:update:sticker',
            'can:delete:sticker',
            'can:view:setting',
            'can:view:control',
        ];

        foreach ($permissions as $permission) {
            $p = Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = ['Super Admin', 'User'];

        foreach ($roles as $role) {
            $r = Role::firstOrCreate(['name' => $role]);
            if ($role === 'Super Admin') {
                $r->syncPermissions($permissions);
                $user->removeRole($role);
                $user->assignRole($role);
            }
        }
    }
}
