<?php

namespace App\Http\Controllers\Verificator\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;

class ShowController extends Controller
{
    public function __invoke(Category $category)
    {
        return view('verificator.categories.show', [
            'category' => $category,
        ]);

    }
}
