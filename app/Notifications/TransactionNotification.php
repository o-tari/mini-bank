<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Transaction $transaction,
        public string $message
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
            ->subject('Transaction Update')
            ->line($this->message)
            ->line('Transaction Reference: ' . $this->transaction->reference)
            ->line('Amount: $' . number_format($this->transaction->amount, 2))
            ->line('New Balance: $' . number_format($this->transaction->balance_after, 2))
            ->action('View Transaction', url('/transactions/' . $this->transaction->id))
            ->line('Thank you for using our banking platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'transaction_id' => $this->transaction->id,
            'type' => $this->transaction->type,
            'amount' => $this->transaction->amount,
            'message' => $this->message,
            'reference' => $this->transaction->reference,
        ];
    }
}
