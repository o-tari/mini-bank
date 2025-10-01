<?php

use App\Models\User;
use App\Models\Transaction;
use App\Services\TransactionService;

it('creates a deposit transaction', function () {
    $user = User::factory()->create(['balance' => 1000]);
    $service = app(TransactionService::class);

    $transaction = $service->deposit($user, 500, 'Test deposit');

    expect($transaction)->toBeInstanceOf(Transaction::class)
        ->and($transaction->type)->toBe('deposit')
        ->and($transaction->amount)->toBe('500.00')
        ->and($transaction->status)->toBe('completed');
});
