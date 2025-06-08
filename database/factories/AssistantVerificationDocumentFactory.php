<?php

namespace Database\Factories;

use App\Models\Assistant;
use App\Models\AssistantVerificationDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssistantVerificationDocumentFactory extends Factory
{
    protected $model = AssistantVerificationDocument::class;

    public function definition(): array
    {
        return [
            'assistant_id' => Assistant::factory(),
            'dni_front_path' => 'verifications/dni_front/'.$this->faker->uuid().'.jpg',
            'dni_back_path' => 'verifications/dni_back/'.$this->faker->uuid().'.jpg',
            'selfie_path' => 'verifications/selfies/'.$this->faker->uuid().'.jpg',
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'rejection_reason' => $this->faker->optional()->sentence(),
        ];
    }

    public function pending(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'rejection_reason' => null,
            ];
        });
    }

    public function approved(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'approved',
                'rejection_reason' => null,
            ];
        });
    }

    public function rejected(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected',
                'rejection_reason' => $this->faker->sentence(),
            ];
        });
    }
}
