<?php

namespace App\Livewire\Notifications;

use Livewire\Component;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $showDropdown = false;

    protected $listeners = ['notification-read', 'notifications-read', 'notification-deleted'];

    public function mount()
    {
        $this->loadUnreadCount();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function markAsRead($notificationId)
    {
        auth()->user()->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
        $this->loadUnreadCount();
        $this->dispatch('notification-read');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        $this->loadUnreadCount();
        $this->dispatch('notifications-read');
    }

    public function updatedShowDropdown()
    {
        if ($this->showDropdown) {
            $this->loadUnreadCount();
        }
    }

    public function loadUnreadCount()
    {
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
    }

    public function render()
    {
        $recentNotifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.notifications.notification-bell', [
            'recentNotifications' => $recentNotifications,
        ]);
    }
}
