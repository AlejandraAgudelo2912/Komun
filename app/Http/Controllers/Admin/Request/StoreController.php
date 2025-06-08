<?php

namespace App\Http\Controllers\Admin\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequestRequest;
use App\Models\RequestModel;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    public function __invoke(StoreRequestRequest $request): RedirectResponse
    {
        RequestModel::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'status' => $request->status,
            'user_id' => auth()->id(),
            'location' => $request->location,
            'max_applications' => $request->max_applications,
            'help_notes' => $request->help_notes,
            'is_urgent' => $request->has('is_urgent'),
            'is_verified' => $request->has('is_verified'),
        ]);

        return redirect()->route('admin.requests.index')
            ->with('success', 'Solicitud creada correctamente.');
    }
}
