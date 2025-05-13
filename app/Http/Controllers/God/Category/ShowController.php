<?php

namespace App\Http\Controllers\God\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;

class ShowController extends Controller
{
    public function __invoke(Category $category)
    {
        return view('god.categories.show', [
            'category' => $category,
        ]);
    }
}
