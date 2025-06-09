<?php

namespace App\Http\Controllers\God;

class DashboardController
{
    public function __invoke()
    {
        return view('god.dashboard');
    }
}
