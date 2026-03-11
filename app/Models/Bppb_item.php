<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bppb_item extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    protected $table = 'bppb_items';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function bppb()
    {
        return $this->belongsTo(Bppb::class, 'bppb_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function Purchase_order()
    {
        return $this->belongsTo(Purchase_order::class, 'purchase_order_id');
    }

    protected static function boot()
    {
        parent::boot();

        if (!app()->runningInConsole()) {
            static::saved(function ($item) {
                if (!$item->bppb_id) {
                    $item->update(['bppb_id' => $item->bppb->id]);
                }

                // Duplikasi jika qty lebih dari 1
                if ($item->qty > 1) {
                    for ($i = 1; $i < $item->qty; $i++) {
                        $item->bppb->bppb_item()->create([
                            'bppb_id'    => $item->bppb->id,
                            'item_id'    => $item->item_id,
                            'qty'        => 1,
                            'description' => $item->description,
                        ]);
                    }
                    $item->update(['qty' => 1]);
                }
            });

            static::updating(function ($item) {
                if ($item->qty > 1) {
                    for ($i = 1; $i < $item->qty; $i++) {
                        $item->bppb->bppb_item()->create([
                            'bppb_id'    => $item->bppb->id,
                            'item_id'    => $item->item_id,
                            'qty'        => 1,
                            'description' => $item->description,
                        ]);
                    }
                    $item->update(['qty' => 1]);
                }
            });
        }
    }
}
