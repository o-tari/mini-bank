<x-app-layout :title="__('Dashboard')">
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Account Balance Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Account Balance</h3>
                    <div class="mt-2 text-3xl font-bold text-indigo-600">${{ number_format($data['balance'], 2) }}</div>
                    <p class="mt-1 text-sm text-gray-500">Available balance</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Recent Transactions -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Transactions</h3>
                        <div class="mt-4">
                            @if($data['recentTransactions']->count() > 0)
                                <div class="space-y-3">
                                    @foreach($data['recentTransactions'] as $transaction)
                                        <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $transaction->description }}</p>
                                                <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-medium {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $transaction->amount > 0 ? '+' : '' }}${{ number_format($transaction->amount, 2) }}
                                                </p>
                                                <p class="text-xs text-gray-500">{{ ucfirst($transaction->status) }}</p>
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

                <!-- Active Loans -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Active Loans</h3>
                        <div class="mt-4">
                            @if($data['activeLoans']->count() > 0)
                                <div class="space-y-3">
                                    @foreach($data['activeLoans'] as $loan)
                                        <div class="py-2 border-b border-gray-200 last:border-b-0">
                                            <p class="text-sm font-medium text-gray-900">{{ $loan->purpose }}</p>
                                            <p class="text-xs text-gray-500">
                                                ${{ number_format($loan->amount, 2) }} -
                                                {{ ucfirst($loan->status) }} -
                                                {{ $loan->duration_months }} months
                                            </p>
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

            <!-- Pending Loans -->
            @if($data['pendingLoans']->count() > 0)
            <div class="mt-6 bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Pending Loan Applications</h3>
                    <div class="mt-4">
                        <div class="space-y-3">
                            @foreach($data['pendingLoans'] as $loan)
                                <div class="py-2 border-b border-gray-200 last:border-b-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $loan->purpose }}</p>
                                    <p class="text-xs text-gray-500">
                                        ${{ number_format($loan->amount, 2) }} -
                                        {{ $loan->duration_months }} months -
                                        Applied {{ $loan->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="mt-6 bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
                    <div class="mt-4 flex flex-wrap gap-4">
                        <a href="{{ route('loans.apply') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Apply for Loan
                        </a>
                        <button onclick="showDepositModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                            Make Deposit
                        </button>
                        <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            View All Transactions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deposit Modal -->
    <div id="depositModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Make Deposit</h3>
                <form onsubmit="handleDeposit(event)">
                    <div class="mb-4">
                        <label for="depositAmount" class="block text-sm font-medium text-gray-700">Amount</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" id="depositAmount" step="0.01" min="0.01" required class="block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeDepositModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md">
                            Deposit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showDepositModal() {
            document.getElementById('depositModal').classList.remove('hidden');
        }

        function closeDepositModal() {
            document.getElementById('depositModal').classList.add('hidden');
        }

        function handleDeposit(event) {
            event.preventDefault();
            const amount = document.getElementById('depositAmount').value;

            // Make AJAX request to deposit endpoint
            fetch('{{ route("transactions.deposit") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    amount: amount,
                    description: 'Account deposit'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Deposit successful!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });

            closeDepositModal();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const depositModal = document.getElementById('depositModal');
            if (event.target === depositModal) {
                closeDepositModal();
            }
        }
    </script>
</x-app-layout>
