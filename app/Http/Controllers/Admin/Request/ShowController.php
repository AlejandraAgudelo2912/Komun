<?php

namespace App\Http\Controllers\Admin\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;

class ShowController extends Controller
{
    public function __invoke(RequestModel $request)
    {
        return view('admin.requests.show', compact('request'));
    }
}
