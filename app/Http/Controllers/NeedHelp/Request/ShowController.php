<?php

namespace App\Http\Controllers\NeedHelp\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class ShowController extends Controller
{
    public function __invoke(HttpRequest $request, RequestModel $requestModel): View
    {
        $requestModel->load(['category', 'applicants']);

        return view('needhelp.requests.show', compact('requestModel'));
    }
}
