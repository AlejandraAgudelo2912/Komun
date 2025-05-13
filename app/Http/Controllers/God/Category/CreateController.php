<?php

namespace App\Http\Controllers\God\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function __invoke()
    {
        return view('god.categories.create');

    }
}
