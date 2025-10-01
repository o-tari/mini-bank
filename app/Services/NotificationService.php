<?php

namespace App\Services;

use App\Models\User;
use App\Models\Loan;
use App\Models\Transaction;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function notifyLoanStatusChange(Loan $loan, string $status): void
    {
        $user = $loan->user;

        $message = match($status) {
            'approved' => "Your loan application for {$loan->purpose} has been approved!",
            'rejected' => "Your loan application for {$loan->purpose} has been rejected.",
            'disbursed' => "Your loan of $" . number_format($loan->amount, 2) . " has been disbursed to your account.",
            'completed' => "Congratulations! Your loan for {$loan->purpose} has been fully paid.",
            'defaulted' => "Your loan for {$loan->purpose} is now in default status.",
            default => "Your loan application status has been updated to {$status}."
        };

        $user->notify(new \App\Notifications\LoanStatusNotification($loan, $status, $message));
    }

    public function notifyTransaction(User $user, Transaction $transaction): void
    {
        $message = match($transaction->type) {
            'deposit' => "Deposit of $" . number_format($transaction->amount, 2) . " has been processed.",
            'withdrawal' => "Withdrawal of $" . number_format($transaction->amount, 2) . " has been processed.",
            'loan_disbursement' => "Loan disbursement of $" . number_format($transaction->amount, 2) . " has been credited to your account.",
            'loan_payment' => "Loan payment of $" . number_format($transaction->amount, 2) . " has been processed.",
            'transfer' => $transaction->amount > 0
                ? "Transfer of $" . number_format($transaction->amount, 2) . " has been received."
                : "Transfer of $" . number_format(abs($transaction->amount), 2) . " has been sent.",
            default => "A transaction has been processed on your account."
        };

        $user->notify(new \App\Notifications\TransactionNotification($transaction, $message));
    }

    public function notifyLowBalance(User $user, float $currentBalance, float $threshold = 100): void
    {
        if ($currentBalance < $threshold) {
            $user->notify(new \App\Notifications\LowBalanceNotification($currentBalance, $threshold));
        }
    }

    public function notifyLoanDueSoon(Loan $loan, int $daysUntilDue = 7): void
    {
        if ($loan->due_date && $loan->due_date->diffInDays(now()) <= $daysUntilDue) {
            $loan->user->notify(new \App\Notifications\LoanDueSoonNotification($loan));
        }
    }

    public function notifyAdminLoanSubmitted(Loan $loan): void
    {
        $admins = User::role(['admin', 'manager'])->get();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\AdminLoanSubmittedNotification($loan));
        }
    }

    public function getUnreadNotificationsCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }

    public function markAsRead(User $user, string $notificationId = null): void
    {
        if ($notificationId) {
            $user->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
        } else {
            $user->unreadNotifications()->update(['read_at' => now()]);
        }
    }

    public function getNotificationsForUser(User $user, int $limit = 20): \Illuminate\Database\Eloquent\Collection
    {
        return $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function deleteOldNotifications(int $daysOld = 30): int
    {
        return DatabaseNotification::where('created_at', '<', now()->subDays($daysOld))->delete();
    }
}
