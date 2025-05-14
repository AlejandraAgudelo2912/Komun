<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $rolesPermissions = [
            'needHelp' => [
                'create requests',
                'edit own requests',
                'delete own requests',
                'comment on requests',
            ],
            'assistant' => [
                'comment on requests',
            ],
            'verificator' => [
                'verify requests',
                'access verified requests',
            ],
            'admin' => [
                'manage users',
                'access admin',
            ],
            'god' => Permission::pluck('name')->toArray(),
        ];

        foreach ($rolesPermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->syncPermissions($permissions);
            }
        }
    }
}
