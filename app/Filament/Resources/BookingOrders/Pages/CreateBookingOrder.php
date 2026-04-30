<?php

namespace App\Filament\Resources\BookingOrders\Pages;

use App\Mail\BookingOrderSubmittedMail;
use App\Models\BookingOrder;
use App\Models\BookingUnit;
use App\Support\BookingOrderAvailability;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\BookingOrders\BookingOrderResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class CreateBookingOrder extends CreateRecord
{
    protected static string $resource = BookingOrderResource::class;

    // transaction start
    protected function isUniqueConstraintViolation(QueryException $exception): bool
    {
        $errorInfo = $exception->errorInfo;

        return ($errorInfo[0] ?? null) === '23000' && ($errorInfo[1] ?? null) === 1062;
    }

    protected function throwUnitConflict(): never
    {
        Notification::make()
            ->title('Slot sudah diambil')
            ->body('Unit yang dipilih baru saja dibooking user lain. Silakan pilih unit atau tanggal lain.')
            ->danger()
            ->send();

        throw ValidationException::withMessages([
            'assigned_unit_id' => 'Unit yang dipilih baru saja dibooking user lain. Silakan pilih unit atau tanggal lain.',
        ]);
    }
    // transaction end

    public function mount(): void
    {
        parent::mount();

        if (! BookingOrderResource::hasValidProfileContact()) {
            Notification::make()
                ->title('Profil belum lengkap')
                ->body('Lengkapi data Anda seperti Email, ext, dan Departemen terlebih dahulu sebelum membuat Booking Order.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirectRoute('filament.sis.auth.profile');

            return;
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! BookingOrderResource::hasValidProfileContact()) {
            Notification::make()
                ->title('Profil belum lengkap')
                ->body('Lengkapi data Anda seperti Email, ext, dan Departemen terlebih dahulu sebelum membuat Booking Order.')
                ->danger()
                ->persistent()
                ->send();

            throw ValidationException::withMessages([
                'user_id' => 'Lengkapi data Anda seperti Email, ext, dan Departemen terlebih dahulu sebelum membuat Booking Order.',
            ]);
        }

        if (($data['end_time'] ?? null) <= ($data['start_time'] ?? null)) {
            throw ValidationException::withMessages([
                'end_time' => 'Jam selesai harus lebih besar dari jam mulai.',
            ]);
        }

        if (! BookingOrderAvailability::hasQuota($data['booking_type_id'], $data['date'])) {
            Notification::make()
                ->title('Tanggal sudah penuh')
                ->body('Kuota booking pada tanggal tersebut sudah habis. Silakan pilih tanggal lain.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'date' => 'Kuota booking di tanggal tersebut sudah penuh.',
            ]);
        }

        if (blank($data['assigned_unit_id'] ?? null)) {
            Notification::make()
                ->title('Unit belum dipilih')
                ->body('Pilih salah satu unit yang tersedia untuk tanggal tersebut.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'assigned_unit_id' => 'Pilih salah satu unit yang tersedia untuk tanggal tersebut.',
            ]);
        }

        if (! BookingOrderAvailability::unitAvailable(
            $data['booking_type_id'],
            $data['date'],
            (int) $data['assigned_unit_id'],
        )) {
            Notification::make()
                ->title('Unit tidak tersedia')
                ->body('Unit yang dipilih sudah tidak tersedia untuk tanggal tersebut. Silakan pilih unit lain.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'assigned_unit_id' => 'Unit yang dipilih sudah tidak tersedia untuk tanggal tersebut.',
            ]);
        }

        $data['user_id'] = auth()->id();

        if (! auth()->user()->can('update-booking-order')) {
            $data['status'] = 'pending';
        }

        return $data;
    }

    // transaction start
    protected function handleRecordCreation(array $data): Model
    {
        try {
            return DB::transaction(function () use ($data) {
                BookingUnit::query()
                    ->whereKey($data['assigned_unit_id'])
                    ->lockForUpdate()
                    ->first();

                if (! BookingOrderAvailability::unitAvailable(
                    $data['booking_type_id'],
                    $data['date'],
                    (int) $data['assigned_unit_id'],
                )) {
                    $this->throwUnitConflict();
                }

                return BookingOrder::query()->create($data);
            });
        } catch (QueryException $exception) {
            if ($this->isUniqueConstraintViolation($exception)) {
                $this->throwUnitConflict();
            }

            throw $exception;
        }
    }
    // transaction end

    protected function afterCreate(): void
    {
        $this->record->loadMissing(['user.department', 'bookingType', 'assignedUnit']);

        $recipients = collect([
            $this->record->bookingType?->notification_email,
        ])
            ->filter()
            ->unique()
            ->values()
            ->all();

        $ccRecipients = collect(explode(',', (string) $this->record->bookingType?->notification_cc))
            ->map(fn($email) => trim($email))
            ->filter(fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();

        if (empty($recipients) && empty($ccRecipients)) {
            return;
        }

        $mail = new BookingOrderSubmittedMail($this->record);
        $mail->replyTo($this->record->user?->email, $this->record->user?->name ?? 'User');

        Mail::to($recipients)
            ->cc($ccRecipients)
            ->send($mail);
    }
}
