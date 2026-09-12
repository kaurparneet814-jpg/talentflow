<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Check for overdue technical tasks every hour.
Schedule::command('tasks:mark-overdue')->hourly();
// Send interview reminders every hour.
Schedule::command('interviews:send-reminders')->hourly();