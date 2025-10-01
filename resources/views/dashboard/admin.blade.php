<x-app-layout :title="__('Admin Dashboard')">
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Overview Statistics -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $data['userStats']['total_users'] }}</dd>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Loans</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $data['loanStats']['total_loans'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Pending Loans</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $data['loanStats']['pending_loans'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Approval Ratio</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $data['loanStats']['approval_ratio'] }}%</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Recent Users -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Users</h3>
                            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-500">View all</a>
                        </div>
                        <div class="mt-4">
                            @if($data['recentUsers']->count() > 0)
                                <div class="space-y-3">
                                    @foreach($data['recentUsers'] as $user)
                                        <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-b-0">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-indigo-600">{{ substr($user->name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ${{ number_format($user->balance, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No recent users</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Audit Logs -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Audit Logs</h3>
                            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-500">View all</a>
                        </div>
                        <div class="mt-4">
                            @if($data['recentAuditLogs']->count() > 0)
                                <div class="space-y-3">
                                    @foreach($data['recentAuditLogs'] as $log)
                                        <div class="py-2 border-b border-gray-200 last:border-b-0">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ ucfirst($log->action) }}</p>
                                                    <p class="text-xs text-gray-500">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</p>
                                                    @if($log->user)
                                                        <p class="text-xs text-gray-400">by {{ $log->user->name }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($log->action === 'created') bg-green-100 text-green-800
                                                        @elseif($log->action === 'updated') bg-blue-100 text-blue-800
                                                        @elseif($log->action === 'deleted') bg-red-100 text-red-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ ucfirst($log->action) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No recent audit logs</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Statistics -->
            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">User Statistics</h3>
                        <div class="mt-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">This Month:</span>
                                <span class="text-sm font-medium">{{ $data['userStats']['users_this_month'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Active Users:</span>
                                <span class="text-sm font-medium">{{ $data['userStats']['active_users'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">With Loans:</span>
                                <span class="text-sm font-medium">{{ $data['userStats']['users_with_loans'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Loan Statistics</h3>
                        <div class="mt-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Approved:</span>
                                <span class="text-sm font-medium text-green-600">{{ $data['loanStats']['approved_loans'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Rejected:</span>
                                <span class="text-sm font-medium text-red-600">{{ $data['loanStats']['rejected_loans'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Approval Rate:</span>
                                <span class="text-sm font-medium">{{ $data['loanStats']['approval_ratio'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
                        <div class="mt-4 space-y-2">
                            <a href="#" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                                Manage Users
                            </a>
                            <a href="#" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                                View All Loans
                            </a>
                            <a href="#" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                                Generate Reports
                            </a>
                            <a href="#" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                                System Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
