<?php

namespace App\Http\Controllers\God\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;

class EditController extends Controller
{
    public function __invoke(Category $category)
    {
        return view('god.categories.edit', compact('category'));
    }
}
