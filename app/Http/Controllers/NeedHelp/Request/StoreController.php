<?php

namespace App\Http\Controllers\NeedHelp\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequestRequest;
use App\Models\RequestModel;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    public function __invoke(StoreRequestRequest $request): RedirectResponse
    {
        $requestModel = RequestModel::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'max_applications' => $request->max_applications,
            'help_notes' => $request->help_notes,
            'is_urgent' => $request->has('is_urgent'),
            'is_verified' => $request->has('is_verified'),
            'status' => 'pending',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('needhelp.requests.index')
            ->with('success', 'Solicitud creada correctamente.');
    }
}
