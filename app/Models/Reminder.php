<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Reminder extends Model
{
    use HasFactory, LogsActivity;

    public const DEFAULT_TO_EMAIL = 'it-admin@sanbe-farma.com';

    protected $guarded = [];

    protected $casts = [
        'expire_date' => 'date',
    ];

    public function getCcRecipientsAttribute(): array
    {
        return collect(explode(',', (string) $this->cc))
            ->map(fn(string $email) => trim($email))
            ->filter(fn(string $email) => filter_var($email, FILTER_VALIDATE_EMAIL))
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

    public function software()
    {
        return $this->belongsTo(Software::class, 'software_id');
    }

    public function getTargetTypeAttribute(): string
    {
        return filled($this->software_id) ? 'software' : 'item';
    }

    public function getTargetNameAttribute(): string
    {
        return $this->item?->name
            ?? $this->software?->name
            ?? '-';
    }

    public function reminderDates()
    {
        return $this->hasMany(ReminderDate::class, 'reminder_id');
    }
}
