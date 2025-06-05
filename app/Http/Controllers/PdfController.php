<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdfController extends Controller
{
    public function userStats(User $user)
    {
        $stats = [
            'request_stats' => [
                'total_requests' => $user->requests()->count(),
                'active_requests' => $user->requests()->where('status', 'pending')->count(),
                'in_progress_requests' => $user->requests()->where('status', 'in_progress')->count(),
                'completed_requests' => $user->requests()->where('status', 'completed')->count(),
                'cancelled_requests' => $user->requests()->where('status', 'cancelled')->count(),
            ],

            'applied_stats' => [
                'total_applied' => $user->appliedRequests()->count(),
                'pending_applied' => $user->appliedRequests()->where('request_model_application.status', 'pending')->count(),
                'accepted_applied' => $user->appliedRequests()->where('request_model_application.status', 'accepted')->count(),
                'rejected_applied' => $user->appliedRequests()->where('request_model_application.status', 'rejected')->count(),
                'cancelled_applied' => $user->appliedRequests()->where('request_model_application.status', 'cancelled')->count(),
            ],

            'message_stats' => [
                'total_messages' => $user->sentMessages()->count() + $user->receivedMessages()->count(),
                'sent_messages' => $user->sentMessages()->count(),
                'received_messages' => $user->receivedMessages()->count(),
                'last_message_date' => $user->sentMessages()->latest()->first()?->created_at?->format('d/m/Y H:i') ??
                                     $user->receivedMessages()->latest()->first()?->created_at?->format('d/m/Y H:i') ?? 'N/A',
            ],

            'comment_stats' => [
                'total_comments' => $user->comments()->count(),
                'recent_comments' => $user->comments()->where('created_at', '>=', now()->subDays(30))->count(),
            ],

            'review_stats' => [
                'total_reviews' => $user->reviews()->count(),
                'average_rating' => number_format($user->reviews()->avg('rating') ?? 0, 1),
                'recent_reviews' => $user->reviews()->where('created_at', '>=', now()->subDays(30))->count(),
            ],

            'activity_stats' => [
                'join_date' => $user->created_at->format('d/m/Y'),
                'last_login' => $user->last_login_at?->format('d/m/Y H:i') ?? 'N/A',
            ]
        ];

        $pdf = PDF::loadView('pdf.user-stats', [
            'user' => $user,
            'stats' => $stats
        ]);

        $pdf->setPaper('a4');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'margin_top' => 20,
            'margin_right' => 20,
            'margin_bottom' => 20,
            'margin_left' => 20,
            'chroot' => public_path(),
            'dpi' => 150,
            'defaultMediaType' => 'screen',
            'isFontSubsettingEnabled' => true,
            'debugKeepTemp' => false,
            'debugCss' => false,
            'debugLayout' => false,
            'logOutputFile' => storage_path('logs/pdf.log'),
        ]);

        return $pdf->download("statistics-{$user->name}.pdf");
    }

    public function usersList(Request $request, $role = null)
    {
        $users = User::with(['roles', 'assistant', 'assistant.verification'])
            ->when($role === 'god', function ($query) {
                $query->with('permissions');
            })
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
            ->get();

        $pdf = PDF::loadView('pdf.users-list', [
            'users' => $users,
            'role' => $role,
            'filters' => [
                'search' => $request->search,
                'role' => $request->role,
                'status' => $request->status
            ]
        ]);

        $pdf->setPaper('a4');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'margin_top' => 20,
            'margin_right' => 20,
            'margin_bottom' => 20,
            'margin_left' => 20,
            'chroot' => public_path(),
            'dpi' => 150,
            'defaultMediaType' => 'screen',
            'isFontSubsettingEnabled' => true,
            'debugKeepTemp' => false,
            'debugCss' => false,
            'debugLayout' => false,
            'logOutputFile' => storage_path('logs/pdf.log'),
        ]);

        return $pdf->download('usuarios-' . now()->format('Y-m-d') . '.pdf');
    }

    private function calculateAverageResponseTime($user)
    {
        $requests = $user->requests()
            ->whereNotNull('first_response_at')
            ->get();

        if ($requests->isEmpty()) {
            return 'N/A';
        }

        $totalMinutes = $requests->sum(function ($request) {
            return $request->created_at->diffInMinutes($request->first_response_at);
        });

        $averageMinutes = $totalMinutes / $requests->count();

        if ($averageMinutes < 60) {
            return round($averageMinutes) . ' minutos';
        } else {
            return round($averageMinutes / 60, 1) . ' horas';
        }
    }
}
