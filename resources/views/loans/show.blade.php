<x-app-layout :title="__('Loan Details')">
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Loan Details</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('loans.index') }}" class="text-indigo-600 hover:text-indigo-500">
                            ← Back to Loans
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Loan Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Loan Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Purpose</dt>
                                    <dd class="text-sm text-gray-900">{{ $loan->purpose }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Amount</dt>
                                    <dd class="text-sm text-gray-900">${{ number_format($loan->amount, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Duration</dt>
                                    <dd class="text-sm text-gray-900">{{ $loan->duration_months }} months</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Interest Rate</dt>
                                    <dd class="text-sm text-gray-900">{{ $loan->interest_rate }}%</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Monthly Payment</dt>
                                    <dd class="text-sm text-gray-900">${{ number_format($loan->monthly_payment, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="text-sm">
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
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Applicant Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Applicant Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="text-sm text-gray-900">{{ $loan->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900">{{ $loan->user->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Monthly Income</dt>
                                    <dd class="text-sm text-gray-900">${{ number_format($loan->income, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Applied Date</dt>
                                    <dd class="text-sm text-gray-900">{{ $loan->created_at->format('M d, Y H:i') }}</dd>
                                </div>
                                @if($loan->approver)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Approved By</dt>
                                    <dd class="text-sm text-gray-900">{{ $loan->approver->name }}</dd>
                                </div>
                                @endif
                                @if($loan->approved_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Approved Date</dt>
                                    <dd class="text-sm text-gray-900">{{ $loan->approved_at->format('M d, Y H:i') }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    @if($loan->approval_comments)
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Approval Comments</h3>
                        <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-md">{{ $loan->approval_comments }}</p>
                    </div>
                    @endif

                    @if($loan->rejection_reason)
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Rejection Reason</h3>
                        <p class="text-sm text-red-700 bg-red-50 p-3 rounded-md">{{ $loan->rejection_reason }}</p>
                    </div>
                    @endif

                    <!-- Actions -->
                    @if(auth()->user()->hasRole(['manager', 'admin']))
                    <div class="mt-6 flex justify-end space-x-3">
                        @if($loan->status === 'pending')
                            <button onclick="showApprovalModal({{ $loan->id }})" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                Approve Loan
                            </button>
                            <button onclick="showRejectionModal({{ $loan->id }})" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Reject Loan
                            </button>
                        @elseif($loan->status === 'approved')
                            <form method="POST" action="{{ route('loans.disburse', $loan) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Disburse Loan
                                </button>
                            </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Transactions -->
            @if($loan->transactions->count() > 0)
            <div class="mt-6 bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Related Transactions</h3>
                    <div class="space-y-3">
                        @foreach($loan->transactions as $transaction)
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $transaction->description }}</p>
                                    <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y H:i') }} • {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</p>
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
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Approval Modal -->
    <div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Approve Loan</h3>
                <form id="approvalForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="approval_comments" class="block text-sm font-medium text-gray-700">Approval Comments (Optional)</label>
                        <textarea id="approval_comments" name="approval_comments" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeApprovalModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md">
                            Approve Loan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Loan</h3>
                <form id="rejectionForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Rejection Reason *</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectionModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
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
        function showApprovalModal(loanId) {
            document.getElementById('approvalForm').action = `/loans/${loanId}/approve`;
            document.getElementById('approvalModal').classList.remove('hidden');
        }

        function closeApprovalModal() {
            document.getElementById('approvalModal').classList.add('hidden');
        }

        function showRejectionModal(loanId) {
            document.getElementById('rejectionForm').action = `/loans/${loanId}/reject`;
            document.getElementById('rejectionModal').classList.remove('hidden');
        }

        function closeRejectionModal() {
            document.getElementById('rejectionModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const approvalModal = document.getElementById('approvalModal');
            const rejectionModal = document.getElementById('rejectionModal');

            if (event.target === approvalModal) {
                closeApprovalModal();
            }
            if (event.target === rejectionModal) {
                closeRejectionModal();
            }
        }
    </script>
</x-app-layout>
