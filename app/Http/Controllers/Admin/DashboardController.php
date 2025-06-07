<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RequestModel;
use App\Models\Assistant;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        // Estadísticas de usuarios
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)->count();
        $activeUsers = User::whereHas('requests', function ($query) {
            $query->whereIn('status', ['pending', 'in_progress']);
        })->count();

        // Estadísticas de solicitudes
        $pendingRequests = RequestModel::where('status', 'pending')->count();
        $completedRequests = RequestModel::where('status', 'completed')->count();
        $requestsThisMonth = RequestModel::whereMonth('created_at', Carbon::now()->month)->count();

        // Estadísticas de asistentes
        $activeAssistants = Assistant::where('status', 'active')->count();
        $verifiedAssistants = Assistant::where('is_verified', true)->count();
        $pendingVerifications = Assistant::where('is_verified', false)->count();

        // Estadísticas de verificadores
        $activeVerifiers = User::role('verificator')->count();
        $verificationsThisMonth = Assistant::where('is_verified', true)
            ->whereMonth('updated_at', Carbon::now()->month)
            ->count();

        // Solicitudes por categoría
        $requestsByCategory = Category::withCount(['requests' => function ($query) {
            $query->where('status', 'completed');
        }])->get();

        // Actividad mensual
        $monthlyActivity = RequestModel::whereYear('created_at', Carbon::now()->year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_requests'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_requests')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::create()->month($item->month)->format('M'),
                    'total' => $item->total_requests,
                    'completed' => $item->completed_requests
                ];
            });

        // Últimas solicitudes
        $latestRequests = RequestModel::with(['user', 'category', 'applicants.assistant.user'])
            ->latest()
            ->take(5)
            ->get();

        // Top asistentes
        $topAssistants = Assistant::with(['user.reviews'])
            ->where('status', 'active')
            ->get()
            ->map(function ($assistant) {
                $assistant->total_requests = $assistant->user->requests()
                    ->where('status', 'completed')
                    ->count();
                $assistant->average_rating = $assistant->user->reviews->avg('rating') ?? 0;
                $assistant->total_reviews = $assistant->user->reviews->count();
                return $assistant;
            })
            ->sortByDesc('average_rating')
            ->take(5);

        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersThisMonth',
            'activeUsers',
            'pendingRequests',
            'completedRequests',
            'requestsThisMonth',
            'activeAssistants',
            'verifiedAssistants',
            'pendingVerifications',
            'activeVerifiers',
            'verificationsThisMonth',
            'requestsByCategory',
            'monthlyActivity',
            'latestRequests',
            'topAssistants'
        ));
    }
} 