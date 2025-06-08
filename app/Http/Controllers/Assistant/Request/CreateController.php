<?php

namespace App\Http\Controllers\Assistant\Request;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;

class CreateController extends Controller
{
    public function __invoke(): View
    {
        $categories = Category::all();

        return view('assistant.requests.create', compact('categories'));
    }
}
