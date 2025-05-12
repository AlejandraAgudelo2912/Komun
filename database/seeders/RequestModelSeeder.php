<?php

namespace Database\Seeders;

use App\Models\RequestModel;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class RequestModelSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            return;
        }

        $requestsModel = [
            [
                'title' => 'Necesito ayuda con la limpieza semanal',
                'description' => 'Busco alguien que me ayude con la limpieza de mi casa una vez por semana',
                'status' => 'pending',
                'priority' => 'medium',
                'location' => 'Madrid Centro',
                'deadline' => now()->addDays(7),
            ],
            [
                'title' => 'Clases de matemáticas para mi hijo',
                'description' => 'Necesito un profesor particular para mi hijo de 12 años',
                'status' => 'in_progress',
                'priority' => 'high',
                'location' => 'Online',
                'deadline' => now()->addDays(14),
            ],
            [
                'title' => 'Paseo de perro por las mañanas',
                'description' => 'Busco alguien que pasee a mi perro de 8:00 a 9:00 de lunes a viernes',
                'status' => 'completed',
                'priority' => 'low',
                'location' => 'Madrid Norte',
                'deadline' => now()->addDays(30),
            ],
            [
                'title' => 'Reparación de grifo',
                'description' => 'El grifo de la cocina gotea y necesito que lo arreglen',
                'status' => 'pending',
                'priority' => 'high',
                'location' => 'Madrid Sur',
                'deadline' => now()->addDays(2),
            ],
            [
                'title' => 'Cuidado de plantas durante vacaciones',
                'description' => 'Necesito alguien que riegue mis plantas mientras estoy de vacaciones',
                'status' => 'in_progress',
                'priority' => 'medium',
                'location' => 'Madrid Este',
                'deadline' => now()->addDays(10),
            ]
        ];

        foreach ($requestsModel as $requestData) {
            RequestModel::create([
                'title' => $requestData['title'],
                'description' => $requestData['description'],
                'status' => $requestData['status'],
                'priority' => $requestData['priority'],
                'location' => $requestData['location'],
                'deadline' => $requestData['deadline'],
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
            ]);
        }
    }
}
