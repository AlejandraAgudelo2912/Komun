<?php

namespace App\Http\Controllers\God\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;

class UpdateController extends Controller
{
    public function __invoke(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('god.categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }
}
