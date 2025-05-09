<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
   
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'god']);
    }

    public function view(User $user, Category $category): bool
    {
        return $user->hasRole(['admin', 'god']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'god']);
    }

    public function update(User $user, Category $category): bool
    {
        return $user->hasRole(['admin', 'god']);
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->hasRole(['admin', 'god']);
    }

} 