<?php


use App\Jobs\SendApplicationResponseEmailJob;
use App\Livewire\RequestApplicantManager;
use App\Models\RequestModel;
use App\Models\User;

it('renders the component', function () {
    // arrange
    $request = RequestModel::factory()->create();

    // act & assert
    Livewire::test(RequestApplicantManager::class, ['requestModel' => $request])
        ->assertStatus(200)
        ->assertViewIs('livewire.request-applicant-manager');
});

it('accepts an applicant', function () {
    // arrange
    Bus::fake();
    $request = RequestModel::factory()->create();
    $user = User::factory()->create();
    $request->applicants()->attach($user->id, ['status' => 'pending']);

    // act
    Livewire::test(RequestApplicantManager::class, ['requestModel' => $request])
        ->call('acceptApplicant', $user->id);

    // assert
    $this->assertDatabaseHas('request_model_application', [
        'request_model_id' => $request->id,
        'user_id' => $user->id,
        'status' => 'accepted',
    ]);

    Bus::assertDispatched(SendApplicationResponseEmailJob::class, function ($job) use ($user, $request) {
        return $job->applicant->is($user)
            && $job->requestModel->is($request)
            && $job->status === 'accepted';
    });
});

it('rejects an applicant', function () {
    // arrange
    Bus::fake();
    $request = RequestModel::factory()->create();
    $user = User::factory()->create();
    $request->applicants()->attach($user->id, ['status' => 'pending']);

    // act
    Livewire::test(RequestApplicantManager::class, ['requestModel' => $request])
        ->call('rejectApplicant', $user->id);

    // assert
    $this->assertDatabaseHas('request_model_application', [
        'request_model_id' => $request->id,
        'user_id' => $user->id,
        'status' => 'rejected',
    ]);

    Bus::assertDispatched(SendApplicationResponseEmailJob::class, function ($job) use ($user, $request) {
        return $job->applicant->is($user)
            && $job->requestModel->is($request)
            && $job->status === 'rejected';
    });
});
