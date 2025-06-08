<?php

namespace App\Http\Controllers\NeedHelp\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;

class DestroyController extends Controller
{
    public function __invoke(HttpRequest $request, RequestModel $requestModel): RedirectResponse
    {

        $requestModel->delete();

        return redirect()
            ->route('needhelp.requests.index')
            ->with('success', 'Solicitud eliminada correctamente.');
    }
}
