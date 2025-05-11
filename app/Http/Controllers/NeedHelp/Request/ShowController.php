<?php

namespace App\Http\Controllers\NeedHelp\Request;

use App\Http\Controllers\Controller;
use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class ShowController extends Controller
{
    public function __invoke(HttpRequest $request, Request $requestModel): View
    {
        // Cargar las relaciones necesarias
        $requestModel->load(['category', 'applicants']);

        return view('needhelp.requests.show', compact('request'));
    }
} 