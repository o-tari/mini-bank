<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\User;
use App\Models\Transaction;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoanService
{
    public function createLoan(User $user, array $data): Loan
    {
        return DB::transaction(function () use ($user, $data) {
            // Calculate monthly payment based on amount, duration, and interest rate
            $monthlyPayment = $this->calculateMonthlyPayment(
                (float) $data['amount'],
                (int) $data['duration_months'],
                (float) ($data['interest_rate'] ?? 0)
            );

            $loan = Loan::create([
                'user_id' => $user->id,
                'amount' => $data['amount'],
                'purpose' => $data['purpose'],
                'duration_months' => (int) $data['duration_months'],
                'monthly_payment' => $monthlyPayment,
                'interest_rate' => $data['interest_rate'] ?? 0,
                'income' => $data['income'],
                'status' => 'pending',
                'due_date' => now()->addMonths((int) $data['duration_months']),
            ]);

            // Log the loan creation
            AuditLog::log('created', $loan, $user, [], $loan->toArray());

            return $loan;
        });
    }

    public function approveLoan(Loan $loan, User $approver, string $comments = null): Loan
    {
        if (!$approver->hasRole(['manager', 'admin'])) {
            throw new \Exception('User does not have permission to approve loans');
        }

        return DB::transaction(function () use ($loan, $approver, $comments) {
            $oldValues = $loan->toArray();

            $loan->update([
                'status' => 'approved',
                'approved_by' => $approver->id,
                'approved_at' => now(),
                'approval_comments' => $comments,
            ]);

            // Log the approval
            AuditLog::log('approved', $loan, $approver, $oldValues, $loan->toArray(), [
                'comments' => $comments
            ]);

            return $loan->fresh();
        });
    }

    public function rejectLoan(Loan $loan, User $rejector, string $reason): Loan
    {
        if (!$rejector->hasRole(['manager', 'admin'])) {
            throw new \Exception('User does not have permission to reject loans');
        }

        return DB::transaction(function () use ($loan, $rejector, $reason) {
            $oldValues = $loan->toArray();

            $loan->update([
                'status' => 'rejected',
                'approved_by' => $rejector->id,
                'approved_at' => now(),
                'rejection_reason' => $reason,
            ]);

            // Log the rejection
            AuditLog::log('rejected', $loan, $rejector, $oldValues, $loan->toArray(), [
                'reason' => $reason
            ]);

            return $loan->fresh();
        });
    }

    public function disburseLoan(Loan $loan, User $disburser): Loan
    {
        if (!$disburser->hasRole(['manager', 'admin'])) {
            throw new \Exception('User does not have permission to disburse loans');
        }

        if (!$loan->isApproved()) {
            throw new \Exception('Only approved loans can be disbursed');
        }

        return DB::transaction(function () use ($loan, $disburser) {
            $oldValues = $loan->toArray();

            // Update loan status
            $loan->update([
                'status' => 'disbursed',
                'disbursed_at' => now(),
            ]);

            // Create disbursement transaction
            $transaction = Transaction::create([
                'user_id' => $loan->user_id,
                'loan_id' => $loan->id,
                'type' => 'loan_disbursement',
                'amount' => $loan->amount,
                'balance_after' => $loan->user->balance + $loan->amount,
                'description' => "Loan disbursement for {$loan->purpose}",
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            // Update user balance
            $loan->user->increment('balance', $loan->amount);

            // Log the disbursement
            AuditLog::log('disbursed', $loan, $disburser, $oldValues, $loan->toArray());

            return $loan->fresh();
        });
    }

    public function processLoanPayment(Loan $loan, User $user, float $amount): Transaction
    {
        if ($loan->user_id !== $user->id) {
            throw new \Exception('User can only make payments for their own loans');
        }

        if ($user->balance < $amount) {
            throw new \Exception('Insufficient balance');
        }

        return DB::transaction(function () use ($loan, $user, $amount) {
            // Create payment transaction
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'loan_id' => $loan->id,
                'type' => 'loan_payment',
                'amount' => $amount,
                'balance_after' => $user->balance - $amount,
                'description' => "Loan payment for {$loan->purpose}",
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            // Update user balance
            $user->decrement('balance', $amount);

            // Check if loan is fully paid
            $totalPaid = $loan->transactions()
                ->where('type', 'loan_payment')
                ->where('status', 'completed')
                ->sum('amount');

            if ($totalPaid >= $loan->amount) {
                $loan->update(['status' => 'completed']);
                AuditLog::log('completed', $loan, $user);
            }

            // Log the payment
            AuditLog::log('payment_made', $loan, $user, [], [], [
                'amount' => $amount,
                'transaction_id' => $transaction->id
            ]);

            return $transaction;
        });
    }

    private function calculateMonthlyPayment(float $amount, int $durationMonths, float $interestRate = 0): float
    {
        if ($interestRate == 0) {
            return $amount / $durationMonths;
        }

        $monthlyRate = $interestRate / 100 / 12;
        $numerator = $amount * $monthlyRate * pow(1 + $monthlyRate, $durationMonths);
        $denominator = pow(1 + $monthlyRate, $durationMonths) - 1;

        return $numerator / $denominator;
    }

    public function getLoansForUser(User $user, array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = $user->loans();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['purpose'])) {
            $query->where('purpose', 'like', '%' . $filters['purpose'] . '%');
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getPendingLoans(): \Illuminate\Database\Eloquent\Collection
    {
        return Loan::where('status', 'pending')
            ->with(['user'])
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
