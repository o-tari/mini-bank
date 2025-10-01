<div>
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Account Balance</h3>
            <div class="mt-2 text-3xl font-bold text-indigo-600">${{ number_format($balance, 2) }}</div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Transactions</h3>
                <div class="mt-4">
                    @if($recentTransactions->count() > 0)
                        <div class="space-y-2">
                            @foreach($recentTransactions as $transaction)
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $transaction->description }}</p>
                                        <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->amount > 0 ? '+' : '' }}${{ number_format($transaction->amount, 2) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No recent transactions</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Active Loans</h3>
                <div class="mt-4">
                    @if($activeLoans->count() > 0)
                        <div class="space-y-2">
                            @foreach($activeLoans as $loan)
                                <div class="py-2 border-b border-gray-200">
                                    <p class="text-sm font-medium text-gray-900">{{ $loan->purpose }}</p>
                                    <p class="text-xs text-gray-500">${{ number_format($loan->amount, 2) }} - {{ ucfirst($loan->status) }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No active loans</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
            <div class="mt-4 flex space-x-4">
                <button wire:click="deposit(100)" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Deposit $100
                </button>
                <button wire:click="withdraw(50)" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    Withdraw $50
                </button>
            </div>
        </div>
    </div>
</div>