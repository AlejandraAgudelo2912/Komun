<?php

namespace Database\Seeders;

use App\Models\Assistant;
use App\Models\AssistantVerificationDocument;
use Illuminate\Database\Seeder;

class AssistantVerificationDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $assistants = Assistant::all();

        if ($assistants->isEmpty()) {
            return;
        }

        foreach ($assistants as $assistant) {
            if ($assistant->is_verified) {
                AssistantVerificationDocument::create([
                    'assistant_id' => $assistant->id,
                    'dni_front_path' => 'verification/dni_front_' . $assistant->id . '.jpg',
                    'dni_back_path' => 'verification/dni_back_' . $assistant->id . '.jpg',
                    'selfie_path' => 'verification/selfie_' . $assistant->id . '.jpg',
                    'status' => 'approved',
                ]);
            }
            else {
                AssistantVerificationDocument::create([
                    'assistant_id' => $assistant->id,
                    'dni_front_path' => 'verification/dni_front_' . $assistant->id . '.jpg',
                    'dni_back_path' => 'verification/dni_back_' . $assistant->id . '.jpg',
                    'selfie_path' => 'verification/selfie_' . $assistant->id . '.jpg',
                    'status' => 'pending',
                ]);
            }
        }

        AssistantVerificationDocument::create([
            'assistant_id' => $assistants->first()->id,
            'dni_front_path' => 'verification/dni_front_rejected.jpg',
            'dni_back_path' => 'verification/dni_back_rejected.jpg',
            'selfie_path' => 'verification/selfie_rejected.jpg',
            'status' => 'rejected',
            'rejection_reason' => 'Documentos no legibles o incompletos',
        ]);
    }
} 