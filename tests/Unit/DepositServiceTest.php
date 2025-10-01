<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\DepositService;
use App\Models\User;
use App\Models\Deposit;
use Stripe\Checkout\Session;

class DepositServiceTest extends TestCase
{
    protected $depositService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->depositService = new DepositService();
    }

    /** @test */
    public function it_can_create_a_stripe_checkout_session()
    {
        $user = User::factory()->create();
        $amount = 100;

        // Mock Stripe Session creation
        $this->mock('Stripe\Checkout\Session', function ($mock) use ($amount) {
            $mock->shouldReceive('create')
                 ->once()
                 ->andReturn((object) [
                     'id' => 'cs_test_123',
                     'url' => 'https://checkout.stripe.com/c/pay/cs_test_123'
                 ]);
        });

        $session = $this->depositService->createCheckoutSession($user, $amount);

        $this->assertNotNull($session);
        $this->assertEquals('https://checkout.stripe.com/c/pay/cs_test_123', $session->url);
    }

    /** @test */
    public function it_can_handle_successful_payment()
    {
        $user = User::factory()->create(['balance' => 0]);
        $deposit = Deposit::factory()->create([
            'user_id' => $user->id,
            'amount' => 50,
            'status' => 'pending',
        ]);

        $this->depositService->handleSuccessfulPayment($deposit);

        $this->assertEquals('successful', $deposit->fresh()->status);
        $this->assertEquals(50, $user->fresh()->balance);
    }

    /** @test */
    public function it_can_handle_failed_payment()
    {
        $user = User::factory()->create(['balance' => 0]);
        $deposit = Deposit::factory()->create([
            'user_id' => $user->id,
            'amount' => 50,
            'status' => 'pending',
        ]);

        $this->depositService->handleFailedPayment($deposit);

        $this->assertEquals('failed', $deposit->fresh()->status);
        $this->assertEquals(0, $user->fresh()->balance);
    }
}
