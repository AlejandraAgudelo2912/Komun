<?php

namespace App\Http\Controllers\NeedHelp\Review;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CreateController extends Controller
{
    public function __invoke(RequestModel $requestModel): View|RedirectResponse
    {
        // Verificar que la solicitud pertenece al usuario y está completada
        if ($requestModel->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'No tienes permiso para calificar esta solicitud.');
        }

        if ($requestModel->status !== 'completed') {
            return redirect()->back()->with('error', 'Solo puedes calificar solicitudes completadas.');
        }

        // Obtener todos los asistentes aceptados que no han sido calificados
        $acceptedApplicants = $requestModel->applicants()
            ->wherePivot('status', 'accepted')
            ->whereDoesntHave('reviews', function ($query) use ($requestModel) {
                $query->where('request_models_id', $requestModel->id)
                      ->where('user_id', auth()->id());
            })
            ->get();

        if ($acceptedApplicants->isEmpty()) {
            return redirect()->back()->with('error', 'No hay asistentes pendientes de calificar para esta solicitud.');
        }

        return view('needhelp.reviews.create', [
            'requestModel' => $requestModel,
            'acceptedApplicants' => $acceptedApplicants,
        ]);
    }
} 