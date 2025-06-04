<?php

namespace App\Http\Controllers\Verificator\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\View\View;

class EditController extends Controller
{
    public function __invoke(Review $review): View
    {
        return view('verificator.reviews.edit', [
            'review' => $review,
        ]);
    }
} 