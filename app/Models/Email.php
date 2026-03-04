<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Email extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'id',
        'idDomainEmail',
        'idUser',
        'idCompany',
        'emailName',
        'passwordEmail',
        'activeStatus',
        'activeDate'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function domainEmail()
    {
        return $this->belongsTo(DomainEmail::class, 'idDomainEmail', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'idCompany', 'id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {

            if (empty($model->idUser)) {
                $model->idUser = auth()->id();
            }

            if (empty($model->idCompany)) {
                $model->idCompany = auth()->user()?->idCompany;
            }
        });
    }
}
