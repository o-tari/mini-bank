<x-app-layout :title="__('Manager Dashboard')">
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Manager Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Loan Statistics -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Pending Loans</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $data['loanStats']['total_pending'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Approved Loans</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $data['loanStats']['total_approved'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Rejected Loans</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $data['loanStats']['total_rejected'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Disbursed Loans</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $data['loanStats']['total_disbursed'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Pending Loans Table -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Pending Loan Applications</h3>
                        <div class="mt-4">
                            @if($data['pendingLoans']->count() > 0)
                                <div class="space-y-3">
                                    @foreach($data['pendingLoans'] as $loan)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h4 class="text-sm font-medium text-gray-900">{{ $loan->purpose }}</h4>
                                                    <p class="text-sm text-gray-500">by {{ $loan->user->name }}</p>
                                                    <p class="text-sm text-gray-500">${{ number_format($loan->amount, 2) }} for {{ $loan->duration_months }} months</p>
                                                    <p class="text-xs text-gray-400">Applied {{ $loan->created_at->diffForHumans() }}</p>
                                                </div>
                                                <div class="flex space-x-2 ml-4">
                                                    <form method="POST" action="{{ route('loans.approve', $loan) }}" class="inline">
                                                        @csrf
                                                        <button type="button" onclick="showApprovalModal({{ $loan->id }})" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                                            Approve
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('loans.reject', $loan) }}" class="inline">
                                                        @csrf
                                                        <button type="button" onclick="showRejectionModal({{ $loan->id }})" class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                            Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    {{ $data['pendingLoans']->links() }}
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No pending loan applications</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Loan Activity -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Loan Activity</h3>
                        <div class="mt-4">
                            @if($data['recentLoans']->count() > 0)
                                <div class="space-y-3">
                                    @foreach($data['recentLoans'] as $loan)
                                        <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $loan->purpose }}</p>
                                                <p class="text-xs text-gray-500">{{ $loan->user->name }} - ${{ number_format($loan->amount, 2) }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($loan->status === 'approved') bg-green-100 text-green-800
                                                    @elseif($loan->status === 'rejected') bg-red-100 text-red-800
                                                    @elseif($loan->status === 'disbursed') bg-blue-100 text-blue-800
                                                    @else bg-yellow-100 text-yellow-800
                                                    @endif">
                                                    {{ ucfirst($loan->status) }}
                                                </span>
                                                <p class="text-xs text-gray-500 mt-1">{{ $loan->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No recent loan activity</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Statistics Chart -->
            <div class="mt-6 bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Monthly Loan Statistics</h3>
                    <div class="mt-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($data['monthlyStats'] as $month)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $month['month'] }}</h4>
                                    <div class="mt-2 space-y-1">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Pending:</span>
                                            <span class="font-medium">{{ $month['pending'] }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Approved:</span>
                                            <span class="font-medium text-green-600">{{ $month['approved'] }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Rejected:</span>
                                            <span class="font-medium text-red-600">{{ $month['rejected'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
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
