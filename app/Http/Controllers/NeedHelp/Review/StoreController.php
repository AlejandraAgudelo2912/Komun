<?php

namespace App\Http\Controllers\NeedHelp\Review;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request, RequestModel $requestModel): RedirectResponse
    {
        // Verificar que la solicitud pertenece al usuario y está completada
        if ($requestModel->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'No tienes permiso para calificar esta solicitud.');
        }

        if ($requestModel->status !== 'completed') {
            return redirect()->back()->with('error', 'Solo puedes calificar solicitudes completadas.');
        }

        // Validar que el asistente existe y está aceptado en la solicitud
        $assistant = $requestModel->applicants()
            ->where('users.id', $request->assistant_id)
            ->wherePivot('status', 'accepted')
            ->first();

        if (! $assistant) {
            return redirect()->back()->with('error', 'El asistente seleccionado no es válido.');
        }

        // Validar que no existe una reseña previa para este asistente
        $existingReview = Review::where('request_models_id', $requestModel->id)
            ->where('user_id', auth()->id())
            ->where('assistant_id', $assistant->id)
            ->exists();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Ya has calificado a este asistente para esta solicitud.');
        }

        // Validar los datos de la reseña
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Crear la reseña
        Review::create([
            'request_models_id' => $requestModel->id,
            'user_id' => auth()->id(),
            'assistant_id' => $assistant->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Actualizar el rating promedio del asistente
        $assistant->updateRating();

        // Verificar si quedan más asistentes por calificar
        $remainingApplicants = $requestModel->applicants()
            ->wherePivot('status', 'accepted')
            ->whereDoesntHave('reviews', function ($query) use ($requestModel) {
                $query->where('request_models_id', $requestModel->id)
                    ->where('user_id', auth()->id());
            })
            ->exists();

        if ($remainingApplicants) {
            return redirect()
                ->route('needhelp.reviews.create', $requestModel)
                ->with('success', 'Calificación enviada correctamente. Puedes continuar calificando a los demás asistentes.');
        }

        return redirect()
            ->route('needhelp.requests.show', $requestModel)
            ->with('success', '¡Has completado todas las calificaciones!');
    }
}
