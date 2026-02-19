<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Regional extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'regionals';

    protected $fillable = [
        'id',
        'idCompany',
        'regionalName',
        'code',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'idCompany');
    }

    public function businessUnits()
    {
        return $this->hasMany(BusinessUnit::class, 'idRegional');
    }
}
