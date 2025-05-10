<?php

namespace App\Http\Controllers\Admin\Category;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Category;
use Illuminate\View\View;

class CreateController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, DispatchesJobs;

    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    public function __invoke(): View
    {
        return view('admin.categories.create');
    }
} 