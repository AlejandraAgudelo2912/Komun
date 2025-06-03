<?php

namespace App\Http\Controllers;

use App\Models\Assistant;
use App\Models\AssistantVerificationDocument;
use Illuminate\Http\Request;

class AssistantStoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'availability' => ['nullable', 'array'],
            'skills' => ['nullable', 'string'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive,suspended'],
            'dni_front' => ['required', 'image'],
            'dni_back' => ['required', 'image'],
            'selfie' => ['required', 'image'],
        ]);

        $validated['user_id'] = auth()->id();
        
        // Convertir availability a un array asociativo de horarios
        $availability = [];
        foreach ($validated['availability'] as $day => $hours) {
            if (!empty($hours)) {
                $availability[$day] = $hours;
            }
        }
        $validated['availability'] = json_encode($availability);
        
        // Convertir skills a un array
        $validated['skills'] = json_encode(array_map('trim', explode(',', $validated['skills'])));

        $assistant = Assistant::create($validated);

        $dniFrontPath = $request->file('dni_front')->store('verifications/dni_front', 'public');
        $dniBackPath = $request->file('dni_back')->store('verifications/dni_back', 'public');
        $selfiePath = $request->file('selfie')->store('verifications/selfies', 'public');

        AssistantVerificationDocument::create([
            'assistant_id' => $assistant->id,
            'dni_front_path' => $dniFrontPath,
            'dni_back_path' => $dniBackPath,
            'selfie_path' => $selfiePath,
            'status' => 'pending',
        ]);

        return redirect()->route('welcome')->with('success', 'Tu perfil de asistente se ha enviado para revisión. Un verificador lo evaluará pronto.');
    }
}

