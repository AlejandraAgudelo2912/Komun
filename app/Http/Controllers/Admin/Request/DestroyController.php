<?php

namespace App\Http\Controllers\Admin\Request;

use App\Http\Controllers\Controller;
use App\Models\Request;
use Illuminate\Http\RedirectResponse;

class DestroyController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $request->delete();

        return redirect()
            ->route('admin.requests.index')
            ->with('success', 'Solicitud eliminada correctamente.');
    }
} 