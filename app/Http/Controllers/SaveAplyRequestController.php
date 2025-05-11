<?php

namespace App\Http\Controllers;

class SaveAplyRequestController extends Controller
{
    public function __invoke()
    {
        //dd para ver el contenido de la solicitud
        dd(request()->all());
        // Validate the request data
        $validatedData = request()->validate([
            'message' => 'required|string|max:500',
        ]);

        //se guarda en la tabla requests_applications
        request()->user()->request_application()->create($validatedData);

        return redirect()
            ->route('home')
            ->with('success', 'Solicitud enviada correctamente.');
    }
}
