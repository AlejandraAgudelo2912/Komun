<?php

namespace App\Http\Controllers\Assistant\Request;

use App\Http\Controllers\Controller;
use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(HttpRequest $request): View
    {
        $requests = Request::where('status', 'pending')
            ->with(['category', 'user'])
            ->latest()
            ->get();

        return view('assistant.requests.index', compact('requests'));
    }
} 