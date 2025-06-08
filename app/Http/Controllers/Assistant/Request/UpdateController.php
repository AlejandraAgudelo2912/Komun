<?php

namespace App\Http\Controllers\Assistant\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;

class UpdateController extends Controller
{
    public function __invoke(HttpRequest $request, RequestModel $requestModel): RedirectResponse
    {

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
