<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BookingOrder extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    protected $table = 'booking_orders';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'validated_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookingType()
    {
        return $this->belongsTo(BookingType::class, 'booking_type_id');
    }

    public function assignedUnit()
    {
        return $this->belongsTo(BookingUnit::class, 'assigned_unit_id');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function scopeActiveRequest($query)
    {
        return $query->whereIn('status', ['pending', 'approved']);
    }

    public function scopeSameDate($query, string $date, ?int $ignoreId = null)
    {
        return $query
            ->whereDate('date', $date)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId));
    }
}
