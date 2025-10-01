<?php

namespace App\Livewire\Dashboard;

use App\Models\Loan;
use App\Models\Transaction;
use App\Services\TransactionService;
use Livewire\Component;
use Livewire\WithPagination;

class UserDashboard extends Component
{
    use WithPagination;

    public $balance;
    public $recentTransactions;
    public $activeLoans;
    public $pendingLoans;
    public $amount;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $user = auth()->user();

        $this->balance = $user->balance;
        $this->recentTransactions = $user->transactions()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $this->activeLoans = $user->loans()
            ->whereIn('status', ['approved', 'disbursed'])
            ->get();

        $this->pendingLoans = $user->loans()
            ->where('status', 'pending')
            ->get();
    }

    public function deposit($amount)
    {
        try {
            $transactionService = app(TransactionService::class);
            $transactionService->deposit(auth()->user(), $amount, 'Account deposit');

            $this->loadDashboardData();
            $this->dispatch('deposit-success', 'Deposit successful!');
        } catch (\Exception $e) {
            $this->dispatch('deposit-error', $e->getMessage());
        }
    }

    public function withdraw($amount)
    {
        $this->amount = $amount;

        $this->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $this->balance
        ], [], ['amount' => 'withdrawal amount']);

        try {
            $transactionService = app(TransactionService::class);
            $transactionService->withdraw(auth()->user(), $amount, 'Account withdrawal');

            $this->loadDashboardData();
            $this->dispatch('withdrawal-success', 'Withdrawal successful!');
        } catch (\Exception $e) {
            $this->dispatch('withdrawal-error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.dashboard.user-dashboard');
    }
}
