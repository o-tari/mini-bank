<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Loan $loan,
        public string $status,
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
            ->subject("Loan Application Update - {$this->loan->purpose}")
            ->line($this->message)
            ->line("Loan Amount: $" . number_format($this->loan->amount, 2))
            ->line("Status: " . ucfirst($this->status))
            ->action('View Loan Details', url('/loans/' . $this->loan->id))
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
            'loan_id' => $this->loan->id,
            'status' => $this->status,
            'message' => $this->message,
            'amount' => $this->loan->amount,
            'purpose' => $this->loan->purpose,
        ];
    }
}
