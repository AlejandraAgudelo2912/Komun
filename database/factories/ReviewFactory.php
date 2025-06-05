<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\RequestModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'request_models_id' => RequestModel::factory(),
            'user_id' => User::factory(),
            'assistant_id' => User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph(),
        ];
    }

    public function positive(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => $this->faker->numberBetween(4, 5),
                'comment' => $this->faker->randomElement([
                    'Excelente servicio, muy profesional y puntual.',
                    'Muy satisfecho con el resultado, lo recomiendo.',
                    'Increíble atención y resultados, volveré a contratar.',
                    'Muy buen trabajo, cumplió con todas las expectativas.',
                    'Servicio de alta calidad, muy recomendable.'
                ]),
            ];
        });
    }

    public function neutral(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => 3,
                'comment' => $this->faker->randomElement([
                    'El servicio fue aceptable, pero podría mejorar.',
                    'Buen trabajo, aunque hay aspectos a mejorar.',
                    'Cumplió con lo básico, pero esperaba más.',
                    'Servicio regular, ni bueno ni malo.',
                    'Aceptable, pero hay margen de mejora.'
                ]),
            ];
        });
    }

    public function negative(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => $this->faker->numberBetween(1, 2),
                'comment' => $this->faker->randomElement([
                    'No cumplió con lo prometido.',
                    'Servicio deficiente, no lo recomiendo.',
                    'Muy descontento con el resultado.',
                    'No volvería a contratar este servicio.',
                    'Pésima experiencia, no cumplió las expectativas.'
                ]),
            ];
        });
    }
} 