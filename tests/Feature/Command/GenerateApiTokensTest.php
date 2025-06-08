<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Crea los roles necesarios
    foreach (['needHelp', 'assistant', 'admin', 'god', 'verificator'] as $role) {
        Role::findOrCreate($role);
    }

    // Borra el archivo anterior si existe
    $path = storage_path('api-tokens.json');
    if (file_exists($path)) {
        unlink($path);
    }
});

it('genera tokens para todos los roles y guarda el archivo', function () {
    Artisan::call('generate:api-tokens');

    $output = Artisan::output();

    expect($output)->toContain('Token generado para needHelp:')
        ->toContain('Tokens guardados en:')
        ->toContain('Puedes usar estos tokens');

    // ✅ Aquí usamos assertFileExists directamente
    $jsonPath = storage_path('api-tokens.json');
    $this->assertFileExists($jsonPath);

    $data = json_decode(file_get_contents($jsonPath), true);
    expect($data)->toHaveKeys(['needHelp', 'assistant', 'admin', 'god', 'verificator']);

    foreach ($data as $role => $info) {
        expect($info)->toHaveKeys(['email', 'password', 'token']);
        expect($info['email'])->toBe("{$role}@komun.com");

        $user = User::where('email', "{$role}@komun.com")->first();
        expect($user)->not()->toBeNull();
        expect($user->hasRole($role))->toBeTrue();
    }
});
