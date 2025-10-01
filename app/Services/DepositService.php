<?php

namespace App\Services;

use App\Models\User;
use App\Models\Deposit;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;

class DepositService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession(User $user, $amount): ?Session
    {
        try {
            $session = Session::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Deposit to Account',
                        ],
                        'unit_amount' => $amount * 100, // Amount in cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('deposit.success'),
                'cancel_url' => route('deposit.cancel'),
                'metadata' => [
                    'user_id' => $user->id,
                    'amount' => $amount,
                ],
            ]);

            // Create a pending deposit record
            Deposit::create([
                'user_id' => $user->id,
                'stripe_transaction_id' => $session->id,
                'amount' => $amount,
                'currency' => 'USD',
                'status' => 'pending',
            ]);

            return $session;
        } catch (\Exception $e) {
            Log::error("Stripe Checkout Session creation failed: " . $e->getMessage());
            return null;
        }
    }

    public function handleSuccessfulPayment(Deposit $deposit): void
    {
        $deposit->status = 'successful';
        $deposit->save();

        $user = $deposit->user;
        $user->balance += $deposit->amount;
        $user->save();

        // Log audit trail
        \App\Models\AuditLog::create([
            'user_id' => $user->id,
            'action' => 'deposit_successful',
            'description' => 'User ' . $user->name . ' successfully deposited ' . $deposit->amount . ' USD.',
        ]);
    }

    public function handleFailedPayment(Deposit $deposit): void
    {
        $deposit->status = 'failed';
        $deposit->save();

        // Log audit trail
        \App\Models\AuditLog::create([
            'user_id' => $deposit->user->id,
            'action' => 'deposit_failed',
            'description' => 'User ' . $deposit->user->name . ' failed to deposit ' . $deposit->amount . ' USD.',
        ]);
    }
}
