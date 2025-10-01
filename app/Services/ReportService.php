<?php

namespace App\Services;

use App\Models\User;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportService
{
    public function getDashboardStats(): array
    {
        $totalUsers = User::count();
        $totalLoans = Loan::count();
        $totalTransactions = Transaction::count();
        $totalLoanAmount = Loan::where('status', '!=', 'rejected')->sum('amount');
        $totalDisbursed = Loan::where('status', 'disbursed')->sum('amount');
        $totalRepaid = Transaction::where('type', 'loan_payment')
            ->where('status', 'completed')
            ->sum('amount');
        $pendingLoans = Loan::where('status', 'pending')->count();
        $approvedLoans = Loan::where('status', 'approved')->count();
        $rejectedLoans = Loan::where('status', 'rejected')->count();

        return [
            'users' => [
                'total' => $totalUsers,
                'new_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            ],
            'loans' => [
                'total' => $totalLoans,
                'pending' => $pendingLoans,
                'approved' => $approvedLoans,
                'rejected' => $rejectedLoans,
                'total_amount' => $totalLoanAmount,
                'disbursed_amount' => $totalDisbursed,
                'repaid_amount' => $totalRepaid,
                'outstanding_amount' => $totalDisbursed - $totalRepaid,
            ],
            'transactions' => [
                'total' => $totalTransactions,
                'this_month' => Transaction::where('created_at', '>=', now()->startOfMonth())->count(),
                'total_volume' => Transaction::where('status', 'completed')->sum('amount'),
            ]
        ];
    }

    public function getLoanReport(array $filters = []): array
    {
        $query = Loan::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['purpose'])) {
            $query->where('purpose', 'like', '%' . $filters['purpose'] . '%');
        }

        $loans = $query->with(['user', 'approver'])->get();

        $summary = [
            'total_loans' => $loans->count(),
            'total_amount' => $loans->sum('amount'),
            'average_amount' => $loans->avg('amount'),
            'status_breakdown' => $loans->groupBy('status')->map->count(),
            'purpose_breakdown' => $loans->groupBy('purpose')->map->count(),
        ];

        return [
            'summary' => $summary,
            'loans' => $loans
        ];
    }

    public function getTransactionReport(array $filters = []): array
    {
        $query = Transaction::query();

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        $transactions = $query->with(['user', 'loan'])->get();

        $summary = [
            'total_transactions' => $transactions->count(),
            'total_volume' => $transactions->where('status', 'completed')->sum('amount'),
            'type_breakdown' => $transactions->groupBy('type')->map->count(),
            'status_breakdown' => $transactions->groupBy('status')->map->count(),
            'daily_volume' => $transactions->where('status', 'completed')
                ->groupBy(function ($transaction) {
                    return $transaction->created_at->format('Y-m-d');
                })
                ->map(function ($group) {
                    return $group->sum('amount');
                })
        ];

        return [
            'summary' => $summary,
            'transactions' => $transactions
        ];
    }

    public function getUserReport(User $user, array $filters = []): array
    {
        $loans = $user->loans();
        $transactions = $user->transactions();

        if (isset($filters['date_from'])) {
            $loans->where('created_at', '>=', $filters['date_from']);
            $transactions->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $loans->where('created_at', '<=', $filters['date_to']);
            $transactions->where('created_at', '<=', $filters['date_to']);
        }

        $userLoans = $loans->get();
        $userTransactions = $transactions->get();

        return [
            'user' => $user,
            'loans' => [
                'total' => $userLoans->count(),
                'total_amount' => $userLoans->sum('amount'),
                'status_breakdown' => $userLoans->groupBy('status')->map->count(),
            ],
            'transactions' => [
                'total' => $userTransactions->count(),
                'total_volume' => $userTransactions->where('status', 'completed')->sum('amount'),
                'type_breakdown' => $userTransactions->groupBy('type')->map->count(),
            ],
            'current_balance' => $user->balance,
        ];
    }

    public function getAuditReport(array $filters = []): array
    {
        $query = AuditLog::query();

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (isset($filters['model_type'])) {
            $query->where('model_type', $filters['model_type']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        $logs = $query->with(['user'])->orderBy('created_at', 'desc')->get();

        $summary = [
            'total_logs' => $logs->count(),
            'action_breakdown' => $logs->groupBy('action')->map->count(),
            'model_breakdown' => $logs->groupBy('model_type')->map->count(),
            'user_breakdown' => $logs->groupBy('user_id')->map->count(),
        ];

        return [
            'summary' => $summary,
            'logs' => $logs
        ];
    }

    public function getMonthlyTrends(int $months = 12): array
    {
        $startDate = now()->subMonths($months)->startOfMonth();

        $loans = Loan::where('created_at', '>=', $startDate)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $transactions = Transaction::where('created_at', '>=', $startDate)
            ->where('status', 'completed')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(amount) as total_volume')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $users = User::where('created_at', '>=', $startDate)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'loans' => $loans,
            'transactions' => $transactions,
            'users' => $users,
        ];
    }

    public function exportToCsv(string $reportType, array $filters = []): string
    {
        $data = match($reportType) {
            'loans' => $this->getLoanReport($filters)['loans'],
            'transactions' => $this->getTransactionReport($filters)['transactions'],
            'users' => User::all(),
            default => throw new \Exception('Invalid report type')
        };

        $filename = storage_path('app/reports/' . $reportType . '_' . now()->format('Y-m-d_H-i-s') . '.csv');

        // Ensure directory exists
        if (!file_exists(dirname($filename))) {
            mkdir(dirname($filename), 0755, true);
        }

        $file = fopen($filename, 'w');

        if ($data->isNotEmpty()) {
            // Write headers
            fputcsv($file, array_keys($data->first()->toArray()));

            // Write data
            foreach ($data as $row) {
                fputcsv($file, $row->toArray());
            }
        }

        fclose($file);

        return $filename;
    }
}
