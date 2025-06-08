<?php

namespace App\Http\Controllers\Assistant\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\View\View;

class ShowController extends Controller
{
    public function __invoke(RequestModel $requestModel): View
    {
        $requestModel->load(['category', 'user']);

        return view('assistant.requests.show', compact('requestModel'));
    }
}
