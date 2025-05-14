<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {

        $permissions = [
            'create requests',
            'edit own requests',
            'delete own requests',
            'comment on requests',
            'verify requests',
            'access verified requests',
            'manage users',
            'access admin',
            'access god panel',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
    }
}
