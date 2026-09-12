<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Interview;


class InterviewReminderNotification extends Notification
{
    use Queueable;

    protected $interview;

    // Receive the interview details.
    public function __construct(Interview $interview)
    {
        $this->interview = $interview;
    }

    // Send the reminder through email.
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    // Build the interview reminder email.
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Interview Reminder')
            ->line('This is a reminder for your upcoming interview.')
            ->line('Interview Date: ' . $this->interview->scheduled_at)
            ->line('Meeting Link: ' . ($this->interview->meeting_link ?? 'Not provided'))
            ->line('Please be available at the scheduled time.');
    }
}
