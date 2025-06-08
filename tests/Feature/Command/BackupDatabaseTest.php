<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    config(['database.connections.mysql.database' => 'komun_test']);
});

it('exports all tables to JSON files', function () {
    // arrange
    $tables = [
        (object) ['Tables_in_komun_test' => 'users'],
        (object) ['Tables_in_komun_test' => 'posts'],
    ];

    DB::shouldReceive('select')
        ->once()
        ->with('SHOW TABLES')
        ->andReturn($tables);

    DB::shouldReceive('table')
        ->with('users')
        ->andReturn(new class
        {
            public function get()
            {
                return collect([
                    (object) ['id' => 1, 'name' => 'Alice'],
                ]);
            }
        });

    DB::shouldReceive('table')
        ->with('posts')
        ->andReturn(new class
        {
            public function get()
            {
                return collect([
                    (object) ['id' => 1, 'title' => 'Hello World'],
                ]);
            }
        });

    // act
    Artisan::call('komun:backup-db');

    // assert
    Storage::disk('local')->assertExists('backups/json/users.json');
    Storage::disk('local')->assertExists('backups/json/posts.json');

    $usersJson = Storage::disk('local')->get('backups/json/users.json');
    expect(json_decode($usersJson, true))->toBe([
        ['id' => 1, 'name' => 'Alice'],
    ]);

    $postsJson = Storage::disk('local')->get('backups/json/posts.json');
    expect(json_decode($postsJson, true))->toBe([
        ['id' => 1, 'title' => 'Hello World'],
    ]);
});

it('creates the backup directory if it does not exist', function () {
    // arrange
    Storage::disk('local')->deleteDirectory('backups/json');

    $tables = [
        (object) ['Tables_in_komun_test' => 'settings'],
    ];

    DB::shouldReceive('select')->andReturn($tables);

    // Simula un query builder con un método get()
    $mockBuilder = Mockery::mock();
    $mockBuilder->shouldReceive('get')->andReturn(collect([
        (object) ['key' => 'value'],
    ]));

    DB::shouldReceive('table')->with('settings')->andReturn($mockBuilder);

    // act
    Artisan::call('komun:backup-db');

    // assert
    Storage::disk('local')->assertExists('backups/json/settings.json');

    // Limpieza (opcional)
    Storage::disk('local')->deleteDirectory('backups/json');
});

it('shows messages during the process', function () {
    // arrange
    $tables = [
        (object) ['Tables_in_komun_test' => 'logs'],
    ];

    DB::shouldReceive('select')->andReturn($tables);

    $mockBuilder = Mockery::mock();
    $mockBuilder->shouldReceive('get')->andReturn(collect([
        (object) ['id' => 1, 'message' => 'test log'],
    ]));

    DB::shouldReceive('table')->with('logs')->andReturn($mockBuilder);

    // act
    Artisan::call('komun:backup-db');

    // assert
    expect(Artisan::output())
        ->toContain('Exportando tablas a JSON')
        ->toContain('Exportando tabla: logs')
        ->toContain('Exportación completada');
});
