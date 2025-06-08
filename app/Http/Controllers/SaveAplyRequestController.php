<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SaveAplyRequestController extends Controller
{
    public function __invoke(HttpRequest $httpRequest, RequestModel $requestModel)
    {
        if (Gate::denies('apply', $requestModel)) {
            abort(403, 'No tienes permiso para aplicar a esta solicitud.');
        }

        $validated = $httpRequest->validate([
            'message' => 'required|string|max:1000',
        ]);

        $requestModel->applicants()->syncWithoutDetaching([
            Auth::id() => [
                'message' => $validated['message'],
                'status' => 'pending',
            ],
        ]);

        return redirect()->route('assistant.requests.show', $requestModel->id)
            ->with('success', 'Has aplicado exitosamente a esta solicitud.');
    }
}
