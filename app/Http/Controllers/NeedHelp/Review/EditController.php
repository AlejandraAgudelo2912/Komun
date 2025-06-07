<?php

namespace App\Http\Controllers\NeedHelp\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EditController extends Controller
{
    public function __invoke(Review $review): View|RedirectResponse
    {
        $review->load('requestModel');

        // Verificar que la reseña pertenece al usuario
        if ($review->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'No tienes permiso para editar esta reseña.');
        }

        // Verificar que la solicitud está completada
        if ($review->requestModel->status !== 'completed') {
            return redirect()->back()->with('error', 'Solo puedes editar reseñas de solicitudes completadas.');
        }

        return view('needhelp.reviews.edit', [
            'review' => $review,
        ]);
    }
} 