<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();
        $assignedRequests = RequestModel::whereHas('applicants', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('status', 'accepted');
        })->get();

        $totalAssignedRequests = $assignedRequests->count();
        $completedAssignedRequests = $assignedRequests->where('status', 'completed')->count();
        $inProgressAssignedRequests = $assignedRequests->where('status', 'in_progress')->count();
        $pendingAssignedRequests = $assignedRequests->where('status', 'pending')->count();

        // Estadísticas principales
        $activeRequests = RequestModel::whereHas('applicants', function ($query) use ($user) {
            $query->where('users.id', $user->id)
                ->where('request_model_application.status', 'accepted');
        })->where('status', 'in_progress')->count();

        $completedRequests = RequestModel::whereHas('applicants', function ($query) use ($user) {
            $query->where('users.id', $user->id)
                ->where('request_model_application.status', 'accepted');
        })->where('status', 'completed')->count();

        $assistedUsers = RequestModel::whereHas('applicants', function ($query) use ($user) {
            $query->where('users.id', $user->id)
                ->where('request_model_application.status', 'accepted');
        })->where('status', 'completed')
            ->distinct('user_id')
            ->count('user_id');

        $assistedUsersThisMonth = RequestModel::whereHas('applicants', function ($query) use ($user) {
            $query->where('users.id', $user->id)
                ->where('request_model_application.status', 'accepted');
        })->where('status', 'completed')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->distinct('user_id')
            ->count('user_id');

        // Estadísticas de valoraciones
        $reviews = Review::where('assistant_id', $user->id)->get();
        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        // Distribución de valoraciones
        $ratingDistribution = [
            $reviews->where('rating', 1)->count(),
            $reviews->where('rating', 2)->count(),
            $reviews->where('rating', 3)->count(),
            $reviews->where('rating', 4)->count(),
            $reviews->where('rating', 5)->count(),
        ];

        // Estadísticas de horas
        $totalHours = RequestModel::whereHas('applicants', function ($query) use ($user) {
            $query->where('users.id', $user->id)
                ->where('request_model_application.status', 'accepted');
        })->where('status', 'completed')
            ->get()
            ->sum(function ($request) {
                return $request->updated_at->diffInMinutes($request->created_at) / 60;
            });

        $hoursThisMonth = RequestModel::whereHas('applicants', function ($query) use ($user) {
            $query->where('users.id', $user->id)
                ->where('request_model_application.status', 'accepted');
        })->where('status', 'completed')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->get()
            ->sum(function ($request) {
                return $request->updated_at->diffInMinutes($request->created_at) / 60;
            });

        // Actividad mensual
        $monthlyActivity = RequestModel::whereHas('applicants', function ($query) use ($user) {
            $query->where('users.id', $user->id)
                ->where('request_model_application.status', 'accepted');
        })->where('status', 'completed')
            ->whereYear('updated_at', Carbon::now()->year)
            ->get()
            ->groupBy(function ($request) {
                return Carbon::parse($request->updated_at)->format('M');
            })
            ->map(function ($requests) {
                return [
                    'month' => $requests->first()->updated_at->format('M'),
                    'requests' => $requests->count(),
                    'hours' => $requests->sum(function ($request) {
                        return $request->updated_at->diffInMinutes($request->created_at) / 60;
                    }),
                ];
            })
            ->values();

        // Últimas solicitudes
        $latestRequests = RequestModel::whereHas('applicants', function ($query) use ($user) {
            $query->where('users.id', $user->id)
                ->where('request_model_application.status', 'accepted');
        })->with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        // Últimas reseñas
        $latestReviews = Review::where('assistant_id', $user->id)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        // Estadísticas por categoría
        $categoryStats = RequestModel::whereHas('applicants', function ($query) use ($user) {
            $query->where('users.id', $user->id)
                ->where('request_model_application.status', 'accepted');
        })->where('status', 'completed')
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

        $maxCategoryCount = $categoryStats->max('count') ?? 1;

        return view('assistant.dashboard', compact(
            'assignedRequests',
            'totalAssignedRequests',
            'completedAssignedRequests',
            'inProgressAssignedRequests',
            'pendingAssignedRequests',
            'activeRequests',
            'completedRequests',
            'assistedUsers',
            'assistedUsersThisMonth',
            'averageRating',
            'totalReviews',
            'ratingDistribution',
            'totalHours',
            'hoursThisMonth',
            'monthlyActivity',
            'latestRequests',
            'latestReviews',
            'categoryStats',
            'maxCategoryCount'
        ));
    }
}
