<?php

namespace App\Http\Controllers\Verificator\Request;

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

        return view('verificator.requests.edit', compact('requestModel', 'categories'));
    }
}
