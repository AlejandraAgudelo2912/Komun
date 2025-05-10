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
                'description' => 'Servicios de limpieza y mantenimiento del hogar',
                'icon' => 'broom',
                'color' => '#4CAF50'
            ],
            [
                'name' => 'Jardinería',
                'description' => 'Cuidado y mantenimiento de jardines y plantas',
                'icon' => 'leaf',
                'color' => '#8BC34A'
            ],
            [
                'name' => 'Reparaciones',
                'description' => 'Reparaciones y mantenimiento del hogar',
                'icon' => 'tools',
                'color' => '#FF9800'
            ],
            [
                'name' => 'Cuidado de Personas',
                'description' => 'Cuidado de niños, ancianos o personas dependientes',
                'icon' => 'heart',
                'color' => '#E91E63'
            ],
            [
                'name' => 'Mascotas',
                'description' => 'Cuidado y paseo de mascotas',
                'icon' => 'paw',
                'color' => '#9C27B0'
            ],
            [
                'name' => 'Transporte',
                'description' => 'Servicios de transporte y mudanzas',
                'icon' => 'car',
                'color' => '#2196F3'
            ],
            [
                'name' => 'Educación',
                'description' => 'Clases particulares y apoyo escolar',
                'icon' => 'book',
                'color' => '#3F51B5'
            ],
            [
                'name' => 'Tecnología',
                'description' => 'Ayuda con dispositivos electrónicos y software',
                'icon' => 'laptop',
                'color' => '#607D8B'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 