<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assets_ink extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    protected $table = 'assets_inks';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function ink()
    {
        return $this->belongsTo(Ink::class, 'ink_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bpb()
    {
        return $this->belongsTo(Bpb::class, 'bpb_id');
    }

    public function bppb_ink()
    {
        return $this->belongsTo(Bppb_ink::class, 'bppb_ink_id');
    }
}
