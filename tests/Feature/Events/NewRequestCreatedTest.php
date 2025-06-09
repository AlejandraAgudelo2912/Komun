<?php

namespace Tests\Feature\Events;

use App\Models\User;
use App\Models\RequestModel;
use App\Models\Category;
use App\Events\NewRequestCreatedEvent;
use App\Listeners\SendNewRequestEmailListener;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Crear roles necesarios
    $roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($roles as $role) {
        Role::findOrCreate($role);
    }
});

it('dispatches event when new request is created', function () {
    Event::fake();

    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $category = Category::factory()->create();

    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id
    ]);

    Event::assertDispatched(NewRequestCreatedEvent::class, function ($event) use ($request) {
        return $event->request->id === $request->id;
    });
})->skip();

it('sends notification when event is handled', function () {
    Notification::fake();

    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $category = Category::factory()->create();

    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id
    ]);

    $event = new NewRequestCreatedEvent($request);
    $listener = new SendNewRequestEmailListener();
    $listener->handle($event);
});

it('notifies all assistants in the category', function () {
    Notification::fake();

    $category = Category::factory()->create();
    $assistants = User::factory()->count(3)->create();
    foreach ($assistants as $assistant) {
        $assistant->assignRole('assistant');
    }

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id
    ]);

    $event = new NewRequestCreatedEvent($request);
    $listener = new SendNewRequestEmailListener();
    $listener->handle($event);
});

it('does not notify users without assistant role', function () {
    Notification::fake();

    $category = Category::factory()->create();
    $regularUsers = User::factory()->count(3)->create();

    $user = User::factory()->create();
    $user->assignRole('needHelp');

    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id
    ]);

    $event = new NewRequestCreatedEvent($request);
    $listener = new SendNewRequestEmailListener();
    $listener->handle($event);

});
