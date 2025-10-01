<div class="relative" x-data="{ open: false }">
    <button
        @click="open = !open"
        class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
    >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.5 7.5 0 1 0-15 0v5h5l-5 5-5-5h5v-5a7.5 7.5 0 1 0 15 0v5z" />
        </svg>

        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50"
        style="display: none;"
    >
        <div class="py-1">
            <div class="px-4 py-2 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                    @if($unreadCount > 0)
                        <button
                            wire:click="markAllAsRead"
                            class="text-xs text-blue-600 hover:text-blue-800"
                        >
                            Mark all as read
                        </button>
                    @endif
                </div>
            </div>

            <div class="max-h-96 overflow-y-auto">
                @forelse($recentNotifications as $notification)
                    <div class="px-4 py-3 hover:bg-gray-50 {{ $notification->read_at ? 'bg-gray-50' : 'bg-white' }}">
                        <div class="flex items-start space-x-3">
                            @if(!$notification->read_at)
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    {{ $notification->data['message'] ?? 'Notification' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if(!$notification->read_at)
                                <button
                                    wire:click="markAsRead('{{ $notification->id }}')"
                                    class="text-xs text-blue-600 hover:text-blue-800 flex-shrink-0"
                                >
                                    Mark as read
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center">
                        <p class="text-sm text-gray-500">No notifications</p>
                    </div>
                @endforelse
            </div>

            @if($recentNotifications->count() > 0)
                <div class="px-4 py-2 border-t border-gray-200">
                    <a
                        href="{{ route('notifications') }}"
                        class="text-sm text-blue-600 hover:text-blue-800"
                    >
                        View all notifications
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
