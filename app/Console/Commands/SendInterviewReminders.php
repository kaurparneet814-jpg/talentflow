<?php

namespace App\Console\Commands;
use App\Models\Interview;
use App\Notifications\InterviewReminderNotification;
use Illuminate\Console\Command;

class SendInterviewReminders extends Command
{
    // Command used to send reminders for upcoming interviews.
    protected $signature = 'interviews:send-reminders';

    // Description shown in the Artisan command list.
    protected $description = 'Send reminders for interviews scheduled within the next 24 hours';

    public function handle()
    {
        // Find scheduled interviews occurring around 24 hours from now.
        $interviews = Interview::with([
            'application.candidate.user',
            'interviewer'
        ])
            ->where('status', 'scheduled')
            ->whereBetween('scheduled_at', [
                now()->addHours(23),
                now()->addHours(24),
            ])
            ->get();

        foreach ($interviews as $interview) {
            // Send reminder to the candidate.
            $candidateUser = $interview->application?->candidate?->user;

            if ($candidateUser) {
                $candidateUser->notify(
                    new InterviewReminderNotification($interview)
                );
            }

            // Send reminder to the interviewer.
            if ($interview->interviewer) {
                $interview->interviewer->notify(
                    new InterviewReminderNotification($interview)
                );
            }
        }

        $this->info(
            $interviews->count() . ' interview reminder(s) processed.'
        );

        return Command::SUCCESS;
    }
}
