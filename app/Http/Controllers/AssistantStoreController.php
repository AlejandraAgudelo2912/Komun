<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssistantVerificationRequest;
use App\Models\Assistant;
use App\Models\AssistantVerificationDocument;

class AssistantStoreController extends Controller
{
    public function __invoke(AssistantVerificationRequest $request)
    {
        $validated = $request->validated();

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'active';

        $availability = [];
        foreach ($validated['availability'] as $day => $hours) {
            if (! empty($hours)) {
                $availability[$day] = array_map('trim', explode(',', $hours));
            }
        }
        $validated['availability'] = json_encode($availability);

        $validated['skills'] = json_encode(array_map('trim', explode(',', $validated['skills'])));

        $assistant = Assistant::create($validated);

        $dniFrontPath = $request->file('dni_front')->store('verifications/dni_front', 'public');
        $dniBackPath = $request->file('dni_back')->store('verifications/dni_back', 'public');
        $selfiePath = $request->file('selfie')->store('verifications/selfies', 'public');

        $verification = AssistantVerificationDocument::create([
            'assistant_id' => $assistant->id,
            'dni_front_path' => $dniFrontPath,
            'dni_back_path' => $dniBackPath,
            'selfie_path' => $selfiePath,
            'status' => 'pending',
        ]);

        event(new \App\Events\VerificationDocumentSubmittedEvent($verification));

        return redirect()->route('welcome')->with('success', 'Tu perfil de asistente se ha creado correctamente. Un verificador lo evaluará pronto.');

    }
}
