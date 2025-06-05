<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Limpieza',
                'description' => 'Servicios de limpieza para hogares y oficinas',
                'icon' => 'broom',
                'color' => '#4CAF50',
            ],
            [
                'name' => 'Jardinería',
                'description' => 'Cuidado y mantenimiento de jardines',
                'icon' => 'leaf',
                'color' => '#8BC34A',
            ],
            [
                'name' => 'Reparaciones',
                'description' => 'Servicios de reparación y mantenimiento',
                'icon' => 'tools',
                'color' => '#FF9800',
            ],
            [
                'name' => 'Cuidado de niños',
                'description' => 'Servicios de cuidado infantil',
                'icon' => 'baby',
                'color' => '#2196F3',
            ],
            [
                'name' => 'Cuidado de mascotas',
                'description' => 'Servicios para el cuidado de mascotas',
                'icon' => 'paw',
                'color' => '#9C27B0',
            ],
            [
                'name' => 'Transporte',
                'description' => 'Servicios de transporte y mudanzas',
                'icon' => 'truck',
                'color' => '#F44336',
            ],
        ];

        foreach ($categories as $category) {
            Category::factory()->create($category);
        }
    }
} 