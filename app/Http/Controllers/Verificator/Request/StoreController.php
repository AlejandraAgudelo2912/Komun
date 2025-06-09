<?php

namespace App\Http\Controllers\Verificator\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;

class StoreController extends Controller
{
    public function __invoke(HttpRequest $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date|after:today',
            'priority' => 'required|in:low,medium,high',
            'verification_notes' => 'nullable|string',
        ]);

        $request = RequestModel::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'location' => $validated['location'],
            'deadline' => $validated['deadline'],
            'priority' => $validated['priority'],
            'verification_notes' => $validated['verification_notes'],
            'user_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('verificator.requests.index')
            ->with('success', 'Solicitud creada correctamente.');
    }
}
