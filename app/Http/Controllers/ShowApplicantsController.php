<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;

class ShowApplicantsController extends Controller
{
    public function __invoke(RequestModel $requestModel)
    {
        $applicants = $requestModel->applicants;

        return view('needHelp.requests.applicants', compact('requestModel', 'applicants'));
    }
}
