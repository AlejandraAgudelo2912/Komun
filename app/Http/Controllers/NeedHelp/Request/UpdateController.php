<?php

namespace App\Http\Controllers\NeedHelp\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRequestRequest;
use App\Models\Request;
use Illuminate\Http\RedirectResponse;

class UpdateController extends Controller
{

    public function __invoke(UpdateRequestRequest $request, Request $requestModel): RedirectResponse
    {
        $requestModel->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('needhelp.requests.index')
            ->with('success', 'Solicitud actualizada correctamente.');
    }
} 