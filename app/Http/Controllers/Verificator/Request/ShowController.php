<?php

namespace App\Http\Controllers\Verificator\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;

class ShowController extends Controller
{
    public function __invoke(RequestModel $requestModel)
    {
        return view('verificator.requests.show', [
            'requestModel' => $requestModel,
        ]);

    }
}
