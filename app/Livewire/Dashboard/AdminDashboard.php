<?php

namespace App\Livewire\Dashboard;

use App\Models\AuditLog;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ReportService;
use Livewire\Component;

class AdminDashboard extends Component
{
    public $stats;
    public $recentAuditLogs;
    public $monthlyTrends;
    public $userStats;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $reportService = app(ReportService::class);

        $this->stats = $reportService->getDashboardStats();
        $this->recentAuditLogs = AuditLog::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $this->monthlyTrends = $reportService->getMonthlyTrends(12);

        $this->userStats = [
            'total_users' => User::count(),
            'users_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'active_users' => User::whereHas('transactions', function ($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            })->count(),
            'users_with_loans' => User::whereHas('loans')->count(),
        ];
    }

    public function exportReport($type)
    {
        try {
            $reportService = app(ReportService::class);
            $filename = $reportService->exportToCsv($type);

            $this->dispatch('export-success', 'Export completed! Download: ' . basename($filename));
        } catch (\Exception $e) {
            $this->dispatch('export-error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.dashboard.admin-dashboard');
    }
}
