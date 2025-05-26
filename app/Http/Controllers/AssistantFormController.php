<?php

namespace App\Http\Controllers;

class AssistantFormController extends Controller
{
    public function __invoke()
    {
        return view('assistant-form');
    }
}
