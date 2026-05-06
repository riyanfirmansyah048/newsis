<?php

namespace App\Console\Commands;

use App\Filament\Resources\Bppbs\BppbResource;
use App\Mail\ReminderNotificationMail;
use App\Models\ReminderDate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyReminderNotifications extends Command
{
    protected $signature = 'reminders:send-daily';

    protected $description = 'Send daily reminder notification emails for due reminder dates';

    public function handle(): int
    {
        $dueReminderDates = ReminderDate::query()
            ->with('reminder.item')
            ->whereDate('reminder_date', today())
            ->where('is_sent', false)
            ->get();

        if ($dueReminderDates->isEmpty()) {
            $this->info('No due reminders for today.');

            return self::SUCCESS;
        }

        foreach ($dueReminderDates as $reminderDate) {
            $reminder = $reminderDate->reminder;

            if (! $reminder || blank($reminder->email)) {
                continue;
            }

            $bppbUrl = url(BppbResource::getUrl('create', [
                'item_name' => $reminder->item?->name,
            ]));

            Mail::to($reminder->email)->send(
                new ReminderNotificationMail($reminder, $reminderDate, $bppbUrl)
            );

            $reminderDate->update([
                'is_sent' => true,
                'sent_at' => now(),
            ]);
        }

        $this->info('Reminder emails sent successfully.');

        return self::SUCCESS;
    }
}
