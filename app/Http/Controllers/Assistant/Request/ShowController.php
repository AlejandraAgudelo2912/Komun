<?php

namespace App\Http\Controllers\Assistant\Request;

use App\Http\Controllers\Controller;
use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class ShowController extends Controller
{
    public function __invoke(HttpRequest $request, Request $requestModel): View
    {
        $requestModel->load(['category', 'user']);

        return view('assistant.requests.show', compact('request'));
    }
}
