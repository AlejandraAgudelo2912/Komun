<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@komun.com',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ],
            [
                'name' => 'God User',
                'email' => 'god@komun.com',
                'password' => Hash::make('password'),
                'role' => 'god'
            ],
            [
                'name' => 'Verificator User',
                'email' => 'verificator@komun.com',
                'password' => Hash::make('password'),
                'role' => 'verificator'
            ],
            [
                'name' => 'Assistant User',
                'email' => 'assistant@komun.com',
                'password' => Hash::make('password'),
                'role' => 'assistant'
            ],
            [
                'name' => 'Need Help User',
                'email' => 'needhelp@komun.com',
                'password' => Hash::make('password'),
                'role' => 'needHelp'
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            
            $user = User::create($userData);
            $user->assignRole($role);
        }
    }
} 