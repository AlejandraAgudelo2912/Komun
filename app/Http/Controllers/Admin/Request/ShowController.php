<?php

namespace App\Http\Controllers\Admin\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(RequestModel $requestModel)
    {
        return view('admin.requests.show', compact('requestModel'));
    }
}
