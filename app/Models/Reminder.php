<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Reminder extends Model
{
    use HasFactory, LogsActivity;

    public const DEFAULT_TO_EMAIL = 'it-admin@gmail.com';

    protected $guarded = [];

    protected $casts = [
        'expire_date' => 'date',
    ];

    public function getCcRecipientsAttribute(): array
    {
        return collect(explode(',', (string) $this->cc))
            ->map(fn (string $email) => trim($email))
            ->filter(fn (string $email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();
    }

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
