<?php

use App\Models\User;
use App\Models\Assistant;

beforeEach(function () {
    //user admin
    $this->actingAs(User::factory()->create()->assignRole('admin'));
});

it('muestra la vista con los usuarios sin filtros', function () {
    $users = User::factory()->count(3)->create();

    $response = $this->get(route('admin.profiles.index'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.profiles.index');
    $response->assertViewHas('users');
    $response->assertViewHas('filters');
});

it('filtra por nombre o email con search', function () {
    $user = User::factory()->create(['name' => 'Alejandra']);

    $response = $this->get(route('admin.profiles.index', ['search' => 'Alejandra']));

    $response->assertStatus(200);
    $response->assertSee('Alejandra');
});

it('filtra por rol si se pasa el parámetro role', function () {
    // Supongamos que tienes un campo "role" en la tabla users
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->get(route('admin.profiles.index', ['role' => 'admin']));

    $response->assertStatus(200);
    $response->assertSee($user->name);
});
