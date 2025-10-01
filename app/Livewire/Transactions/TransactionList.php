<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Services\TransactionService;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionList extends Component
{
    use WithPagination;

    public $typeFilter = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $search = '';
    public $showUserTransactions = false;

    protected $queryString = [
        'typeFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public function mount($showUserTransactions = false)
    {
        $this->showUserTransactions = $showUserTransactions;
    }

    public function deposit($amount)
    {
        // Validate the amount parameter
        if (!is_numeric($amount) || $amount <= 0) {
            $this->dispatch('deposit-error', 'Invalid amount provided.');
            return;
        }

        try {
            $transactionService = app(TransactionService::class);
            $transactionService->deposit(auth()->user(), $amount, 'Account deposit');

            $this->dispatch('deposit-success', 'Deposit successful!');
        } catch (\Exception $e) {
            $this->dispatch('deposit-error', $e->getMessage());
        }
    }

    public function withdraw($amount)
    {
        // Validate the amount parameter
        if (!is_numeric($amount) || $amount <= 0) {
            $this->dispatch('withdrawal-error', 'Invalid amount provided.');
            return;
        }

        if ($amount > auth()->user()->balance) {
            $this->dispatch('withdrawal-error', 'Insufficient balance.');
            return;
        }

        try {
            $transactionService = app(TransactionService::class);
            $transactionService->withdraw(auth()->user(), $amount, 'Account withdrawal');

            $this->dispatch('withdrawal-success', 'Withdrawal successful!');
        } catch (\Exception $e) {
            $this->dispatch('withdrawal-error', $e->getMessage());
        }
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->typeFilter = '';
        $this->statusFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->search = '';
        $this->resetPage();
    }

    public function exportCsv()
    {
        try {
            $filters = [
                'type' => $this->typeFilter,
                'status' => $this->statusFilter,
                'date_from' => $this->dateFrom,
                'date_to' => $this->dateTo,
            ];

            if ($this->showUserTransactions) {
                $filters['user_id'] = auth()->id();
            }

            $reportService = app(\App\Services\ReportService::class);
            $filename = $reportService->exportToCsv('transactions', $filters);

            $this->dispatch('export-success', 'Export completed! Download: ' . basename($filename));
        } catch (\Exception $e) {
            $this->dispatch('export-error', $e->getMessage());
        }
    }

    public function render()
    {
        $query = Transaction::query();

        if ($this->showUserTransactions) {
            $query->where('user_id', auth()->id());
        } else {
            $query->with(['user', 'loan']);
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->dateFrom) {
            $query->where('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->where('created_at', '<=', $this->dateTo . ' 23:59:59');
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('reference', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('livewire.transactions.transaction-list', [
            'transactions' => $transactions,
            'types' => ['deposit', 'withdrawal', 'loan_disbursement', 'loan_payment', 'transfer'],
            'statuses' => ['pending', 'completed', 'failed', 'cancelled'],
        ]);
    }
}
