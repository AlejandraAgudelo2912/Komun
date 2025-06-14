<?php

namespace App\Http\Controllers\Assistant\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(): View
    {
        $categories = Category::all();

        return view('assistant.categories.index', compact('categories'));
    }
}
