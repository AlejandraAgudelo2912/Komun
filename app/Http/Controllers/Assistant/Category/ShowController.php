<?php

namespace App\Http\Controllers\Assistant\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;

class ShowController extends Controller
{
    public function __invoke(Category $category)
    {
        return view('assistant.categories.show', [
            'category' => $category,
        ]);
    }
}
