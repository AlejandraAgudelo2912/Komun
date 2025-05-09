<?php

namespace App\Http\Controllers\God\Request;

use App\Http\Controllers\Controller;
use App\Models\Request;
use App\Models\Category;
use Illuminate\View\View;

class EditController extends Controller
{
    public function __invoke(Request $requestModel): View
    {
        $categories = Category::all();
        
        return view('god.requests.edit', compact('requestModel', 'categories'));
    }
} 