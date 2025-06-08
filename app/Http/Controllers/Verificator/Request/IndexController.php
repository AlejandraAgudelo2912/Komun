<?php

namespace App\Http\Controllers\Verificator\Request;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(HttpRequest $request): View
    {
        $requests = $request->user()->requests;
        $categories = Category::all();

        return view('verificator.requests.index', compact('requests', 'categories'));
    }
}
