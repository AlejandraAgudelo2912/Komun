<?php

namespace App\Http\Controllers\God\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;

class DestroyController extends Controller
{
    public function __invoke(Category $category)
    {
        $category = Category::findOrFail($category->id);
        $category->delete();

        return redirect()->route('god.categories.index')->with('success', 'Category deleted successfully.');
    }
}
