<?php

namespace App\Filament\Resources\Reminders\Pages;

use App\Filament\Resources\Reminders\ReminderResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateReminder extends CreateRecord
{
    protected static string $resource = ReminderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        $this->validateReminderDates($data);

        return $data;
    }

    protected function validateReminderDates(array $data): void
    {
        $expireDate = $data['expire_date'] ?? null;

        foreach (($data['reminderDates'] ?? []) as $index => $reminderDate) {
            if (($reminderDate['reminder_date'] ?? null) > $expireDate) {
                Notification::make()
                    ->title('Tanggal reminder tidak valid')
                    ->body('Tanggal reminder tidak boleh melewati tanggal expired.')
                    ->danger()
                    ->send();

                throw ValidationException::withMessages([
                    "reminderDates.{$index}.reminder_date" => 'Tanggal reminder tidak boleh melewati tanggal expired.',
                ]);
            }
        }
    }
}
