<?php

namespace App\Livewire\Loans;

use App\Models\Loan;
use App\Services\LoanService;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\WithPagination;

class LoanTable extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $purposeFilter = '';
    public $search = '';
    public $showUserLoans = false;

    protected $queryString = [
        'statusFilter' => ['except' => ''],
        'purposeFilter' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public function mount($showUserLoans = false)
    {
        $this->showUserLoans = $showUserLoans;
    }

    public function approveLoan($loanId)
    {
        try {
            $loan = Loan::findOrFail($loanId);
            $loanService = app(LoanService::class);
            $notificationService = app(NotificationService::class);

            $loanService->approveLoan($loan, auth()->user(), 'Approved by ' . auth()->user()->name);
            $notificationService->notifyLoanStatusChange($loan, 'approved');

            $this->dispatch('loan-approved', 'Loan approved successfully!');
        } catch (\Exception $e) {
            $this->dispatch('loan-error', $e->getMessage());
        }
    }

    public function rejectLoan($loanId)
    {
        $this->dispatch('show-reject-modal', $loanId);
    }

    public function rejectLoanConfirm($loanId, $reason)
    {
        try {
            $loan = Loan::findOrFail($loanId);
            $loanService = app(LoanService::class);
            $notificationService = app(NotificationService::class);

            $loanService->rejectLoan($loan, auth()->user(), $reason);
            $notificationService->notifyLoanStatusChange($loan, 'rejected');

            $this->dispatch('loan-rejected', 'Loan rejected successfully!');
        } catch (\Exception $e) {
            $this->dispatch('loan-error', $e->getMessage());
        }
    }

    public function disburseLoan($loanId)
    {
        try {
            $loan = Loan::findOrFail($loanId);
            $loanService = app(LoanService::class);
            $notificationService = app(NotificationService::class);

            $loanService->disburseLoan($loan, auth()->user());
            $notificationService->notifyLoanStatusChange($loan, 'disbursed');

            $this->dispatch('loan-disbursed', 'Loan disbursed successfully!');
        } catch (\Exception $e) {
            $this->dispatch('loan-error', $e->getMessage());
        }
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedPurposeFilter()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->statusFilter = '';
        $this->purposeFilter = '';
        $this->search = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Loan::query();

        if ($this->showUserLoans) {
            $query->where('user_id', auth()->id());
        } else {
            $query->with(['user', 'approver']);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->purposeFilter) {
            $query->where('purpose', 'like', '%' . $this->purposeFilter . '%');
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('purpose', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.loans.loan-table', [
            'loans' => $loans,
            'statuses' => ['pending', 'approved', 'rejected', 'disbursed', 'completed', 'defaulted'],
            'purposes' => Loan::distinct()->pluck('purpose')->sort()->values(),
        ]);
    }
}
