<?php

namespace Database\Seeders;

use App\Models\Assistant;
use App\Models\AssistantVerificationDocument;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AssistantVerificationSeeder extends Seeder
{
    public function run(): void
    {
        Role::findOrCreate('verificator');
        Role::findOrCreate('assistant');

        for ($i = 1; $i <= 5; $i++) {
            $user = User::factory()->create([
                'name' => "Asistente $i",
                'email' => "asistente$i@komun.com",
                'password' => Hash::make('password'),
            ]);

            $assistant = Assistant::create([
                'user_id' => $user->id,
                'bio' => 'Me encanta ayudar a mi comunidad.',
                'availability' => json_encode(['lunes', 'miércoles']),
                'skills' => json_encode(['escuchar', 'acompañar']),
                'experience_years' => rand(0, 5),
                'status' => 'active',
            ]);

            AssistantVerificationDocument::create([
                'assistant_id' => $assistant->id,
                'dni_front_path' => 'verifications/dni_front/fake_front.jpg',
                'dni_back_path' => 'verifications/dni_back/fake_back.jpg',
                'selfie_path' => 'verifications/selfies/fake_selfie.jpg',
                'status' => 'pending',
            ]);
        }

    }
}
