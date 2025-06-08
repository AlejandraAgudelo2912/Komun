<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->word();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(),
            'icon' => $this->faker->randomElement(['broom', 'leaf', 'tools', 'heart', 'paw', 'car', 'book', 'laptop']),
            'color' => $this->faker->hexColor(),
        ];
    }

    public function withIcon(string $icon): self
    {
        return $this->state(function (array $attributes) use ($icon) {
            return [
                'icon' => $icon,
            ];
        });
    }

    public function withColor(string $color): self
    {
        return $this->state(function (array $attributes) use ($color) {
            return [
                'color' => $color,
            ];
        });
    }
}
