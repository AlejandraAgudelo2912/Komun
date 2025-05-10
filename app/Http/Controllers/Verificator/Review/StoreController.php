<?php

namespace App\Http\Controllers\Verificator\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use App\Models\Request;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    public function __invoke(StoreReviewRequest $request, Request $requestModel): RedirectResponse
    {
        Review::create([
            'request_id' => $requestModel->id,
            'user_id' => auth()->id(),
            'assistant_id' => $requestModel->assistant_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()
            ->with('success', 'Valoración enviada correctamente.');
    }
} 