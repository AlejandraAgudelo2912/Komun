<?php

namespace App\Http\Controllers\God\Request;

use App\Http\Controllers\Controller;
use App\Models\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(): View
    {
        $requests = Request::all();
        
        return view('god.requests.index', compact('requests'));
    }
} 