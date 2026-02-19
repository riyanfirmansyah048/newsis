<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mutation_item extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'mutation_items';
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function asset()
    {
        return $this->belongsTo(Assets_item::class, 'item_id');
    }
}
