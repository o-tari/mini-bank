<?php

use App\Models\User;
use App\Models\Loan;
use App\Services\LoanService;
use Tests\Feature\TestCase;



it('creates a loan request with valid data', function () {
    $user = User::factory()->create();
    $service = app(LoanService::class);

    $loan = $service->createLoan($user, [
        'amount' => 1000,
        'purpose' => 'Education',
        'duration_months' => 12,
        'income' => 2000,
    ]);

    expect($loan)->toBeInstanceOf(Loan::class)
        ->and($loan->status)->toBe('pending')
        ->and($loan->user_id)->toBe($user->id);
});

it('calculates monthly payment correctly', function () {
    $user = User::factory()->create();
    $service = app(LoanService::class);

    $loan = $service->createLoan($user, [
        'amount' => 10000,
        'purpose' => 'Car Loan',
        'duration_months' => 24,
        'income' => 3000,
        'interest_rate' => 5.0,
    ]);

    expect($loan->monthly_payment)->toBeGreaterThan(400)
        ->and($loan->monthly_payment)->toBeLessThan(500);
});

it('approves a loan with manager role', function () {
    // Create roles first
    \Spatie\Permission\Models\Role::create(['name' => 'manager']);

    $manager = User::factory()->create()->assignRole('manager');
    $user = User::factory()->create();
    $service = app(LoanService::class);

    $loan = $service->createLoan($user, [
        'amount' => 1000,
        'purpose' => 'Education',
        'duration_months' => 12,
        'income' => 2000,
    ]);

    $approvedLoan = $service->approveLoan($loan, $manager, 'Good credit history');

    expect($approvedLoan->status)->toBe('approved')
        ->and($approvedLoan->approved_by)->toBe($manager->id)
        ->and($approvedLoan->approval_comments)->toBe('Good credit history');
});

it('throws exception when non-manager tries to approve loan', function () {
    $user = User::factory()->create();
    $service = app(LoanService::class);

    $loan = $service->createLoan($user, [
        'amount' => 1000,
        'purpose' => 'Education',
        'duration_months' => 12,
        'income' => 2000,
    ]);

    expect(fn() => $service->approveLoan($loan, $user, 'Test'))
        ->toThrow(Exception::class, 'User does not have permission to approve loans');
});

it('disburses approved loan', function () {
    // Create roles first
    \Spatie\Permission\Models\Role::create(['name' => 'manager']);

    $manager = User::factory()->create()->assignRole('manager');
    $user = User::factory()->create(['balance' => 1000]);
    $service = app(LoanService::class);

    $loan = $service->createLoan($user, [
        'amount' => 5000,
        'purpose' => 'Home Improvement',
        'duration_months' => 24,
        'income' => 3000,
    ]);

    $service->approveLoan($loan, $manager, 'Approved');
    $disbursedLoan = $service->disburseLoan($loan, $manager);

    expect($disbursedLoan->status)->toBe('disbursed')
        ->and($disbursedLoan->disbursed_at)->not->toBeNull();
});
