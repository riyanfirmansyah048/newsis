<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Components\Section;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubDepartment extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'sub_departments';

    protected $fillable = [
        'id',
        'idDepartment',
        'subDepartmentName',
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

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'idRegional');
    }

    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class, 'idBusinessUnit');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'idDepartment');
    }

    public function section()
    {
        return $this->hasMany(Section::class, 'idSubDepartment');
    }
}
