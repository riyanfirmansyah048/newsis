<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Internet extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'idUser',
        'description',
        'url',
        'ip',
        'activeStatus'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }
}
