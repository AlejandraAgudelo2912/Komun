<?php

namespace App\Http\Controllers\Assistant\Request;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\RequestModel;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class EditController extends Controller
{
    public function __invoke(HttpRequest $request, RequestModel $requestModel): View
    {
        $categories = Category::all();

        return view('assistant.requests.edit', compact('requestModel', 'categories'));
    }
}
