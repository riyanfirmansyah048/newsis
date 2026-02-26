<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase_order extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    protected $table = 'purchase_orders';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function bppb_items()
    {
        return $this->hasMany(Bppb_item::class, 'purchase_order_id');
    }

    public function bppb_inks()
    {
        return $this->hasMany(Bppb_ink::class, 'purchase_order_id');
    }

    public function bppb_softwares()
    {
        return $this->hasMany(Bppb_software::class, 'purchase_order_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function bppb()
    {
        return $this->belongsTo(Bppb::class, 'bppb_id');
    }

    public function bpb()
    {
        return $this->hasMany(Bpb::class, 'po_id');
    }
}
