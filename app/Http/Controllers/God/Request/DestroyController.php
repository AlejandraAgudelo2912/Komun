<?php

namespace App\Http\Controllers\God\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Http\RedirectResponse;

class DestroyController extends Controller
{
    public function __invoke(RequestModel $request): RedirectResponse
    {
        $request->delete();

        return redirect()
            ->route('god.requests.index')
            ->with('success', 'Solicitud eliminada correctamente.');
    }
}
