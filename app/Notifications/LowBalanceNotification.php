<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowBalanceNotification extends Notification
{
    use Queueable;

    public function __construct(
        public float $currentBalance,
        public float $threshold
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Low Balance Alert')
            ->line('Your account balance is below the threshold.')
            ->line('Current Balance: $' . number_format($this->currentBalance, 2))
            ->line('Threshold: $' . number_format($this->threshold, 2))
            ->action('Add Funds', url('/deposit'))
            ->line('Please consider adding funds to your account.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'current_balance' => $this->currentBalance,
            'threshold' => $this->threshold,
            'message' => 'Your account balance is below the threshold of $' . number_format($this->threshold, 2),
        ];
    }
}
