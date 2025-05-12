<?php

namespace App\Http\Controllers\God\Request;

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
            'status' => $request->status,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('god.requests.index')
            ->with('success', 'Solicitud creada correctamente.');
    }
}
