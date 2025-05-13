<?php

namespace App\Http\Controllers\God\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;

class ShowController extends Controller
{
    public function __invoke(RequestModel $requestModel)
    {
        return view('god.requests.show', compact('requestModel'));
    }
}
