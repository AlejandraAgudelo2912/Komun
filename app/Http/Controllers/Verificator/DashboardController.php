<?php

namespace App\Http\Controllers\Verificator;

use App\Http\Controllers\Controller;
use App\Models\Assistant;
use App\Models\RequestModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $pendingVerifications = Assistant::where('status', 'pending')->count();
        $verifiedToday = Assistant::where('status', 'verified')
            ->whereDate('updated_at', Carbon::today())
            ->count();
        $totalVerified = Assistant::where('status', 'verified')->count();
        $rejectedToday = Assistant::where('status', 'rejected')
            ->whereDate('updated_at', Carbon::today())
            ->count();

        $completedVerifications = Assistant::whereIn('status', ['verified', 'rejected'])->count();
        $verificationsThisMonth = Assistant::whereIn('status', ['verified', 'rejected'])
            ->whereMonth('updated_at', Carbon::now()->month)
            ->count();

        $totalRequests = RequestModel::count();
        $requestsThisMonth = RequestModel::whereMonth('created_at', Carbon::now()->month)->count();
        $completedRequests = RequestModel::where('status', 'completed')->count();
        $activeRequests = RequestModel::where('status', 'in_progress')->count();

        $totalAssistants = Assistant::count();
        $verifiedAssistants = Assistant::where('status', 'verified')->count();
        $pendingAssistants = Assistant::where('status', 'pending')->count();
        $rejectedAssistants = Assistant::where('status', 'rejected')->count();

        $verifiedAssistantsWithTime = Assistant::whereIn('status', ['verified', 'rejected'])
            ->whereNotNull('updated_at')
            ->whereNotNull('created_at')
            ->get();

        $averageVerificationTime = $verifiedAssistantsWithTime->avg(function ($assistant) {
            return $assistant->updated_at->diffInMinutes($assistant->created_at);
        });

        $lastVerification = Assistant::whereIn('status', ['verified', 'rejected'])
            ->latest('updated_at')
            ->first();

        $lastVerificationTime = $lastVerification ?
            $lastVerification->updated_at->diffInMinutes($lastVerification->created_at).' min' :
            'N/A';

        $totalProcessed = Assistant::whereIn('status', ['verified', 'rejected'])->count();
        $totalVerified = Assistant::where('status', 'verified')->count();
        $totalRejected = Assistant::where('status', 'rejected')->count();

        $approvalRate = $totalProcessed > 0 ?
            round(($totalVerified / $totalProcessed) * 100, 1) : 0;
        $rejectionRate = $totalProcessed > 0 ?
            round(($totalRejected / $totalProcessed) * 100, 1) : 0;

        $verificationsByDay = Assistant::whereIn('status', ['verified', 'rejected'])
            ->where('updated_at', '>=', Carbon::now()->subDays(7))
            ->get()
            ->groupBy(function ($assistant) {
                return Carbon::parse($assistant->updated_at)->format('d/m');
            })
            ->map(function ($assistants, $day) {
                return [
                    'day' => $day,
                    'count' => $assistants->count(),
                ];
            })
            ->values();

        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('d/m');
            $dayData = $verificationsByDay->firstWhere('day', $date);
            $last7Days->push([
                'day' => $date,
                'count' => $dayData ? $dayData['count'] : 0,
            ]);
        }
        $verificationsByDay = $last7Days;

        $verificationTime = Assistant::whereIn('status', ['verified', 'rejected'])
            ->where('updated_at', '>=', Carbon::now()->subDays(7))
            ->get()
            ->groupBy(function ($assistant) {
                return Carbon::parse($assistant->updated_at)->format('d/m');
            })
            ->map(function ($assistants, $date) {
                $avgTime = $assistants->avg(function ($assistant) {
                    return $assistant->updated_at->diffInMinutes($assistant->created_at);
                });

                return [
                    'date' => $date,
                    'time' => round($avgTime, 1),
                ];
            })
            ->values();

        $last7DaysTime = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('d/m');
            $dayData = $verificationTime->firstWhere('date', $date);
            $last7DaysTime->push([
                'date' => $date,
                'time' => $dayData ? $dayData['time'] : 0,
            ]);
        }
        $verificationTime = $last7DaysTime;

        $monthlyActivity = Assistant::whereYear('updated_at', Carbon::now()->year)
            ->whereIn('status', ['verified', 'rejected'])
            ->get()
            ->groupBy(function ($assistant) {
                return Carbon::parse($assistant->updated_at)->format('M');
            })
            ->map(function ($assistants) {
                return [
                    'month' => $assistants->first()->updated_at->format('M'),
                    'verified' => $assistants->where('status', 'verified')->count(),
                    'rejected' => $assistants->where('status', 'rejected')->count(),
                ];
            })
            ->values();

        $latestVerifications = Assistant::with(['user'])
            ->whereIn('status', ['verified', 'rejected'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        $latestPendingVerifications = Assistant::with(['user'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('verificator.dashboard', compact(
            'pendingVerifications',
            'verifiedToday',
            'totalVerified',
            'rejectedToday',
            'completedVerifications',
            'verificationsThisMonth',
            'totalRequests',
            'requestsThisMonth',
            'completedRequests',
            'activeRequests',
            'totalAssistants',
            'verifiedAssistants',
            'pendingAssistants',
            'rejectedAssistants',
            'averageVerificationTime',
            'lastVerificationTime',
            'approvalRate',
            'rejectionRate',
            'verificationsByDay',
            'verificationTime',
            'monthlyActivity',
            'latestVerifications',
            'latestPendingVerifications'
        ));
    }
}
