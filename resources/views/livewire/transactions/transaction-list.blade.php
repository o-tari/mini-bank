<div>
    <!-- Filters -->
    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <div>
                <label for="typeFilter" class="block text-sm font-medium text-gray-700">Type</label>
                <select wire:model="typeFilter" id="typeFilter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}">{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="statusFilter" class="block text-sm font-medium text-gray-700">Status</label>
                <select wire:model="statusFilter" id="statusFilter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="dateFrom" class="block text-sm font-medium text-gray-700">From Date</label>
                <input type="date" wire:model="dateFrom" id="dateFrom" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="dateTo" class="block text-sm font-medium text-gray-700">To Date</label>
                <input type="date" wire:model="dateTo" id="dateTo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div class="flex items-end">
                <button wire:click="clearFilters" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md">
                    Clear Filters
                </button>
            </div>
        </div>

        <div class="mt-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text"
                       wire:model="search"
                       placeholder="Search transactions..."
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-6 flex flex-wrap gap-4">
        <button onclick="showDepositModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Make Deposit
        </button>
        <button onclick="showWithdrawalModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
            Make Withdrawal
        </button>
        <button wire:click="exportCsv" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export CSV
        </button>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if($transactions->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                    <li class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $transaction->description }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($transaction->status === 'completed') bg-green-100 text-green-800
                                            @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-1 flex items-center text-sm text-gray-500">
                                    <p class="truncate">
                                        {{ $transaction->created_at->format('M d, Y H:i') }} •
                                        {{ ucfirst(str_replace('_', ' ', $transaction->type)) }} •
                                        Ref: {{ $transaction->reference }}
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <p class="text-sm font-medium {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->amount > 0 ? '+' : '' }}${{ number_format($transaction->amount, 2) }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Balance: ${{ number_format($transaction->balance_after, 2) }}
                                </p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <!-- Pagination -->
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by making your first deposit or withdrawal.</p>
            </div>
        @endif
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

    <!-- Withdrawal Modal -->
    <div id="withdrawalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Make Withdrawal</h3>
                <form onsubmit="handleWithdrawal(event)">
                    <div class="mb-4">
                        <label for="withdrawalAmount" class="block text-sm font-medium text-gray-700">Amount</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" id="withdrawalAmount" step="0.01" min="0.01" required class="block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Available balance: ${{ number_format(auth()->user()->balance, 2) }}</p>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeWithdrawalModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">
                            Withdraw
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

        function showWithdrawalModal() {
            document.getElementById('withdrawalModal').classList.remove('hidden');
        }

        function closeWithdrawalModal() {
            document.getElementById('withdrawalModal').classList.add('hidden');
        }

        function handleDeposit(event) {
            event.preventDefault();
            const amount = document.getElementById('depositAmount').value;
            @this.deposit(amount);
            closeDepositModal();
        }

        function handleWithdrawal(event) {
            event.preventDefault();
            const amount = document.getElementById('withdrawalAmount').value;
            @this.withdraw(amount);
            closeWithdrawalModal();
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const depositModal = document.getElementById('depositModal');
            const withdrawalModal = document.getElementById('withdrawalModal');

            if (event.target === depositModal) {
                closeDepositModal();
            }
            if (event.target === withdrawalModal) {
                closeWithdrawalModal();
            }
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('deposit-success', (event) => {
                alert(event[0]);
                location.reload();
            });

            Livewire.on('deposit-error', (event) => {
                alert('Error: ' + event[0]);
            });

            Livewire.on('withdrawal-success', (event) => {
                alert(event[0]);
                location.reload();
            });

            Livewire.on('withdrawal-error', (event) => {
                alert('Error: ' + event[0]);
            });

            Livewire.on('export-success', (event) => {
                alert(event[0]);
            });

            Livewire.on('export-error', (event) => {
                alert('Error: ' + event[0]);
            });
        });
    </script>
</div>
