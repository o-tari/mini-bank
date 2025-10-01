<?php

use App\Livewire\Loans\LoanForm;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Create roles for testing
    Role::firstOrCreate(['name' => 'user']);
    Role::firstOrCreate(['name' => 'manager']);
    Role::firstOrCreate(['name' => 'admin']);
});

it('validates loan form submission', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(LoanForm::class)
        ->set('amount', null)
        ->call('submit')
        ->assertHasErrors(['amount' => 'required']);
});

it('submits valid loan form', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create()->assignRole('admin');

    Livewire::actingAs($user)
        ->test(LoanForm::class)
        ->set('amount', 1000)
        ->set('purpose', 'Car Loan')
        ->set('duration_months', 24)
        ->set('income', 3000)
        ->call('submit')
        ->assertDispatched('loan-submitted');
});

it('validates minimum loan amount', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(LoanForm::class)
        ->set('amount', 50)
        ->set('purpose', 'Test')
        ->set('duration_months', 12)
        ->set('income', 2000)
        ->call('submit')
        ->assertHasErrors(['amount' => 'min']);
});

it('validates maximum loan amount', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(LoanForm::class)
        ->set('amount', 200000)
        ->set('purpose', 'Test')
        ->set('duration_months', 12)
        ->set('income', 2000)
        ->call('submit')
        ->assertHasErrors(['amount' => 'max']);
});

it('validates minimum income', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(LoanForm::class)
        ->set('amount', 1000)
        ->set('purpose', 'Test')
        ->set('duration_months', 12)
        ->set('income', 500)
        ->call('submit')
        ->assertHasErrors(['income' => 'min']);
});

it('calculates monthly payment', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(LoanForm::class)
        ->set('amount', 10000)
        ->set('duration_months', 24)
        ->set('interest_rate', 5.0)
        ->assertSee('Monthly Payment');
});

it('resets form after submission', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(LoanForm::class)
        ->set('amount', 1000)
        ->set('purpose', 'Test')
        ->set('duration_months', 12)
        ->set('income', 2000)
        ->call('submit')
        ->assertSet('amount', '')
        ->assertSet('purpose', '');
});
