<?php

namespace App\Http\Controllers\Verificator\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\RedirectResponse;

class DestroyController extends Controller
{
    public function __invoke(HttpRequest $request, RequestModel $requestModel): RedirectResponse
    {
        if ($request->user()->id !== $requestModel->user_id) {
            abort(403);
        }

        $requestModel->delete();

        return redirect()
            ->route('verificator.requests.index')
            ->with('success', 'Solicitud eliminada correctamente.');
    }
}
