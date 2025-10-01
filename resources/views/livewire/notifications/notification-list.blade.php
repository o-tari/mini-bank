<div>
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
                <div class="flex items-center space-x-2">
                    @if($unreadCount > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ $unreadCount }} unread
                        </span>
                    @endif
                    @if($notifications->count() > 0)
                        <button
                            wire:click="markAllAsRead"
                            class="text-sm text-blue-600 hover:text-blue-800"
                        >
                            Mark all as read
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <div class="px-6 py-4 {{ $notification->read_at ? 'bg-gray-50' : 'bg-white' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2">
                                @if(!$notification->read_at)
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                @endif
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $notification->data['message'] ?? 'Notification' }}
                                </p>
                            </div>

                            @if(isset($notification->data['amount']))
                                <p class="text-sm text-gray-600 mt-1">
                                    Amount: ${{ number_format($notification->data['amount'], 2) }}
                                </p>
                            @endif

                            @if(isset($notification->data['purpose']))
                                <p class="text-sm text-gray-600 mt-1">
                                    Purpose: {{ $notification->data['purpose'] }}
                                </p>
                            @endif

                            <p class="text-xs text-gray-500 mt-2">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-2 ml-4">
                            @if(!$notification->read_at)
                                <button
                                    wire:click="markAsRead('{{ $notification->id }}')"
                                    class="text-xs text-blue-600 hover:text-blue-800"
                                >
                                    Mark as read
                                </button>
                            @endif

                            <button
                                wire:click="deleteNotification('{{ $notification->id }}')"
                                class="text-xs text-red-600 hover:text-red-800"
                                onclick="return confirm('Are you sure you want to delete this notification?')"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center">
                    <div class="text-gray-400 mb-2">
                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.5 7.5 0 1 0-15 0v5h5l-5 5-5-5h5v-5a7.5 7.5 0 1 0 15 0v5z" />
                        </svg>
                    </div>
                    <p class="text-gray-500">No notifications yet</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
