<?php

namespace App\Http\Controllers\Admin\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel as RequestModel;
use App\Models\Category;
use Illuminate\View\View;

class EditController extends Controller
{
    public function __invoke(RequestModel $requestModel): View
    {
        $categories = Category::all();

        return view('admin.requests.edit', [
            'requestModel' => $requestModel,
            'categories' => $categories
        ]);
    }
}
