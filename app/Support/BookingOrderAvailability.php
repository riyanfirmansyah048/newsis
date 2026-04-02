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

    public static function availableUnitsOptions(?int $bookingTypeId, ?string $date, ?int $ignoreBookingId = null, ?int $selectedUnitId = null): array
    {
        if (! $bookingTypeId || ! $date) {
            return [];
        }

        $reservedUnitIds = BookingOrder::query()
            ->activeRequest()
            ->where('booking_type_id', $bookingTypeId)
            ->sameDate($date, $ignoreBookingId)
            ->whereNotNull('assigned_unit_id')
            ->pluck('assigned_unit_id')
            ->all();

        return BookingUnit::query()
            ->active()
            ->where('booking_type_id', $bookingTypeId)
            ->when(! empty($reservedUnitIds), function ($query) use ($reservedUnitIds, $selectedUnitId) {
                $query->where(function ($inner) use ($reservedUnitIds, $selectedUnitId) {
                    $inner->whereNotIn('id', $reservedUnitIds);

                    if ($selectedUnitId) {
                        $inner->orWhere('id', $selectedUnitId);
                    }
                });
            })
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public static function unitAvailable(int $bookingTypeId, string $date, int $unitId, ?int $ignoreBookingId = null): bool
    {
        return array_key_exists(
            $unitId,
            static::availableUnitsOptions($bookingTypeId, $date, $ignoreBookingId, $unitId),
        );
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
        $availableUnits = count(static::availableUnitsOptions($bookingTypeId, $date, $ignoreId));

        if ($quota <= 0) {
            return 'Belum ada unit booking aktif untuk jenis ini.';
        }

        if ($used >= $quota || $availableUnits <= 0) {
            return "Slot hari ini penuh. Kuota aktif {$quota}, booking hari ini {$used}.";
        }

        return "Slot tersedia. Kuota aktif {$quota}, booking hari ini {$used}, unit tersedia {$availableUnits}.";
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
