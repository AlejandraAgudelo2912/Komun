<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\RequestModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'receiver_id' => User::factory(),
            'request_model_id' => RequestModel::factory(),
            'message' => $this->faker->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function recent(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => now()->subHours(rand(1, 24)),
                'updated_at' => now()->subHours(rand(1, 24)),
            ];
        });
    }

    public function old(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => now()->subDays(rand(2, 30)),
                'updated_at' => now()->subDays(rand(2, 30)),
            ];
        });
    }

    public function withRequest(RequestModel $request): self
    {
        return $this->state(function (array $attributes) use ($request) {
            return [
                'request_model_id' => $request->id,
            ];
        });
    }

    public function betweenUsers(User $user1, User $user2): self
    {
        return $this->state(function (array $attributes) use ($user1, $user2) {
            return [
                'user_id' => $user1->id,
                'receiver_id' => $user2->id,
            ];
        });
    }
}
