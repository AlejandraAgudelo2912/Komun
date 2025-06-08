<?php

namespace App\Http\Controllers\Assistant\Request;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\RequestModel;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(RequestModel $requestModel): View
    {
        $requestsModel = RequestModel::Active()
            ->paginate(10);
        $categories = Category::all();

        return view('assistant.requests.index', compact('requestsModel', 'categories'));
    }
}
