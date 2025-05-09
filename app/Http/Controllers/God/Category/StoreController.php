<?php

namespace App\Http\Controllers\God\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    public function __invoke(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('god.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }
} 