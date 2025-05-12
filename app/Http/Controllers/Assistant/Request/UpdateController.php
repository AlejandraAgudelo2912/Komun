<?php

namespace App\Http\Controllers\Assistant\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\RedirectResponse;

class UpdateController extends Controller
{
    public function __invoke(HttpRequest $request, RequestModel $requestModel): RedirectResponse
    {
        if ($request->user()->id !== $requestModel->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date|after:today',
        ]);

        $requestModel->update($validated);

        return redirect()
            ->route('assistant.requests.index')
            ->with('success', 'Solicitud actualizada correctamente.');
    }
}
