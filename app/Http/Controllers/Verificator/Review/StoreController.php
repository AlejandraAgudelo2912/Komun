<?php

namespace App\Http\Controllers\Verificator\Review;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request, RequestModel $requestModel): RedirectResponse
    {
        // Verificar que la solicitud está completada
        if ($requestModel->status !== 'completed') {
            return redirect()->back()->with('error', 'Solo puedes calificar solicitudes completadas.');
        }

        // Verificar que no existe una reseña previa
        if ($requestModel->reviews()->exists()) {
            return redirect()->back()->with('error', 'Esta solicitud ya tiene una calificación.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // Obtener el asistente aceptado
        $assistant = $requestModel->applicants()
            ->wherePivot('status', 'accepted')
            ->first();

        if (! $assistant) {
            return redirect()->back()->with('error', 'No hay un asistente aceptado para esta solicitud.');
        }

        $review = Review::create([
            'request_models_id' => $requestModel->id,
            'user_id' => auth()->id(),
            'assistant_id' => $assistant->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Actualizar el rating promedio del asistente
        $assistant->updateRating();

        return redirect()->back()
            ->with('success', 'Calificación registrada correctamente.');
    }
}
