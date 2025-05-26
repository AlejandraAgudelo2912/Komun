<?php

namespace App\Http\Controllers;

use App\Models\Assistant;
use Illuminate\Support\Facades\Request;

class AssistantStoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'bio' => ['nullable', 'string'],
            'availability' => ['nullable', 'json'],
            'skills' => ['nullable', 'json'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive,suspended'],
        ]);

        $validated['user_id'] = auth()->id();

        Assistant::create($validated);

        return redirect()->route('assistants.index')->with('success', 'Asistente creado correctamente.');

    }
}
