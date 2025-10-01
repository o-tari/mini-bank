<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\AuditLog;
use App\Services\ReportService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        } elseif ($user->hasRole('manager')) {
            return $this->managerDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    private function userDashboard()
    {
        $user = auth()->user();

        $data = [
            'balance' => $user->balance,
            'recentTransactions' => $user->transactions()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'activeLoans' => $user->loans()
                ->whereIn('status', ['approved', 'disbursed'])
                ->get(),
            'pendingLoans' => $user->loans()
                ->where('status', 'pending')
                ->get(),
        ];

        return view('dashboard.user', compact('data'));
    }

    private function managerDashboard()
    {
        $data = [
            'pendingLoans' => Loan::where('status', 'pending')
                ->with(['user'])
                ->orderBy('created_at', 'asc')
                ->paginate(10),
            'recentLoans' => Loan::with(['user', 'approver'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'loanStats' => [
                'total_pending' => Loan::where('status', 'pending')->count(),
                'total_approved' => Loan::where('status', 'approved')->count(),
                'total_rejected' => Loan::where('status', 'rejected')->count(),
                'total_disbursed' => Loan::where('status', 'disbursed')->count(),
            ],
            'monthlyStats' => $this->getMonthlyLoanStats(),
        ];

        return view('dashboard.manager', compact('data'));
    }

    private function adminDashboard()
    {
        $reportService = app(ReportService::class);

        $data = [
            'stats' => $reportService->getDashboardStats(),
            'recentUsers' => User::orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'recentAuditLogs' => AuditLog::with(['user'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'userStats' => [
                'total_users' => User::count(),
                'users_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
                'active_users' => User::whereHas('transactions', function ($query) {
                    $query->where('created_at', '>=', now()->subDays(30));
                })->count(),
                'users_with_loans' => User::whereHas('loans')->count(),
            ],
            'loanStats' => [
                'total_loans' => Loan::count(),
                'pending_loans' => Loan::where('status', 'pending')->count(),
                'approved_loans' => Loan::where('status', 'approved')->count(),
                'rejected_loans' => Loan::where('status', 'rejected')->count(),
                'approval_ratio' => $this->getApprovalRatio(),
            ],
        ];

        return view('dashboard.admin', compact('data'));
    }

    private function getMonthlyLoanStats()
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push([
                'month' => $date->format('M Y'),
                'pending' => Loan::where('status', 'pending')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'approved' => Loan::where('status', 'approved')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'rejected' => Loan::where('status', 'rejected')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ]);
        }
        return $months;
    }

    private function getApprovalRatio()
    {
        $totalProcessed = Loan::whereIn('status', ['approved', 'rejected'])->count();
        if ($totalProcessed === 0) return 0;

        $approved = Loan::where('status', 'approved')->count();
        return round(($approved / $totalProcessed) * 100, 2);
    }
}
