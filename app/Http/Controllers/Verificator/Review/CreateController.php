<?php

namespace App\Http\Controllers\Verificator\Review;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\View\View;

class CreateController extends Controller
{
    public function __invoke(RequestModel $requestModel): View
    {
        // Verificar que la solicitud está completada
        if ($requestModel->status !== 'completed') {
            return redirect()->back()->with('error', 'Solo puedes calificar solicitudes completadas.');
        }

        // Verificar que no existe una reseña previa
        if ($requestModel->reviews()->exists()) {
            return redirect()->back()->with('error', 'Esta solicitud ya tiene una calificación.');
        }

        return view('verificator.reviews.create', [
            'requestModel' => $requestModel,
        ]);
    }
}
