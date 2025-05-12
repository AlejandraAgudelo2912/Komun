<?php

namespace App\Http\Controllers\NeedHelp\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(HttpRequest $request): View
    {
        $requests = RequestModel::where('user_id', auth()->id())
            ->with(['category', 'applicants'])
            ->latest()
            ->get();

        return view('needhelp.requests.index', compact('requests'));
    }
}
