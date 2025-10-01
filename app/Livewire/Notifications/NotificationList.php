<?php

namespace App\Livewire\Notifications;

use App\Services\NotificationService;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationList extends Component
{
    use WithPagination;

    public $unreadCount = 0;

    public function mount()
    {
        $this->loadUnreadCount();
    }

    public function markAsRead($notificationId = null)
    {
        $notificationService = app(NotificationService::class);
        $notificationService->markAsRead(auth()->user(), $notificationId);

        $this->loadUnreadCount();
        $this->dispatch('notification-read');
    }

    public function markAllAsRead()
    {
        $notificationService = app(NotificationService::class);
        $notificationService->markAsRead(auth()->user());

        $this->loadUnreadCount();
        $this->dispatch('notifications-read');
    }

    public function deleteNotification($notificationId)
    {
        auth()->user()->notifications()->where('id', $notificationId)->delete();
        $this->loadUnreadCount();
        $this->dispatch('notification-deleted');
    }

    public function loadUnreadCount()
    {
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
    }

    public function render()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.notifications.notification-list', [
            'notifications' => $notifications,
        ]);
    }
}
