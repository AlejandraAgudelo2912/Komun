<?php

namespace Database\Seeders;

use App\Models\Assistant;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssistantSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            return;
        }

        Assistant::factory()
            ->count(5)
            ->verified()
            ->active()
            ->create();

        Assistant::factory()
            ->count(3)
            ->unverified()
            ->active()
            ->create();

        Assistant::factory()
            ->count(2)
            ->verified()
            ->suspended()
            ->create();

        Assistant::factory()
            ->count(1)
            ->verified()
            ->inactive()
            ->create();

        $assistants = [
            [
                'bio' => 'Experto en limpieza y organización del hogar con más de 5 años de experiencia.',
                'availability' => 'Lunes a Viernes, 9:00 - 18:00',
                'experience_years' => 5,
            ],
            [
                'bio' => 'Profesor particular de matemáticas y ciencias con amplia experiencia en educación.',
                'availability' => 'Lunes a Sábado, 16:00 - 20:00',
                'experience_years' => 3,
            ],
            [
                'bio' => 'Cuidador profesional de mascotas con certificación en primeros auxilios veterinarios.',
                'availability' => 'Todos los días, 7:00 - 22:00',
                'experience_years' => 2,
            ],
            [
                'bio' => 'Técnico en reparaciones domésticas con especialidad en fontanería y electricidad.',
                'availability' => 'Lunes a Viernes, 8:00 - 20:00',
                'experience_years' => 8,
            ],
            [
                'bio' => 'Jardinero profesional con experiencia en diseño y mantenimiento de jardines.',
                'availability' => 'Lunes a Sábado, 7:00 - 19:00',
                'experience_years' => 4,
            ],
        ];

        $experienceLevels = ['beginner', 'intermediate', 'advanced', 'expert'];

        foreach ($assistants as $assistantData) {
            $assistant = Assistant::create([
                'bio' => $assistantData['bio'],
                'availability' => $assistantData['availability'],
                'experience_years' => $assistantData['experience_years'],
                'user_id' => $users->random()->id,
            ]);

            $selectedCategories = $categories->random(rand(1, 3));

            foreach ($selectedCategories as $category) {
                $assistant->categories()->attach($category->id, [
                    'experience_level' => $experienceLevels[array_rand($experienceLevels)],
                    'years_of_experience' => rand(1, 10),
                    'notes' => 'Experiencia en '.$category->name.' con '.rand(1, 10).' años de práctica.',
                ]);
            }
        }
    }
}
