<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanDueSoonNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Loan $loan
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
            ->subject('Loan Payment Due Soon')
            ->line('Your loan payment is due soon.')
            ->line('Loan Purpose: ' . $this->loan->purpose)
            ->line('Amount: $' . number_format($this->loan->amount, 2))
            ->line('Due Date: ' . $this->loan->due_date->format('M d, Y'))
            ->action('Make Payment', url('/loans/' . $this->loan->id . '/pay'))
            ->line('Please ensure you have sufficient funds for the payment.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'loan_id' => $this->loan->id,
            'purpose' => $this->loan->purpose,
            'amount' => $this->loan->amount,
            'due_date' => $this->loan->due_date,
            'message' => 'Your loan payment is due on ' . $this->loan->due_date->format('M d, Y'),
        ];
    }
}
