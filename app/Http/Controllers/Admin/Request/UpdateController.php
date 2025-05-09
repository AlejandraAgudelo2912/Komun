<?php

namespace App\Http\Controllers\Admin\Request;

use App\Http\Controllers\Controller;
use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\RedirectResponse;

class UpdateController extends Controller
{
    public function __invoke(HttpRequest $request, Request $requestModel): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date|after:today',
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);

        $requestModel->update($validated);

        return redirect()
            ->route('admin.requests.index')
            ->with('success', 'Solicitud actualizada correctamente.');
    }
} 