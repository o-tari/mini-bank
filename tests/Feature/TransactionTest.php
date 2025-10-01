<?php

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Create roles for testing
    Role::firstOrCreate(['name' => 'user']);
    Role::firstOrCreate(['name' => 'manager']);
    Role::firstOrCreate(['name' => 'admin']);
});

it('user can make a deposit', function () {
    $user = User::factory()->create(['balance' => 1000]);

    $this->actingAs($user)
        ->post(route('transactions.deposit'), [
            'amount' => 500,
            'description' => 'Test deposit'
        ])
        ->assertRedirect();

    $user->refresh();
    expect($user->balance)->toBe('1500.00');
});

it('user can make a withdrawal', function () {
    $user = User::factory()->create(['balance' => 1000]);

    $this->actingAs($user)
        ->post(route('transactions.withdraw'), [
            'amount' => 300,
            'description' => 'Test withdrawal'
        ])
        ->assertRedirect();

    $user->refresh();
    expect($user->balance)->toBe('700.00');
});

it('user cannot withdraw more than balance', function () {
    $user = User::factory()->create(['balance' => 100]);

    $this->actingAs($user)
        ->post(route('transactions.withdraw'), [
            'amount' => 200,
            'description' => 'Test withdrawal'
        ])
        ->assertSessionHasErrors(['amount']);
});

it('user can view their transactions', function () {
    $user = User::factory()->create();
    Transaction::factory()->count(5)->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('transactions.index'))
        ->assertOk()
        ->assertSee('Transaction History');
});

it('manager can view all transactions', function () {
    $manager = User::factory()->create()->assignRole('manager');
    Transaction::factory()->count(10)->create();

    $this->actingAs($manager)
        ->get(route('admin.transactions.index'))
        ->assertOk()
        ->assertSee('All Transactions');
});

it('creates transaction with unique reference', function () {
    $user = User::factory()->create();
    $transaction1 = Transaction::factory()->create(['user_id' => $user->id]);
    $transaction2 = Transaction::factory()->create(['user_id' => $user->id]);

    expect($transaction1->reference)->not->toBe($transaction2->reference)
        ->and($transaction1->reference)->toStartWith('TXN')
        ->and($transaction2->reference)->toStartWith('TXN');
});
