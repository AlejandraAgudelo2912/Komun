<?php

namespace App\Http\Controllers\NeedHelp\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(): View
    {
        $categories = Category::all();

        return view('needhelp.categories.index', compact('categories'));
    }
}
