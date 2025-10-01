<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminLoanSubmittedNotification extends Notification
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
            ->subject('New Loan Application Submitted')
            ->line('A new loan application has been submitted and requires review.')
            ->line('Applicant: ' . $this->loan->user->name)
            ->line('Purpose: ' . $this->loan->purpose)
            ->line('Amount: $' . number_format($this->loan->amount, 2))
            ->line('Duration: ' . $this->loan->duration_months . ' months')
            ->action('Review Application', url('/admin/loans/' . $this->loan->id))
            ->line('Please review the application as soon as possible.');
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
            'applicant_name' => $this->loan->user->name,
            'purpose' => $this->loan->purpose,
            'amount' => $this->loan->amount,
            'duration_months' => $this->loan->duration_months,
            'message' => 'New loan application from ' . $this->loan->user->name . ' for $' . number_format($this->loan->amount, 2),
        ];
    }
}
