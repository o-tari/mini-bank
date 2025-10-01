<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->get();

        $managers = User::whereHas('roles', function ($query) {
            $query->where('name', 'manager');
        })->get();

        $loans = [
            [
                'user_id' => $users[0]->id,
                'amount' => 5000.00,
                'purpose' => 'Home Improvement',
                'duration_months' => 24,
                'interest_rate' => 5.5,
                'income' => 4000.00,
                'status' => 'approved',
                'approved_by' => $managers[0]->id,
                'approved_at' => now()->subDays(5),
                'approval_comments' => 'Good credit history and stable income.',
            ],
            [
                'user_id' => $users[1]->id,
                'amount' => 15000.00,
                'purpose' => 'Car Purchase',
                'duration_months' => 36,
                'interest_rate' => 6.0,
                'income' => 3500.00,
                'status' => 'disbursed',
                'approved_by' => $managers[0]->id,
                'approved_at' => now()->subDays(10),
                'disbursed_at' => now()->subDays(8),
                'approval_comments' => 'Approved for vehicle financing.',
            ],
            [
                'user_id' => $users[2]->id,
                'amount' => 8000.00,
                'purpose' => 'Education',
                'duration_months' => 48,
                'interest_rate' => 4.5,
                'income' => 2800.00,
                'status' => 'pending',
            ],
            [
                'user_id' => $users[0]->id,
                'amount' => 3000.00,
                'purpose' => 'Emergency Fund',
                'duration_months' => 12,
                'interest_rate' => 7.0,
                'income' => 4000.00,
                'status' => 'rejected',
                'approved_by' => $managers[0]->id,
                'approved_at' => now()->subDays(3),
                'rejection_reason' => 'Insufficient income to debt ratio.',
            ],
            [
                'user_id' => $users[1]->id,
                'amount' => 12000.00,
                'purpose' => 'Business Investment',
                'duration_months' => 60,
                'interest_rate' => 8.0,
                'income' => 3500.00,
                'status' => 'completed',
                'approved_by' => $managers[0]->id,
                'approved_at' => now()->subMonths(6),
                'disbursed_at' => now()->subMonths(6),
                'approval_comments' => 'Business loan for expansion.',
            ],
        ];

        foreach ($loans as $loanData) {
            // Calculate monthly payment
            $monthlyPayment = $this->calculateMonthlyPayment(
                $loanData['amount'],
                $loanData['duration_months'],
                $loanData['interest_rate']
            );

            $loanData['monthly_payment'] = $monthlyPayment;
            $loanData['due_date'] = now()->addMonths($loanData['duration_months']);

            Loan::create($loanData);
        }
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
}
