<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expedition extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    protected $guarded = [];
    protected $table = 'expeditions';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function bppb()
    {
        return $this->belongsTo(Bppb::class, 'bppb_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function expeditionDetails()
    {
        return $this->hasMany(ExpeditionDetail::class, 'expedition_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(Purchase_order::class, 'po_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!app()->runningInConsole()) {
                $user = $model->bppb?->user;

                $kodeRegional = $user?->regional?->code ?? 'XXX';
                $kodeDepartemen = $user?->department?->code ?? 'XXX';

                $tahun = now()->format('Y');

                // Hitung jumlah ekspedisi tahun ini
                $jumlah = self::whereYear('created_at', now()->year)->count();

                // $nomorUrut = str_pad($jumlah + 1, 3, '0', STR_PAD_LEFT);
                $nomorUrut = $jumlah + 1;

                $model->number = $nomorUrut;
                $model->noExpedition = "{$nomorUrut}/{$kodeRegional}-{$kodeDepartemen}/{$tahun}";
                $model->dateInput = now()->toDateTimeString();
                $model->user_id = Auth::id();
            }
        });

        static::created(function ($model) {
            if (!app()->runningInConsole() && $model->bppb) {
                $model->bppb->update([
                    'status_id' => 7,
                ]);
            }
        });
    }

    protected static function booted()
    {
        static::deleting(function ($expedition) {
            if ($expedition->isForceDeleting()) {
                // hapus permanen
                $expedition->expeditionDetails()->forceDelete();
            } else {
                // soft delete
                $expedition->expeditionDetails()->delete();
            }
        });

        static::restoring(function ($expedition) {
            // Saat di-restore, kembalikan juga expedition_details yang di-soft delete
            $expedition->expeditionDetails()->withTrashed()->restore();
        });
    }
}
