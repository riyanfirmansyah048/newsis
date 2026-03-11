<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bppb_software extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    protected $table = 'bppb_software';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function bppb()
    {
        return $this->belongsTo(Bppb::class, 'bppb_id');
    }

    public function software()
    {
        return $this->belongsTo(Software::class, 'software_id');
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

    public function user()
    {
        return $this->belongsTo(User::class, 'pemohonIT');
    }

    protected static function boot()
    {
        parent::boot();

        if (!app()->runningInConsole()) {
            static::saved(function ($software) {
                if (!$software->bppb_id) {
                    $software->update(['bppb_id' => $software->bppb->id]);
                }

                // Duplikasi jika qty lebih dari 1
                if ($software->qty > 1) {
                    for ($i = 1; $i < $software->qty; $i++) {
                        $software->bppb->bppb_software()->create([
                            'bppb_id'    => $software->bppb->id,
                            'software_id' => $software->software_id, // Pastikan ini sesuai dengan kolom di database
                            'qty'        => 1,
                            'description' => $software->description,
                        ]);
                    }
                    $software->update(['qty' => 1]);
                }
            });

            static::updating(function ($software) {
                if ($software->qty > 1) {
                    for ($i = 1; $i < $software->qty; $i++) {
                        $software->bppb->bppb_software()->create([
                            'bppb_id'    => $software->bppb->id,
                            'software_id' => $software->software_id, // Pastikan ini sesuai dengan kolom di database
                            'qty'        => 1,
                            'description' => $software->description,
                        ]);
                    }
                    $software->update(['qty' => 1]);
                }
            });
        }
    }
}
