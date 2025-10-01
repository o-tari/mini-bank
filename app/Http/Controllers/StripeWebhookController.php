<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Services\DepositService;
use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Stripe\Webhook;
use Stripe\Event;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends CashierController
{
    protected $depositService;

    public function __construct(DepositService $depositService)
    {
        $this->depositService = $depositService;
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload, $signature, $webhookSecret
            );
        } catch (
UnexpectedValueException $e) {
            // Invalid payload
            return new Response('', 400);
        } catch (
Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return new Response('', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $depositId = $session->metadata->deposit_id ?? null; // Assuming you store deposit_id in metadata

                if ($depositId) {
                    $deposit = Deposit::find($depositId);
                    if ($deposit && $deposit->status === 'pending') {
                        $this->depositService->handleSuccessfulPayment($deposit);
                    }
                }
                break;
            // ... handle other event types
            default:
                // Unexpected event type
                return new Response('', 400);
        }

        return new Response('', 200);
    }
}
