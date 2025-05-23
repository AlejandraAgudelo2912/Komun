<?php

namespace App\Http\Controllers\Assistant\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\RedirectResponse;

class DestroyController extends Controller
{
    public function __invoke(HttpRequest $request, RequestModel $requestModel): RedirectResponse
    {
        $requestModel->delete();

        return redirect()
            ->route('assistant.requests.index')
            ->with('success', 'Solicitud eliminada correctamente.');
    }
}
