<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'expire_date' => 'date',
    ];

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
