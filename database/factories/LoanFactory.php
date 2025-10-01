<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->numberBetween(1000, 50000);
        $durationMonths = fake()->numberBetween(6, 60);
        $interestRate = fake()->randomFloat(2, 3, 12);

        return [
            'user_id' => \App\Models\User::factory(),
            'amount' => $amount,
            'purpose' => fake()->randomElement([
                'Home Improvement', 'Car Purchase', 'Education', 'Business Investment',
                'Debt Consolidation', 'Medical Expenses', 'Wedding', 'Vacation'
            ]),
            'duration_months' => $durationMonths,
            'monthly_payment' => $this->calculateMonthlyPayment($amount, $durationMonths, $interestRate),
            'interest_rate' => $interestRate,
            'income' => fake()->numberBetween(2000, 10000),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'disbursed', 'completed']),
            'due_date' => now()->addMonths($durationMonths),
        ];
    }

    private function calculateMonthlyPayment(float $amount, int $durationMonths, float $interestRate): float
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
