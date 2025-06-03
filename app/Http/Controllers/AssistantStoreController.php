<?php

namespace App\Http\Controllers;

use App\Models\Assistant;
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
        ]);

        $validated['user_id'] = auth()->id();
        $validated['availability'] = json_encode($validated['availability'] ?? []);
        $validated['skills'] = json_encode(explode(',', $validated['skills']));

        Assistant::create($validated);

        //asiganr rol de assitente
        $user = auth()->user();
        $user->syncRoles('assistant');

        return redirect()->route('welcome')->with('success', 'Asistente creado correctamente.');
    }
}
