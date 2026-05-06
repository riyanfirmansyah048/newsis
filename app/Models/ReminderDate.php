<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderDate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'reminder_date' => 'date',
        'is_sent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function reminder()
    {
        return $this->belongsTo(Reminder::class, 'reminder_id');
    }
}
