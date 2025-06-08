<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;

class AplyRequestController extends Controller
{
    public function __invoke(RequestModel $requestModel)
    {
        return view('aply-request', compact('requestModel'));
    }
}
