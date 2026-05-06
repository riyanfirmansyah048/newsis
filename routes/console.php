<?php

use App\Console\Commands\SendDailyReminderNotifications;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(SendDailyReminderNotifications::class)
    ->dailyAt('07:00')
    ->timezone(config('app.timezone'));
