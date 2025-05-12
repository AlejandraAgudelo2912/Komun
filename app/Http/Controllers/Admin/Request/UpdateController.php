<?php

namespace App\Http\Controllers\Admin\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRequestRequest;
use App\Models\RequestModel;
use Illuminate\Http\RedirectResponse;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequestRequest $request, RequestModel $requestModel): RedirectResponse
    {
        $requestModel->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'deadline' => $request->deadline,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.requests.index')
            ->with('success', 'Solicitud actualizada correctamente.');
    }
}
