<?php

use App\Livewire\Dashboard\UserDashboard;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Create roles for testing
    Role::firstOrCreate(['name' => 'user']);
    Role::firstOrCreate(['name' => 'manager']);
    Role::firstOrCreate(['name' => 'admin']);
});

it('renders user dashboard with balance', function () {
    $user = User::factory()->create(['balance' => 500]);

    Livewire::actingAs($user)
        ->test(UserDashboard::class)
        ->assertSee('500');
});

it('shows recent transactions', function () {
    $user = User::factory()->create();
    \App\Models\Transaction::factory()->count(3)->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(UserDashboard::class)
        ->assertSee('Recent Transactions');
});

it('shows active loans', function () {
    $user = User::factory()->create();
    \App\Models\Loan::factory()->create([
        'user_id' => $user->id,
        'status' => 'approved'
    ]);

    Livewire::actingAs($user)
        ->test(UserDashboard::class)
        ->assertSee('Active Loans');
});

it('can make a deposit', function () {
    $user = User::factory()->create(['balance' => 1000]);

    Livewire::actingAs($user)
        ->test(UserDashboard::class)
        ->call('deposit', 500)
        ->assertDispatched('deposit-success');

    $user->refresh();
    expect($user->balance)->toBe('1500.00');
});

it('can make a withdrawal', function () {
    $user = User::factory()->create(['balance' => 1000]);

    Livewire::actingAs($user)
        ->test(UserDashboard::class)
        ->call('withdraw', 300)
        ->assertDispatched('withdrawal-success');

    $user->refresh();
    expect($user->balance)->toBe('700.00');
});

it('validates withdrawal amount', function () {
    $user = User::factory()->create(['balance' => 100]);

    Livewire::actingAs($user)
        ->test(UserDashboard::class)
        ->call('withdraw', 200)
        ->assertHasErrors(['amount']);
});
