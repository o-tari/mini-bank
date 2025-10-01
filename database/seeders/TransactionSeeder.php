<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $loans = Loan::all();

        // Create some initial deposits for users
        foreach ($users as $user) {
            // Initial deposit
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => 1000.00, // Initial deposit amount
                'balance_after' => $user->balance + 1000.00, // Assuming initial balance is 0 before this deposit
                'description' => 'Initial account deposit',
                'status' => 'completed',
                'processed_at' => now()->subDays(30),
            ]);
        }

        // Create loan disbursement transactions
        foreach ($loans as $loan) {
            if (in_array($loan->status, ['disbursed', 'completed'])) {
                Transaction::create([
                    'user_id' => $loan->user_id,
                    'loan_id' => $loan->id,
                    'type' => 'loan_disbursement',
                    'amount' => $loan->amount,
                    'balance_after' => $loan->user->balance + $loan->amount,
                    'description' => "Loan disbursement for {$loan->purpose}",
                    'status' => 'completed',
                    'processed_at' => $loan->disbursed_at ?? now()->subDays(10),
                ]);

                // Update user balance
                $loan->user->increment('balance', $loan->amount);
            }
        }

        // Create some sample transactions
        $sampleTransactions = [
            [
                'user_id' => $users[2]->id, // John Doe
                'type' => 'deposit',
                'amount' => 500.00,
                'description' => 'Salary deposit',
            ],
            [
                'user_id' => $users[3]->id, // Jane Smith
                'type' => 'withdrawal',
                'amount' => 200.00,
                'description' => 'ATM withdrawal',
            ],
            [
                'user_id' => $users[4]->id, // Bob Johnson
                'type' => 'deposit',
                'amount' => 1000.00,
                'description' => 'Freelance payment',
            ],
            [
                'user_id' => $users[2]->id, // John Doe
                'type' => 'withdrawal',
                'amount' => 150.00,
                'description' => 'Online purchase',
            ],
        ];

        foreach ($sampleTransactions as $transactionData) {
            $user = User::find($transactionData['user_id']);
            $newBalance = $user->balance + ($transactionData['amount'] * ($transactionData['type'] === 'deposit' ? 1 : -1));

            Transaction::create([
                'user_id' => $transactionData['user_id'],
                'type' => $transactionData['type'],
                'amount' => $transactionData['amount'] * ($transactionData['type'] === 'deposit' ? 1 : -1),
                'balance_after' => $newBalance,
                'description' => $transactionData['description'],
                'status' => 'completed',
                'processed_at' => now()->subDays(rand(1, 20)),
            ]);

            // Update user balance
            $user->update(['balance' => $newBalance]);
        }

        // Create loan payment transactions for completed loans
        $completedLoans = Loan::where('status', 'completed')->get();
        foreach ($completedLoans as $loan) {
            $totalPaid = 0;
            $monthlyPayment = $loan->monthly_payment;
            $monthsPaid = 0;

            while ($totalPaid < $loan->amount && $monthsPaid < $loan->duration_months) {
                $paymentAmount = min($monthlyPayment, $loan->amount - $totalPaid);
                $totalPaid += $paymentAmount;
                $monthsPaid++;

                Transaction::create([
                    'user_id' => $loan->user_id,
                    'loan_id' => $loan->id,
                    'type' => 'loan_payment',
                    'amount' => $paymentAmount,
                    'balance_after' => $loan->user->balance - $paymentAmount,
                    'description' => "Monthly loan payment for {$loan->purpose}",
                    'status' => 'completed',
                    'processed_at' => now()->subMonths($loan->duration_months - $monthsPaid),
                ]);

                // Update user balance
                $loan->user->decrement('balance', $paymentAmount);
            }
        }
    }
}
