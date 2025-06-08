<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FilterController extends Controller
{
    /**
     * Aplica los filtros a las solicitudes y devuelve la vista con los resultados
     */
    public function __invoke(Request $request): View
    {
        $query = RequestModel::query()
            ->with(['category', 'applicants']);

        // Aplicar filtros usando scopes
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('location')) {
            $query->byLocation($request->location);
        }

        if ($request->boolean('urgent')) {
            $query->urgent();
        }

        if ($request->boolean('no_applicants')) {
            $query->noApplicants();
        }

        if (auth()->user()->hasRole('needHelp')) {
            $query->where('user_id', auth()->id());
        } elseif (auth()->user()->hasRole('assistant')) {
            $query->where('status', 'pending');
        }

        $requests = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::all();

        $view = match(auth()->user()->getRoleNames()->first()) {
            'needHelp' => 'needhelp.requests.index',
            'assistant' => 'assistant.requests.index',
            'admin' => 'admin.requests.index',
            'verificator' => 'verificator.requests.index',
            'god' => 'god.requests.index',
            default => 'requests.index'
        };

        return view($view, compact('requests', 'categories'));
    }
}
