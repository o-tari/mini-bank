<?php

namespace App\Livewire\Deposits;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\DepositService;

class DepositForm extends Component
{
    public $amount;

    protected $rules = [
        'amount' => 'required|numeric|min:1|max:10000',
    ];

    public function mount()
    {
        $this->amount = 10; // Default amount
    }

    public function initiateDeposit(DepositService $depositService)
    {
        $this->validate();

        $user = Auth::user();
        $session = $depositService->createCheckoutSession($user, $this->amount);

        if ($session) {
            return redirect($session->url);
        }

        $this->addError('amount', 'Failed to initiate deposit. Please try again.');
    }

    public function render()
    {
        return view('livewire.deposits.deposit-form');
    }
}
