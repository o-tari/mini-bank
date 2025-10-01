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

it('creates a withdrawal transaction', function () {
    $user = User::factory()->create(['balance' => 1000]);
    $service = app(TransactionService::class);

    $transaction = $service->withdraw($user, 200, 'Test withdrawal');

    expect($transaction)->toBeInstanceOf(Transaction::class)
        ->and($transaction->type)->toBe('withdrawal')
        ->and($transaction->amount)->toBe('200.00')
        ->and($transaction->status)->toBe('completed');
});

it('throws exception for insufficient balance on withdrawal', function () {
    $user = User::factory()->create(['balance' => 100]);
    $service = app(TransactionService::class);

    expect(fn() => $service->withdraw($user, 200, 'Test withdrawal'))
        ->toThrow(Exception::class, 'Insufficient balance');
});

it('creates a transfer between users', function () {
    $fromUser = User::factory()->create(['balance' => 1000]);
    $toUser = User::factory()->create(['balance' => 500]);
    $service = app(TransactionService::class);

    $result = $service->transfer($fromUser, $toUser, 300, 'Test transfer');

    expect($result)->toHaveKeys(['withdrawal_transaction', 'deposit_transaction'])
        ->and($result['withdrawal_transaction']->amount)->toBe('-300.00')
        ->and($result['deposit_transaction']->amount)->toBe('300.00');
});

it('throws exception for transfer to same user', function () {
    $user = User::factory()->create(['balance' => 1000]);
    $service = app(TransactionService::class);

    expect(fn() => $service->transfer($user, $user, 100, 'Test transfer'))
        ->toThrow(Exception::class, 'Cannot transfer to the same account');
});

it('gets transactions for user with filters', function () {
    $user = User::factory()->create();
    $service = app(TransactionService::class);

    // Create some test transactions
    $service->deposit($user, 100, 'Deposit 1');
    $service->deposit($user, 200, 'Deposit 2');
    $service->withdraw($user, 50, 'Withdrawal 1');

    $deposits = $service->getTransactionsForUser($user, ['type' => 'deposit']);

    expect($deposits)->toHaveCount(2)
        ->and($deposits->every(fn($t) => $t->type === 'deposit'))->toBeTrue();
});
