<?php

namespace App\Http\Controllers\Verificator\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\RequestModel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    public function __invoke(Request $request, RequestModel $requestModel): RedirectResponse
    {
        // Verificar que la solicitud está completada
        if ($requestModel->status !== 'completed') {
            return redirect()->back()->with('error', 'Solo puedes calificar solicitudes completadas.');
        }

        // Verificar que no existe una reseña previa
        if ($requestModel->review()->exists()) {
            return redirect()->back()->with('error', 'Esta solicitud ya tiene una calificación.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $review = Review::create([
            'request_models_id' => $requestModel->id,
            'user_id' => auth()->id(),
            'assistant_id' => $requestModel->assistant_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Actualizar el promedio de calificaciones del asistente
        $assistant = $requestModel->assistant->assistant;
        $assistant->total_reviews++;
        $assistant->rating = Review::where('assistant_id', $assistant->user_id)
            ->avg('rating');
        $assistant->save();

        return redirect()->back()
            ->with('success', 'Calificación registrada correctamente.');
    }
}
