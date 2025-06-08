<?php

use App\Models\Category;
use App\Models\RequestModel;
use App\Models\User;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;

it('should allow admin to view requests index', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('admin');
    $this->actingAs($user);

    // act
    $response = get(route('admin.requests.index'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('admin.requests.index');
});

it('permite acceder a la vista de crear solicitud', function () {
    // Crear usuario y asignarle rol 'admin'
    $admin = User::factory()->create();
    Role::firstOrCreate(['name' => 'admin']);
    $admin->assignRole('admin');

    // Autenticar como admin
    $this->actingAs($admin);

    // Hacer la petición
    $response = $this->get(route('admin.requests.create'));

    // Verificar que se accede a la vista correctamente
    $response->assertStatus(200);
    $response->assertViewIs('admin.requests.create');
});

it('allows a admin to delete a request', function () {
    // Arrange
    $admin = User::factory()->create();
    $admin->syncRoles('admin');
    $this->actingAs($admin);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->create([
        'category_id' => $category->id,
        'user_id' => $admin->id,
    ]);

    // Act
    $response = delete(route('admin.requests.destroy', $request));

    // Assert
    $response->assertRedirect(route('admin.requests.index'));
    $this->assertDatabaseMissing('request_models', ['id' => $request->id]);

})->skip();

it('allows a admin to create a request', function () {
    // Arrange
    $admin = User::factory()->create();
    $admin->syncRoles('admin');
    $this->actingAs($admin);
    $category = Category::factory()->create();
    $request = RequestModel::factory()->make([
        'category_id' => $category->id,
        'user_id' => $admin->id
    ]);

    // Act
    $response = $this->post(route('admin.requests.store'), $request->toArray());

    // Assert
    $response->assertRedirect(route('admin.requests.index'));
    $this->assertDatabaseHas('request_models', [
        'category_id' => $category->id,
        'user_id' => $admin->id,
    ]);
});

it('shows the edit view with request and categories', function () {
    // Arrange
    $user = User::factory()->create();
    $user->syncRoles('admin');
    $this->actingAs($user);

    $category = Category::factory()->create();
    $requestModel = RequestModel::factory()->create();

    // Act
    $response = $this->get(route('admin.requests.edit', $requestModel));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('admin.requests.edit');
    $response->assertViewHas('requestModel', $requestModel);
    $response->assertViewHas('categories', function ($categories) {
        return $categories->count() > 0;
    });
});
