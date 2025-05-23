<?php

namespace App\Http\Controllers\NeedHelp\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use App\Models\Category;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class EditController extends Controller
{
    public function __invoke(HttpRequest $request, RequestModel $requestModel): View
    {
        $categories = Category::all();

        return view('needhelp.requests.edit', compact('requestModel', 'categories'));
    }
}
