<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request as HttpRequest;
use App\Models\RequestModel as RequestModel;
use Illuminate\Support\Facades\Auth;

class SaveAplyRequestController extends Controller
{
    public function __invoke(HttpRequest $httpRequest, RequestModel $requestModel)
    {
        $validated = $httpRequest->validate([
            'message' => 'required|string|max:1000',
        ]);

        $requestModel->applicants()->syncWithoutDetaching([
            Auth::id() => [
                'message' => $validated['message'],
            ]
        ]);

        return redirect()->route('assistant.requests.show', $requestModel->id)
            ->with('success', 'Has aplicado exitosamente a esta solicitud.');
    }
}
