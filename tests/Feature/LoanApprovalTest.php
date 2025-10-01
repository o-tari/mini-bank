<?php

use App\Models\User;
use App\Models\Loan;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Create roles for testing
    Role::firstOrCreate(['name' => 'user']);
    Role::firstOrCreate(['name' => 'manager']);
    Role::firstOrCreate(['name' => 'admin']);
});

it('manager can approve a loan', function () {
    $manager = User::factory()->create()->assignRole('manager');
    $loan = Loan::factory()->create(['status' => 'pending']);

    $this->actingAs($manager)
        ->post(route('loans.approve', $loan), ['approval_comments' => 'Approved'])
        ->assertRedirect();

    $loan->refresh();
    expect($loan->status)->toBe('approved');
});

it('manager can reject a loan', function () {
    $manager = User::factory()->create()->assignRole('manager');
    $loan = Loan::factory()->create(['status' => 'pending']);

    $this->actingAs($manager)
        ->post(route('loans.reject', $loan), ['rejection_reason' => 'Insufficient income'])
        ->assertRedirect();

    $loan->refresh();
    expect($loan->status)->toBe('rejected')
        ->and($loan->rejection_reason)->toBe('Insufficient income');
});

it('user cannot approve their own loan', function () {
    $user = User::factory()->create()->assignRole('user');
    $loan = Loan::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

    $this->actingAs($user)
        ->post(route('loans.approve', $loan), ['approval_comments' => 'Self approval'])
        ->assertStatus(403);
});

it('admin can disburse approved loan', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $loan = Loan::factory()->create(['status' => 'approved']);

    $this->actingAs($admin)
        ->post(route('loans.disburse', $loan))
        ->assertRedirect();

    $loan->refresh();
    expect($loan->status)->toBe('disbursed')
        ->and($loan->disbursed_at)->not->toBeNull();
});

it('creates audit log when loan is approved', function () {
    $manager = User::factory()->create()->assignRole('manager');
    $loan = Loan::factory()->create(['status' => 'pending']);

    $this->actingAs($manager)
        ->post(route('loans.approve', $loan), ['approval_comments' => 'Approved']);

    expect(\App\Models\AuditLog::where('model_type', Loan::class)
        ->where('model_id', $loan->id)
        ->where('action', 'approved')
        ->exists())->toBeTrue();
});
