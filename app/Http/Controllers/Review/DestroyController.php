<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class DestroyController extends Controller
{
    
    public function __invoke(Review $review): RedirectResponse
    {
        if (auth()->id() !== $review->user_id && !auth()->user()->hasRole(['admin', 'god'])) {
            abort(403);
        }

        $review->delete();

        return redirect()->back()
            ->with('success', 'Valoración eliminada correctamente.');
    }
} 