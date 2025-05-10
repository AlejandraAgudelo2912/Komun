<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Request;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $requests = Request::where('status', 'completed')->get();
        $users = User::all();

        if ($requests->isEmpty() || $users->isEmpty()) {
            return;
        }

        $reviews = [
            [
                'rating' => 5,
                'comment' => 'Excelente servicio, muy profesional y puntual.',
            ],
            [
                'rating' => 4,
                'comment' => 'Buen trabajo, aunque llegó un poco tarde.',
            ],
            [
                'rating' => 5,
                'comment' => 'Muy satisfecho con el resultado, lo recomiendo.',
            ],
            [
                'rating' => 3,
                'comment' => 'El servicio fue aceptable, pero podría mejorar.',
            ],
            [
                'rating' => 5,
                'comment' => 'Increíble atención y resultados, volveré a contratar.',
            ]
        ];

        foreach ($requests as $request) {
            if (rand(0, 1)) { 
                Review::create([
                    'rating' => $reviews[array_rand($reviews)]['rating'],
                    'comment' => $reviews[array_rand($reviews)]['comment'],
                    'request_id' => $request->id,
                    'user_id' => $users->random()->id,
                ]);
            }
        }
    }
} 