<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bppb extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    protected $table = 'bppbs';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function bppb_item()
    {
        return $this->hasMany(Bppb_item::class, 'bppb_id');
    }

    public function bppb_ink()
    {
        return $this->hasMany(Bppb_ink::class, 'bppb_id');
    }

    public function bppb_software()
    {
        return $this->hasMany(Bppb_software::class, 'bppb_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status()
    {
        return $this->belongsTo(Bppb_status::class, 'status_id');
    }

    public function bppb_type()
    {
        return $this->belongsTo(Bppb_type::class, 'bppb_type_id');
    }

    protected static function boot()
    {
        parent::boot();

        // static::creating(function ($model) {
        //     if (!app()->runningInConsole()) {
        //         $kodeDepartemen = Auth::user()?->department?->code ?? 'XXX'; // Default jika tidak ada kode

        //         $bulanRomawi = self::convertToRoman(Carbon::now()->month);

        //         $tahun = Carbon::now()->format('y');

        //         $userId = $model->user_id;

        //         $jumlahBppb = self::whereHas('user', function ($query) use ($userId) {
        //             $query->where('id', $userId)
        //                 ->whereHas('department');
        //         })
        //             ->whereMonth('created_at', Carbon::now()->month)
        //             ->whereYear('created_at', Carbon::now()->year)
        //             ->count();

        //         $nomorUrut = str_pad($jumlahBppb + 1, 3, '0', STR_PAD_LEFT);

        //         $model->number = $nomorUrut;
        //         if ($model->bppb_type_id != 2) {
        //             $model->noBppb = "{$nomorUrut}/{$kodeDepartemen}/{$bulanRomawi}/{$tahun}";
        //         }

        //         $model->status_id = 3;
        //     }
        // });

        static::creating(function ($model) {
            if (!app()->runningInConsole()) {
                $model->print_count ??= 0;

                if (!empty($model->noBppb)) {
                    $model->status_id = 3;
                } else {
                    $user = Auth::user();
                    $departmentId = $user?->idDepartment;

                    $bulan = Carbon::now()->month;
                    $tahun = Carbon::now()->year;

                    $jumlahBppb = self::withTrashed()
                        ->whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun)
                        ->whereHas('user', function ($query) use ($departmentId) {
                            $query->where('idDepartment', $departmentId);
                        })
                        ->count();

                    $nomorUrut = str_pad($jumlahBppb + 1, 3, '0', STR_PAD_LEFT);

                    $model->number = $nomorUrut;

                    $kodeDepartemen = $user?->department?->code ?? 'XXX';
                    $bulanRomawi = self::convertToRoman($bulan);
                    $tahunShort = Carbon::now()->format('y');

                    $model->noBppb = "{$nomorUrut}/{$kodeDepartemen}/{$bulanRomawi}/{$tahunShort}";
                    $model->status_id = 3;
                }
            }
        });
    }

    // Fungsi untuk mengonversi angka ke Romawi
    private static function convertToRoman($num)
    {
        $map = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        return $map[$num] ?? $num; // Jika bulan tidak ditemukan, kembalikan angka biasa
    }

    public function purchase_orders()
    {
        return $this->hasMany(Purchase_order::class, 'bppb_id');
    }

    public function isSoftwareConsolidation(): bool
    {
        return $this->bppb_software()
            ->whereNotNull('noBppbPemohon')
            ->where('noBppbPemohon', '!=', '')
            ->exists();
    }

    public function getFlowLabelAttribute(): string
    {
        return $this->isSoftwareConsolidation()
            ? 'BPPB Konsolidasi Software'
            : 'BPPB Biasa';
    }

    protected static function booted()
    {
        static::deleting(function ($bppb) {
            if ($bppb->isForceDeleting()) {
                // Hapus permanen
                $bppb->bppb_item()->forceDelete();
                $bppb->bppb_ink()->forceDelete();
                $bppb->bppb_software()->forceDelete();
            } else {
                // Soft delete
                $bppb->bppb_item()->delete();
                $bppb->bppb_ink()->delete();
                $bppb->bppb_software()->delete();
            }
        });

        static::restoring(function ($bppb) {
            // Saat di-restore, kembalikan juga bppb_item, bppb_ink, dan bppb_software yang di-soft delete
            $bppb->bppb_item()->withTrashed()->restore();
            $bppb->bppb_ink()->withTrashed()->restore();
            $bppb->bppb_software()->withTrashed()->restore();
        });
    }
}
