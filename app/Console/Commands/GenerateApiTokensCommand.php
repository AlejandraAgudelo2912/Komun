<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class GenerateApiTokensCommand extends Command
{
    protected $signature = 'generate:api-tokens';

    protected $description = 'Generate API tokens';

    public function handle(): void
    {
        $roles = ['needHelp', 'assistant', 'admin', 'god', 'verificator'];
        $tokens = [];

        foreach ($roles as $roleName) {
            $user = User::role($roleName)->first();

            if (! $user) {
                $user = User::create([
                    'name' => ucfirst($roleName),
                    'email' => "{$roleName}@komun.com",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);

                $role = Role::findByName($roleName);
                $user->assignRole($role);

                $this->info("Usuario {$roleName} creado.");
            }

            $user->tokens()->delete();

            $token = $user->createToken("{$roleName}-token")->plainTextToken;
            $tokens[$roleName] = [
                'email' => $user->email,
                'password' => 'password',
                'token' => $token,
            ];

            $this->info("Generating token for {$roleName}:");
            $this->line("Email: {$user->email}");
            $this->line('Password: password');
            $this->line("Token: {$token}");
            $this->newLine();
        }

        $json = json_encode($tokens, JSON_PRETTY_PRINT);
        file_put_contents(storage_path('api-tokens.json'), $json);

        $this->info('Save tokens in: '.storage_path('api-tokens.json'));
        $this->info('You can use these tokens to access the API.');
    }
}
