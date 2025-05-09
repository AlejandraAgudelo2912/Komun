<?php

namespace App\Http\Controllers\Verificator\Request;

use App\Http\Controllers\Controller;
use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(HttpRequest $request): View
    {
        $requests = $request->user()->requests;
        
        return view('verificator.requests.index', compact('requests'));
    }
} 