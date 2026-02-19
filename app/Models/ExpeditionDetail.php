<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpeditionDetail extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    protected $guarded = [];
    protected $table = 'expedition_details';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function expedition()
    {
        return $this->belongsTo(Expedition::class, 'expedition_id');
    }

    public function productForm()
    {
        return $this->belongsTo(Product_form::class, 'product_form_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(Purchase_order::class, 'po_id');
    }
}
