<div>
    <!-- Filters -->
    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
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
                <label for="purposeFilter" class="block text-sm font-medium text-gray-700">Purpose</label>
                <select wire:model="purposeFilter" id="purposeFilter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Purposes</option>
                    @foreach($purposes as $purpose)
                        <option value="{{ $purpose }}">{{ $purpose }}</option>
                    @endforeach
                </select>
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
                       placeholder="Search loans..."
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if($loans->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($loans as $loan)
                    <li class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $loan->purpose }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($loan->status === 'approved') bg-green-100 text-green-800
                                            @elseif($loan->status === 'rejected') bg-red-100 text-red-800
                                            @elseif($loan->status === 'disbursed') bg-blue-100 text-blue-800
                                            @elseif($loan->status === 'completed') bg-gray-100 text-gray-800
                                            @elseif($loan->status === 'defaulted') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-1 flex items-center text-sm text-gray-500">
                                    <p class="truncate">
                                        @if($showUserLoans)
                                            Applied {{ $loan->created_at->format('M d, Y') }} •
                                            ${{ number_format($loan->amount, 2) }} •
                                            {{ $loan->duration_months }} months
                                        @else
                                            by {{ $loan->user->name }} •
                                            Applied {{ $loan->created_at->format('M d, Y') }} •
                                            ${{ number_format($loan->amount, 2) }} •
                                            {{ $loan->duration_months }} months
                                        @endif
                                    </p>
                                </div>
                                @if($loan->approval_comments)
                                    <div class="mt-1 text-sm text-gray-600">
                                        <strong>Comments:</strong> {{ $loan->approval_comments }}
                                    </div>
                                @endif
                                @if($loan->rejection_reason)
                                    <div class="mt-1 text-sm text-red-600">
                                        <strong>Rejection Reason:</strong> {{ $loan->rejection_reason }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-shrink-0 flex space-x-2">
                                @if(!$showUserLoans && $loan->status === 'pending')
                                    <button wire:click="approveLoan({{ $loan->id }})"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        Approve
                                    </button>
                                    <button wire:click="rejectLoan({{ $loan->id }})"
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Reject
                                    </button>
                                @elseif(!$showUserLoans && $loan->status === 'approved')
                                    <button wire:click="disburseLoan({{ $loan->id }})"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        Disburse
                                    </button>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <!-- Pagination -->
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $loans->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No loans found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($showUserLoans)
                        You haven't applied for any loans yet.
                    @else
                        No loans match your current filters.
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- Rejection Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Loan</h3>
                <form onsubmit="handleRejection(event)">
                    <div class="mb-4">
                        <label for="rejectionReason" class="block text-sm font-medium text-gray-700">Rejection Reason *</label>
                        <textarea id="rejectionReason" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">
                            Reject Loan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentLoanId = null;

        function showRejectModal(loanId) {
            currentLoanId = loanId;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            currentLoanId = null;
        }

        function handleRejection(event) {
            event.preventDefault();
            const reason = document.getElementById('rejectionReason').value;
            if (currentLoanId) {
                @this.rejectLoanConfirm(currentLoanId, reason);
                closeRejectModal();
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const rejectModal = document.getElementById('rejectModal');
            if (event.target === rejectModal) {
                closeRejectModal();
            }
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('show-reject-modal', (event) => {
                showRejectModal(event[0]);
            });

            Livewire.on('loan-approved', (event) => {
                alert(event[0]);
                location.reload();
            });

            Livewire.on('loan-rejected', (event) => {
                alert(event[0]);
                location.reload();
            });

            Livewire.on('loan-disbursed', (event) => {
                alert(event[0]);
                location.reload();
            });

            Livewire.on('loan-error', (event) => {
                alert('Error: ' + event[0]);
            });
        });
    </script>
</div>
