<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DomainEmail extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'id',
        'idCompany',
        'domainName',
        'titleName',
        'imap',
        'pop3',
        'smtp',
        'description',
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
}
