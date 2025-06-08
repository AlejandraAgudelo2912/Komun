<?php

namespace App\Http\Controllers\God\Request;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\RequestModel;
use Illuminate\View\View;

class EditController extends Controller
{
    public function __invoke(RequestModel $requestModel): View
    {
        $categories = Category::all();

        return view('god.requests.edit', compact('requestModel', 'categories'));
    }
}
