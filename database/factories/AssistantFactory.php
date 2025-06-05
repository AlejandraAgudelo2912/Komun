<?php

namespace Database\Factories;

use App\Models\Assistant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssistantFactory extends Factory
{
    protected $model = Assistant::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'bio' => $this->faker->paragraph(),
            'availability' => json_encode($this->faker->randomElements(['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'], 3)),
            'skills' => json_encode($this->faker->words(5)),
            'experience_years' => $this->faker->numberBetween(0, 20),
            'is_verified' => $this->faker->boolean(),
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'total_reviews' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['active', 'inactive', 'suspended']),
        ];
    }

    public function active(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'active',
                'is_verified' => true,
            ];
        });
    }

    public function inactive(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'inactive',
            ];
        });
    }

    public function suspended(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'suspended',
            ];
        });
    }

    public function verified(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_verified' => true,
            ];
        });
    }

    public function unverified(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_verified' => false,
            ];
        });
    }
} 