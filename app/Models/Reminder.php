<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Reminder extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'expire_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function reminderDates()
    {
        return $this->hasMany(ReminderDate::class, 'reminder_id');
    }
}
