<?php

namespace App\Http\Controllers\Admin\Request;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(): View
    {
        $requests = RequestModel::all();

        return view('admin.requests.index', compact('requests'));
    }
}
