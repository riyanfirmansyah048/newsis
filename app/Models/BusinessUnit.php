<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessUnit extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'business_units';

    protected $fillable = [
        'id',
        'idRegional',
        'businessUnitName',
        'code',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'idRegional');
    }
    public function departments()
    {
        return $this->hasMany(Department::class, 'idBusinessUnit');
    }
}
