<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['deposit', 'withdrawal', 'loan_disbursement', 'loan_payment', 'transfer']);
        $amount = fake()->numberBetween(10, 5000);

        return [
            'user_id' => \App\Models\User::factory(),
            'loan_id' => $type === 'loan_disbursement' || $type === 'loan_payment'
                ? \App\Models\Loan::factory()
                : null,
            'type' => $type,
            'amount' => $type === 'withdrawal' ? -$amount : $amount,
            'balance_after' => fake()->numberBetween(0, 10000),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(['pending', 'completed', 'failed', 'cancelled']),
            'processed_at' => fake()->optional(0.8)->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
