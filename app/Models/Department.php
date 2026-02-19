<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'departments';

    protected $fillable = [
        'id',
        'idBusinessUnit',
        'departmentName',
        'code',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function businessunit()
    {
        return $this->belongsTo(BusinessUnit::class, 'idBusinessUnit');
    }

    public function subDepartments()
    {
        return $this->hasMany(SubDepartment::class, 'idDepartment');
    }
}
