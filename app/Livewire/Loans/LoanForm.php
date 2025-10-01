<?php

namespace App\Livewire\Loans;

use App\Services\LoanService;
use App\Services\NotificationService;
use Livewire\Component;

class LoanForm extends Component
{
    public $amount = '';
    public $purpose = '';
    public $duration_months = '';
    public $income = '';
    public $interest_rate = 5.0;

    protected $rules = [
        'amount' => 'required|numeric|min:100|max:100000',
        'purpose' => 'required|string|max:255',
        'duration_months' => 'required|integer|min:6|max:60',
        'income' => 'required|numeric|min:1000',
        'interest_rate' => 'nullable|numeric|min:0|max:20',
    ];

    protected $messages = [
        'amount.required' => 'Loan amount is required.',
        'amount.min' => 'Minimum loan amount is $100.',
        'amount.max' => 'Maximum loan amount is $100,000.',
        'purpose.required' => 'Loan purpose is required.',
        'duration_months.required' => 'Loan duration is required.',
        'duration_months.min' => 'Minimum loan duration is 6 months.',
        'duration_months.max' => 'Maximum loan duration is 60 months.',
        'income.required' => 'Monthly income is required.',
        'income.min' => 'Minimum monthly income is $1,000.',
    ];

    public function submit()
    {
        $this->validate();

        try {
            $loanService = app(LoanService::class);
            $notificationService = app(NotificationService::class);

            $loan = $loanService->createLoan(auth()->user(), [
                'amount' => (float) $this->amount,
                'purpose' => $this->purpose,
                'duration_months' => (int) $this->duration_months,
                'income' => (float) $this->income,
                'interest_rate' => (float) $this->interest_rate,
            ]);

            // Notify admins about new loan application
            $notificationService->notifyAdminLoanSubmitted($loan);

            $this->dispatch('loan-submitted', 'Loan application submitted successfully!');
            $this->resetForm();

        } catch (\Exception $e) {
            $this->dispatch('loan-error', $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->amount = '';
        $this->purpose = '';
        $this->duration_months = '';
        $this->income = '';
        $this->interest_rate = 5.0;
    }

    public function calculateMonthlyPayment()
    {
        if ($this->amount && $this->duration_months && $this->interest_rate) {
            $monthlyRate = $this->interest_rate / 100 / 12;
            $numerator = $this->amount * $monthlyRate * pow(1 + $monthlyRate, $this->duration_months);
            $denominator = pow(1 + $monthlyRate, $this->duration_months) - 1;

            return $numerator / $denominator;
        }

        return 0;
    }

    public function render()
    {
        return view('livewire.loans.loan-form', [
            'monthlyPayment' => $this->calculateMonthlyPayment()
        ]);
    }
}
