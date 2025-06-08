<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryFollowController extends Controller
{
    public function follow(Category $category): RedirectResponse
    {
        $user = auth()->user();

        if ($user->followedCategories()->where('category_id', $category->id)->exists()) {
            return back()->with('error', 'Ya sigues esta categoría');
        }

        // guardar en la tabla pivote
        $user->followedCategories()->attach($category->id, ['notifications_enabled' => true]);

        return back()->with('success', 'Ahora sigues la categoría '.$category->name);
    }

    public function unfollow(Category $category): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->followedCategories()->where('category_id', $category->id)->exists()) {
            return back()->with('error', 'No sigues esta categoría');
        }

        $user->followedCategories()->detach($category->id);

        return back()->with('success', 'Has dejado de seguir la categoría '.$category->name);
    }

    public function followedCategories(): View
    {
        $user = auth()->user();
        $categories = $user->followedCategories()
            ->withPivot('notifications_enabled')
            ->get();

        return view('categories.followed', [
            'categories' => $categories,
        ]);
    }
}
