<?php

namespace App\Http\Controllers\Assistant\Request;

use App\Http\Controllers\Controller;
use App\Models\Request;
use App\Models\Category;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class EditController extends Controller
{
    public function __invoke(HttpRequest $request, Request $requestModel): View
    {
        if ($request->user()->id !== $requestModel->user_id) {
            abort(403);
        }

        $categories = Category::all();
        
        return view('assistant.requests.edit', compact('requestModel', 'categories'));
    }
} 