<?php

namespace App\Http\Controllers\NeedHelp\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;

class ShowController extends Controller
{
    public function __invoke(Category $category)
    {
        return view('needHelp.categories.show', [
            'category' => $category,
        ]);

    }
}
