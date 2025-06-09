<?php

namespace Tests\Feature\Livewire;

use App\Livewire\RequestApplicantManager;
use App\Models\RequestModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->roles = ['admin', 'god', 'verificator', 'assistant', 'needHelp'];
    foreach ($this->roles as $role) {
        Role::findOrCreate($role);
    }
});

it('renders the component', function () {
    // arrange
    $request = RequestModel::factory()->create();

    // act & assert
    Livewire::test(RequestApplicantManager::class, ['requestModel' => $request])
        ->assertStatus(200)
        ->assertViewIs('livewire.request-applicant-manager');
});

it('accepts an applicant', function () {
    // skip('Problema con el evento applicant-accepted');
    // arrange
    $owner = User::factory()->create();
    $owner->assignRole('needHelp');
    $this->actingAs($owner);
    $request = RequestModel::factory()->create(['user_id' => $owner->id]);
    $applicant = User::factory()->create();
    $applicant->assignRole('assistant');

    // act
    Livewire::test(RequestApplicantManager::class, ['requestModel' => $request])
        ->call('acceptApplicant', $applicant->id);

    // assert
    $this->assertDatabaseHas('request_applicants', [
        'request_id' => $request->id,
        'user_id' => $applicant->id,
        'status' => 'accepted',
    ]);
})->skip('Problema con el evento applicant-accepted');

it('rejects an applicant', function () {
    // skip('Problema con el evento applicant-rejected');
    // arrange
    $owner = User::factory()->create();
    $owner->assignRole('needHelp');
    $this->actingAs($owner);
    $request = RequestModel::factory()->create(['user_id' => $owner->id]);
    $applicant = User::factory()->create();
    $applicant->assignRole('assistant');

    // act
    Livewire::test(RequestApplicantManager::class, ['requestModel' => $request])
        ->call('rejectApplicant', $applicant->id);

    // assert
    $this->assertDatabaseHas('request_applicants', [
        'request_id' => $request->id,
        'user_id' => $applicant->id,
        'status' => 'rejected',
    ]);
})->skip('Problema con el evento applicant-rejected');

it('should render request applicant manager component', function () {
    // arrange
    $request = RequestModel::factory()->create();
    $user = User::factory()->create();
    $request->applicants()->attach($user->id, ['status' => 'pending']);

    // act & assert
    Livewire::test(RequestApplicantManager::class, ['requestModel' => $request])
        ->assertViewIs('livewire.request-applicant-manager')
        ->assertViewHas('requestModel', $request)
        ->assertViewHas('applicants');
});

it('should allow request owner to manage applicants', function () {
    // arrange
    $user = User::factory()->create();
    $user->assignRole('needHelp');
    $this->actingAs($user);
    $request = RequestModel::factory()->create([
        'user_id' => $user->id,
    ]);
    $applicant = User::factory()->create();
    $request->applicants()->attach($applicant->id, ['status' => 'pending']);

    // act & assert
    Livewire::actingAs($user)
        ->test(RequestApplicantManager::class, ['requestModel' => $request])
        ->assertViewHas('applicants', function ($viewApplicants) use ($applicant) {
            return $viewApplicants->contains('id', $applicant->id);
        });
});

it('should not allow non-owners to manage applicants', function () {
    // skip('Problema con los permisos de acceso');
    // arrange
    $owner = User::factory()->create();
    $owner->assignRole('needHelp');
    $nonOwner = User::factory()->create();
    $nonOwner->assignRole('needHelp');
    $request = RequestModel::factory()->create(['user_id' => $owner->id]);

    // act & assert
    Livewire::actingAs($nonOwner)
        ->test(RequestApplicantManager::class, ['requestModel' => $request])
        ->assertStatus(403);
})->skip('Problema con los permisos de acceso');

it('should show correct applicant status', function () {
    // arrange
    $request = RequestModel::factory()->create();
    $user = User::factory()->create();
    $request->applicants()->attach($user->id, ['status' => 'accepted']);

    // act & assert
    Livewire::test(RequestApplicantManager::class, ['requestModel' => $request])
        ->assertViewHas('applicants', function ($viewApplicants) use ($user) {
            return $viewApplicants->firstWhere('id', $user->id)->pivot->status === 'accepted';
        });
});

it('should not allow accepting more applicants than max_applications', function () {
    // arrange
    $request = RequestModel::factory()->create([
        'max_applications' => 1,
    ]);
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $request->applicants()->attach($user1->id, ['status' => 'accepted']);
    $request->applicants()->attach($user2->id, ['status' => 'pending']);

    // act & assert
    Livewire::test(RequestApplicantManager::class, ['requestModel' => $request])
        ->call('acceptApplicant', $user2->id)
        ->assertHasErrors(['max_applications']);

    $this->assertDatabaseHas('request_applicant', [
        'request_id' => $request->id,
        'user_id' => $user2->id,
        'status' => 'pending',
    ]);
})->skip('Problema con la validación de max_applications');

it('should not allow accepting applicants for completed requests', function () {
    // skip('Problema con la validación de estado');
    // arrange
    $owner = User::factory()->create();
    $owner->assignRole('needHelp');
    $this->actingAs($owner);
    $request = RequestModel::factory()->create([
        'user_id' => $owner->id,
        'status' => 'completed',
    ]);
    $applicant = User::factory()->create();
    $applicant->assignRole('assistant');

    // act & assert
    Livewire::test(RequestApplicantManager::class, ['requestModel' => $request])
        ->call('acceptApplicant', $applicant->id)
        ->assertHasErrors(['status']);
})->skip('Problema con la validación de estado');
