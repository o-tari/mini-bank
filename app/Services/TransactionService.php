<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionService
{
    public function deposit(User $user, float $amount, string $description = null): Transaction
    {
        if ($amount <= 0) {
            throw new \Exception('Deposit amount must be greater than zero');
        }

        return DB::transaction(function () use ($user, $amount, $description) {
            $newBalance = $user->balance + $amount;

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $amount,
                'balance_after' => $newBalance,
                'description' => $description ?? 'Account deposit',
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            // Update user balance
            $user->update(['balance' => $newBalance]);

            // Log the deposit
            AuditLog::log('deposit', $transaction, $user, [], $transaction->toArray());

            return $transaction;
        });
    }

    public function withdraw(User $user, float $amount, string $description = null): Transaction
    {
        if ($amount <= 0) {
            throw new \Exception('Withdrawal amount must be greater than zero');
        }

        if ($user->balance < $amount) {
            throw new \Exception('Insufficient balance');
        }

        return DB::transaction(function () use ($user, $amount, $description) {
            $newBalance = $user->balance - $amount;

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'amount' => $amount,
                'balance_after' => $newBalance,
                'description' => $description ?? 'Account withdrawal',
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            // Update user balance
            $user->update(['balance' => $newBalance]);

            // Log the withdrawal
            AuditLog::log('withdrawal', $transaction, $user, [], $transaction->toArray());

            return $transaction;
        });
    }

    public function transfer(User $fromUser, User $toUser, float $amount, string $description = null): array
    {
        if ($amount <= 0) {
            throw new \Exception('Transfer amount must be greater than zero');
        }

        if ($fromUser->balance < $amount) {
            throw new \Exception('Insufficient balance');
        }

        if ($fromUser->id === $toUser->id) {
            throw new \Exception('Cannot transfer to the same account');
        }

        return DB::transaction(function () use ($fromUser, $toUser, $amount, $description) {
            $fromNewBalance = $fromUser->balance - $amount;
            $toNewBalance = $toUser->balance + $amount;

            // Create withdrawal transaction for sender
            $withdrawalTransaction = Transaction::create([
                'user_id' => $fromUser->id,
                'type' => 'transfer',
                'amount' => -$amount, // Negative amount for outgoing transfer
                'balance_after' => $fromNewBalance,
                'description' => $description ?? "Transfer to {$toUser->name}",
                'status' => 'completed',
                'processed_at' => now(),
                'metadata' => [
                    'transfer_to' => $toUser->id,
                    'transfer_reference' => 'TXN' . Str::upper(Str::random(10))
                ]
            ]);

            // Create deposit transaction for receiver
            $depositTransaction = Transaction::create([
                'user_id' => $toUser->id,
                'type' => 'transfer',
                'amount' => $amount, // Positive amount for incoming transfer
                'balance_after' => $toNewBalance,
                'description' => $description ?? "Transfer from {$fromUser->name}",
                'status' => 'completed',
                'processed_at' => now(),
                'metadata' => [
                    'transfer_from' => $fromUser->id,
                    'transfer_reference' => $withdrawalTransaction->metadata['transfer_reference']
                ]
            ]);

            // Update balances
            $fromUser->update(['balance' => $fromNewBalance]);
            $toUser->update(['balance' => $toNewBalance]);

            // Log the transfers
            AuditLog::log('transfer_out', $withdrawalTransaction, $fromUser, [], $withdrawalTransaction->toArray());
            AuditLog::log('transfer_in', $depositTransaction, $toUser, [], $depositTransaction->toArray());

            return [
                'withdrawal_transaction' => $withdrawalTransaction,
                'deposit_transaction' => $depositTransaction
            ];
        });
    }

    public function getTransactionsForUser(User $user, array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = $user->transactions();

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

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getTransactionSummary(User $user, string $period = 'month'): array
    {
        $dateField = match($period) {
            'day' => 'DATE(created_at)',
            'week' => 'YEARWEEK(created_at)',
            'month' => 'DATE_FORMAT(created_at, "%Y-%m")',
            'year' => 'YEAR(created_at)',
            default => 'DATE_FORMAT(created_at, "%Y-%m")'
        };

        $summary = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->selectRaw("
                {$dateField} as period,
                type,
                SUM(CASE WHEN amount > 0 THEN amount ELSE 0 END) as total_incoming,
                SUM(CASE WHEN amount < 0 THEN ABS(amount) ELSE 0 END) as total_outgoing,
                COUNT(*) as transaction_count
            ")
            ->groupBy('period', 'type')
            ->orderBy('period', 'desc')
            ->get();

        return $summary->toArray();
    }

    public function getBalanceHistory(User $user, int $days = 30): array
    {
        $transactions = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays($days))
            ->orderBy('created_at', 'asc')
            ->get(['created_at', 'balance_after']);

        return $transactions->map(function ($transaction) {
            return [
                'date' => $transaction->created_at->format('Y-m-d H:i:s'),
                'balance' => $transaction->balance_after
            ];
        })->toArray();
    }
}
