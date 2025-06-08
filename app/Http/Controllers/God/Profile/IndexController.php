<?php

namespace App\Http\Controllers\God\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $users = User::query()
            ->with(['assistant', 'assistant.verification', 'roles', 'permissions'])
            ->when($request->input('search'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->input('role'), function ($query, $role) {
                $query->whereHas('roles', function ($query) use ($role) {
                    $query->where('name', $role);
                });
            })
            ->when($request->boolean('verified'), function ($query) {
                $query->whereHas('assistant', function ($query) {
                    $query->where('is_verified', true);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('god.profiles.index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'verified']),
            'roles' => \Spatie\Permission\Models\Role::all(),
        ]);
    }
}
