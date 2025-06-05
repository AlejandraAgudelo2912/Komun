<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\RequestModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'request_model_id' => RequestModel::factory(),
            'body' => $this->faker->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
