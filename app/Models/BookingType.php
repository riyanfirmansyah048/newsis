<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BookingType extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function units()
    {
        return $this->hasMany(BookingUnit::class, 'booking_type_id');
    }

    public function orders()
    {
        return $this->hasMany(BookingOrder::class, 'booking_type_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
