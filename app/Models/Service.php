<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    protected $table = 'services';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function icUser()
    {
        return $this->belongsTo(User::class, 'ic_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function status()
    {
        return $this->belongsTo(Service_status::class, 'status_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function serviceType()
    {
        return $this->belongsTo(Service_type::class, 'type_service_id');
    }

    public function bppbs()
    {
        return $this->hasMany(Bppb::class, 'service_id');
    }

    public function serviceSolution()
    {
        return $this->belongsTo(Service_solusi::class, 'solution_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!app()->runningInConsole()) {
                $kodeSubDepartemen = Auth::user()?->subdepartment?->code ?? 'XXX';
                $kodeRegional = Auth::user()?->regional?->code ?? 'XXX';

                $bulan = Carbon::now()->month;

                $tahun = Carbon::now()->format('y');

                // Hitung jumlah BPPB yang sudah dibuat untuk kode departemen, bulan, dan tahun yang sama
                $jumlahBppb = self::whereHas('user', function ($query) use ($kodeSubDepartemen) {
                    $query->whereHas('subdepartment', function ($q) use ($kodeSubDepartemen) {
                        $q->where('code', $kodeSubDepartemen);
                    });
                })
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count();

                // Tentukan nomor urut (dimulai dari 1, format 3 digit: 001, 002, ...)
                $nomorUrut = str_pad($jumlahBppb + 1, 3, '0', STR_PAD_LEFT);

                // Gabungkan menjadi format yg diinginkan
                $model->number = $nomorUrut;
                $model->noService = "{$nomorUrut}/{$kodeSubDepartemen}{$kodeRegional}/SER/{$bulan}/{$tahun}";

                // Simpan user_id dari user yang sedang login
                // $model->user_id = Auth::id();

                // default status 3 menunggu konfirmasi IT
                $model->status_id = 3;
            }
        });
    }
}
