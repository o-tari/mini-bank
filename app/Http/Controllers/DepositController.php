<?php

namespace App\Http\Controllers;

use App\Services\DepositService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DepositController extends Controller
{
    protected $depositService;

    public function __construct(DepositService $depositService)
    {
        $this->depositService = $depositService;
    }

    public function initiate(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:10000'],
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        $session = $this->depositService->createCheckoutSession($user, $amount);

        if ($session) {
            return response()->json(['checkout_url' => $session->url]);
        }

        return response()->json(['message' => 'Failed to initiate deposit'], 500);
    }

    public function success()
    {
        // Handle successful deposit, e.g., show a success message
        return redirect('/dashboard')->with('success', 'Deposit successful!');
    }

    public function cancel()
    {
        // Handle cancelled deposit, e.g., show a cancellation message
        return redirect('/dashboard')->with('error', 'Deposit cancelled. ');
    }
}
