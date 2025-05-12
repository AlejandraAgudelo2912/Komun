<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request as HttpRequest;
use App\Models\RequestModel as RequestModel;
use Illuminate\Support\Facades\Auth;

class SaveAplyRequestController extends Controller
{
    public function __invoke(HttpRequest $httpRequest, RequestModel $requestModel)
    {
        // Validar los datos del formulario
        $validated = $httpRequest->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Guardar en la tabla pivote
        $requestModel->applicants()->syncWithoutDetaching([
            Auth::id() => [
                'message' => $validated['message'],
            ]
        ]);

        // Redireccionar con mensaje de éxito
        return redirect()->route('assistant.requests.show', $requestModel->id)
            ->with('success', 'Has aplicado exitosamente a esta solicitud.');
    }
}
