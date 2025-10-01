<?php

namespace App\Livewire\Dashboard;

use App\Models\Loan;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ReportService;
use Livewire\Component;

class ManagerDashboard extends Component
{
    public $stats;
    public $pendingLoans;
    public $recentTransactions;
    public $monthlyTrends;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $reportService = app(ReportService::class);

        $this->stats = $reportService->getDashboardStats();
        $this->pendingLoans = Loan::where('status', 'pending')
            ->with(['user'])
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();

        $this->recentTransactions = Transaction::with(['user', 'loan'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $this->monthlyTrends = $reportService->getMonthlyTrends(6);
    }

    public function render()
    {
        return view('livewire.dashboard.manager-dashboard');
    }
}
