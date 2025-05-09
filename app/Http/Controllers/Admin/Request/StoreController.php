<?php

namespace App\Http\Controllers\Admin\Request;

use App\Http\Controllers\Controller;
use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\RedirectResponse;

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
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);

        $request = Request::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'location' => $validated['location'],
            'deadline' => $validated['deadline'],
            'user_id' => $request->user()->id,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.requests.index')
            ->with('success', 'Solicitud creada correctamente.');
    }
} 