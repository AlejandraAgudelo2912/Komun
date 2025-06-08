<?php

namespace App\Http\Controllers\Admin\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class DestroyController extends Controller
{
    public function __invoke(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->back()
            ->with('success', 'Valoración eliminada correctamente.');
    }
}
