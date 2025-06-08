<?php

namespace App\Http\Controllers\God\Request;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\RequestModel;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(): View
    {
        $requests = RequestModel::all();
        $categories = Category::all();

        return view('god.requests.index', compact('requests', 'categories'));
    }
}
