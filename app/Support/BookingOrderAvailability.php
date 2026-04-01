<?php

namespace App\Support;

use App\Models\BookingOrder;
use App\Models\BookingUnit;

class BookingOrderAvailability
{
    public static function activeQuota(?int $bookingTypeId = null): int
    {
        return BookingUnit::query()
            ->active()
            ->when($bookingTypeId, fn ($query) => $query->where('booking_type_id', $bookingTypeId))
            ->count();
    }

    public static function bookingCount(int $bookingTypeId, string $date, ?int $ignoreId = null): int
    {
        return BookingOrder::query()
            ->activeRequest()
            ->where('booking_type_id', $bookingTypeId)
            ->sameDate($date, $ignoreId)
            ->count();
    }

    public static function hasQuota(int $bookingTypeId, string $date, ?int $ignoreId = null): bool
    {
        return static::bookingCount($bookingTypeId, $date, $ignoreId) < static::activeQuota($bookingTypeId);
    }

    public static function message(?int $bookingTypeId, ?string $date, ?int $ignoreId = null): string
    {
        if (! $bookingTypeId) {
            return 'Pilih jenis booking terlebih dahulu.';
        }

        if (! $date) {
            return 'Pilih tanggal terlebih dahulu.';
        }

        $quota = static::activeQuota($bookingTypeId);
        $used = static::bookingCount($bookingTypeId, $date, $ignoreId);

        if ($quota <= 0) {
            return 'Belum ada unit booking aktif untuk jenis ini.';
        }

        if ($used >= $quota) {
            return "Slot hari ini penuh. Kuota aktif {$quota}, booking hari ini {$used}.";
        }

        return "Slot tersedia. Kuota aktif {$quota}, booking hari ini {$used}.";
    }

    public static function findAvailableUnitId(int $bookingTypeId, string $date, ?int $ignoreBookingId = null): ?int
    {
        $units = BookingUnit::query()
            ->active()
            ->where('booking_type_id', $bookingTypeId)
            ->get();

        foreach ($units as $unit) {
            $hasConflict = BookingOrder::query()
                ->where('assigned_unit_id', $unit->id)
                ->where('status', 'approved')
                ->sameDate($date, $ignoreBookingId)
                ->exists();

            if (! $hasConflict) {
                return $unit->id;
            }
        }

        return null;
    }

    public static function timeOptions(): array
    {
        $times = [];

        for ($hour = 7; $hour <= 21; $hour++) {
            foreach ([0, 30] as $minute) {
                $value = sprintf('%02d:%02d:00', $hour, $minute);
                $times[$value] = substr($value, 0, 5);
            }
        }

        return $times;
    }

    public static function endTimeOptions(?string $startTime): array
    {
        if (! $startTime) {
            return [];
        }

        return collect(static::timeOptions())
            ->filter(fn ($_label, $value) => $value > $startTime)
            ->all();
    }
}
