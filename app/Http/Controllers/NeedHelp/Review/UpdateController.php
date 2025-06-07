<?php

namespace App\Http\Controllers\NeedHelp\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class UpdateController extends Controller
{
    public function __invoke(Request $request, Review $review): RedirectResponse
    {
        $review->load('requestModel');

        // Verificar que la reseña pertenece al usuario
        if ($review->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'No tienes permiso para actualizar esta reseña.');
        }

        // Verificar que la solicitud está completada
        if ($review->requestModel->status !== 'completed') {
            return redirect()->back()->with('error', 'Solo puedes actualizar reseñas de solicitudes completadas.');
        }

        // Validar los datos de la reseña
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Actualizar la reseña
        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Actualizar el rating promedio del asistente
        $review->assistant->updateRating();

        return redirect()
            ->route('needhelp.requests.show', $review->requestModel)
            ->with('success', 'Reseña actualizada correctamente.');
    }
} 