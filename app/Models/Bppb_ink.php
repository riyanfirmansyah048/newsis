<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bppb_ink extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    protected $table = 'bppb_inks';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function bppb()
    {
        return $this->belongsTo(Bppb::class, 'bppb_id');
    }

    public function ink()
    {
        return $this->belongsTo(Ink::class, 'ink_id');
    }

    public function Purchase_order()
    {
        return $this->belongsTo(Purchase_order::class, 'purchase_order_id');
    }

    protected static function boot()
    {
        parent::boot();

        if (!app()->runningInConsole()) {
            static::saved(function ($ink) {
                if (!$ink->bppb_id) {
                    $ink->update(['bppb_id' => $ink->bppb->id]);
                }

                // Duplikasi jika qty lebih dari 1
                if ($ink->qty > 1) {
                    for ($i = 1; $i < $ink->qty; $i++) {
                        $ink->bppb->bppb_ink()->create([
                            'bppb_id'    => $ink->bppb->id,
                            'ink_id'     => $ink->ink_id, // Pastikan ini sesuai dengan kolom di database
                            'qty'        => 1,
                            'description' => $ink->description,
                        ]);
                    }
                    $ink->update(['qty' => 1]);
                }
            });

            static::updating(function ($ink) {
                if ($ink->qty > 1) {
                    for ($i = 1; $i < $ink->qty; $i++) {
                        $ink->bppb->bppb_ink()->create([
                            'bppb_id'    => $ink->bppb->id,
                            'ink_id'     => $ink->ink_id, // Pastikan ini sesuai dengan kolom di database
                            'qty'        => 1,
                            'description' => $ink->description,
                        ]);
                    }
                    $ink->update(['qty' => 1]);
                }
            });
        }
    }
}
