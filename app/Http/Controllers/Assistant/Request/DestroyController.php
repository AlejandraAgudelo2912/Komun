<?php

namespace App\Http\Controllers\Assistant\Request;

use App\Http\Controllers\Controller;
use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\RedirectResponse;

class DestroyController extends Controller
{
    public function __invoke(HttpRequest $request, Request $requestModel): RedirectResponse
    {
        if ($request->user()->id !== $requestModel->user_id) {
            abort(403);
        }

        $requestModel->delete();

        return redirect()
            ->route('assistant.requests.index')
            ->with('success', 'Solicitud eliminada correctamente.');
    }
} 