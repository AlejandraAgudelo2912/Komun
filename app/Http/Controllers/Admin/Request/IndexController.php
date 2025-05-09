<?php

namespace App\Http\Controllers\Admin\Request;

use App\Http\Controllers\Controller;
use App\Models\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(): View
    {
        $requests = Request::all();
        
        return view('admin.requests.index', compact('requests'));
    }
} 