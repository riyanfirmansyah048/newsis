<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mutation extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'mutations';
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function userFrom()
    {
        return $this->belongsTo(User::class, 'user_id_from');
    }

    public function userTo()
    {
        return $this->belongsTo(User::class, 'user_id_to');
    }
    public function items()
    {
        return $this->hasMany(Mutation_item::class);
    }
}
