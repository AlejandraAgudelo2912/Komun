<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $users = User::with(['roles', 'assistant', 'assistant.verification'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->role($request->role);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                if ($request->status === 'verified') {
                    $query->whereHas('assistant', function ($q) {
                        $q->where('is_verified', true);
                    });
                } elseif ($request->status === 'unverified') {
                    $query->whereHas('assistant', function ($q) {
                        $q->where('is_verified', false);
                    });
                }
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.profiles.index', [
            'users' => $users,
            'filters' => [
                'search' => $request->search,
                'role' => $request->role,
                'status' => $request->status,
            ],
        ]);
    }
}
