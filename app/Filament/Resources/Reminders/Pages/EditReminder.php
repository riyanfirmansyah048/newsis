<?php

namespace App\Filament\Resources\Reminders\Pages;

use App\Filament\Resources\Reminders\ReminderResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditReminder extends EditRecord
{
    protected static string $resource = ReminderResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['email'] = filled(trim((string) ($data['email'] ?? null)))
            ? trim((string) $data['email'])
            : \App\Models\Reminder::DEFAULT_TO_EMAIL;
        $data['cc'] = $this->normalizeCc($data['cc'] ?? null);

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

    protected function normalizeCc(?string $value): ?string
    {
        $emails = collect(explode(',', (string) $value))
            ->map(fn (string $email) => trim($email))
            ->filter()
            ->unique()
            ->values();

        $invalidEmails = $emails
            ->filter(fn (string $email) => ! filter_var($email, FILTER_VALIDATE_EMAIL))
            ->values();

        if ($invalidEmails->isNotEmpty()) {
            Notification::make()
                ->title('CC email tidak valid')
                ->body('Pastikan semua email CC dipisah dengan koma dan menggunakan format email yang benar.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'cc' => 'Terdapat email CC yang tidak valid: ' . $invalidEmails->implode(', '),
            ]);
        }

        return $emails->isNotEmpty() ? $emails->implode(', ') : null;
    }
}
