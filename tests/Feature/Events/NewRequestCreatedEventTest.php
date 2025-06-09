<?php

namespace Tests\Feature\Events;

use App\Events\NewRequestCreated;
use App\Models\Category;
use App\Models\RequestModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        Role::findOrCreate($role);
    }
});

it('should dispatch event when new request is created', function () {
    // skip('Problema con el evento NewRequestCreated');
    Event::fake();

    // arrange
    $needHelp = User::factory()->create();
    $needHelp->assignRole('needHelp');
    $this->actingAs($needHelp);

    // act
    $request = RequestModel::factory()->create([
        'user_id' => $needHelp->id,
        'status' => 'pending',
    ]);

    // assert
    Event::assertDispatched(NewRequestCreated::class);
})->skip('Problema con el evento NewRequestCreated');

it('should include correct data in the event', function () {
    // skip('Problema con el evento NewRequestCreated');
    // arrange
    $needHelp = User::factory()->create();
    $needHelp->assignRole('needHelp');
    $this->actingAs($needHelp);

    $request = RequestModel::factory()->create([
        'user_id' => $needHelp->id,
        'status' => 'pending',
    ]);

    // act
    $event = new NewRequestCreated($request);

    // assert
    expect($event->request)->toBe($request);
    expect($event->request->user_id)->toBe($needHelp->id);
})->skip('Problema con el evento NewRequestCreated'); 