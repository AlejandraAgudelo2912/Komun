<?php

namespace App\Http\Controllers\Admin\Request;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;

class CreateController extends Controller
{
    public function __invoke(): View
    {
        $categories = Category::all();
        
        return view('admin.requests.create', compact('categories'));
    }
} 