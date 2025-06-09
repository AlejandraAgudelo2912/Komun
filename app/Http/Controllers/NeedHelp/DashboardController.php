<?php

namespace App\Http\Controllers\NeedHelp;

use App\Http\Controllers\Controller;
use App\Models\Assistant;
use App\Models\RequestModel;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        if (! $user->hasRole('needHelp')) {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta página.');
        }

        $userRequests = RequestModel::where('user_id', $user->id)->get();

        $totalRequests = $userRequests->count();
        $completedRequests = $userRequests->where('status', 'completed')->count();
        $inProgressRequests = $userRequests->where('status', 'in_progress')->count();
        $pendingRequests = $userRequests->where('status', 'pending')->count();

        // Estadísticas principales
        $activeRequests = RequestModel::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();

        $activeAssistants = RequestModel::where('user_id', $user->id)
            ->whereHas('applicants', function ($query) {
                $query->where('status', 'accepted');
            })
            ->where('status', 'in_progress')
            ->count();

        $totalAssistants = RequestModel::where('user_id', $user->id)
            ->whereHas('applicants', function ($query) {
                $query->where('status', 'accepted');
            })
            ->where('status', 'completed')
            ->distinct('assistant_id')
            ->count('assistant_id');

        // Estadísticas de horas
        $completedRequestsWithDuration = RequestModel::where('user_id', $user->id)
            ->where('status', 'completed')
            ->get();

        $totalHours = $completedRequestsWithDuration->sum(function ($request) {
            return $request->created_at->diffInMinutes($request->updated_at) / 60;
        });

        $hoursThisMonth = $completedRequestsWithDuration
            ->filter(function ($request) {
                return $request->updated_at->month === Carbon::now()->month;
            })
            ->sum(function ($request) {
                return $request->created_at->diffInMinutes($request->updated_at) / 60;
            });

        // Estadísticas de valoraciones
        $reviews = Review::whereHas('request', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        // Actividad mensual
        $monthlyActivity = RequestModel::where('user_id', $user->id)
            ->whereYear('created_at', Carbon::now()->year)
            ->get()
            ->groupBy(function ($request) {
                return Carbon::parse($request->created_at)->format('M');
            })
            ->map(function ($requests, $month) {
                $hours = $requests->where('status', 'completed')->sum(function ($request) {
                    return $request->created_at->diffInMinutes($request->updated_at) / 60;
                });

                return [
                    'month' => $month,
                    'requests' => $requests->count(),
                    'hours' => $hours,
                ];
            })
            ->values();

        // Últimas solicitudes
        $latestRequests = RequestModel::where('user_id', $user->id)
            ->with(['category', 'applicants.assistant.user'])
            ->latest()
            ->take(5)
            ->get();

        // Últimas reseñas
        $latestReviews = Review::whereHas('request', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['assistant.assistant.user'])
            ->latest()
            ->take(5)
            ->get();

        // Estadísticas por categoría
        $categoryStats = RequestModel::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(function ($requests) {
                return (object) [
                    'name' => $requests->first()->category->name,
                    'count' => $requests->count(),
                ];
            })
            ->values();

        // Top asistentes
        $topAssistants = Assistant::whereHas('user.requests', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('status', 'completed');
        })->with(['user', 'reviews' => function ($query) use ($user) {
            $query->whereHas('request', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }])->get()
            ->map(function ($assistant) {
                $completedRequests = $assistant->user->requests()
                    ->where('status', 'completed')
                    ->get();

                $assistant->total_requests = $completedRequests->count();
                $assistant->total_hours = $completedRequests->sum(function ($request) {
                    return $request->created_at->diffInMinutes($request->updated_at) / 60;
                });
                $assistant->average_rating = $assistant->reviews->avg('rating') ?? 0;

                return $assistant;
            })
            ->sortByDesc('average_rating')
            ->take(3);

        return view('needhelp.dashboard', compact(
            'userRequests',
            'totalRequests',
            'completedRequests',
            'inProgressRequests',
            'pendingRequests',
            'activeRequests',
            'activeAssistants',
            'totalAssistants',
            'totalHours',
            'hoursThisMonth',
            'averageRating',
            'totalReviews',
            'monthlyActivity',
            'latestRequests',
            'latestReviews',
            'categoryStats',
            'topAssistants'
        ));
    }
}
