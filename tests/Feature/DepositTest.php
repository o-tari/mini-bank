<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class)->in('Feature');

// test('a user can initiate a deposit', function () {
//     $user = User::factory()->create();

//     $response = $this->actingAs($user)->postJson('deposit/initiate', [
//         'amount' => 1000,
//         'currency' => 'usd',
//     ]);

//     $response->assertStatus(200)
//              ->assertJsonStructure([
//                  'checkout_url',
//              ]);

//     // Further assertions could include:
//     // - Checking if a Deposit record was created (status pending)
//     // - Checking if checkout_url is a valid Stripe URL
// });

test('a guest cannot initiate a deposit', function () {
    $response = $this->postJson('/deposit/initiate', [
        'amount' => 1000,
        'currency' => 'usd',
    ]);

    $response->assertStatus(401);
});

test('a deposit initiation requires amount and currency', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/deposit/initiate', []);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['amount']);
});

// test('stripe webhook can be received and processed', function () {
//     $payload = [
//         'id' => 'evt_' . Str::random(24),
//         'object' => 'event',
//         'type' => 'checkout.session.completed',
//         'data' => [
//             'object' => [
//                 'id' => 'cs_' . Str::random(24),
//                 'metadata' => [
//                     'user_id' => User::factory()->create()->id,
//                     'deposit_id' => 1,
//                 ],
//                 'amount_total' => 1000,
//                 'currency' => 'usd',
//                 'payment_status' => 'paid',
//             ],
//         ],
//     ];

//     $response = $this->postJson('/stripe/webhook', $payload);

//     $response->assertStatus(200);
// });
