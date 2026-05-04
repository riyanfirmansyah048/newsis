<?php

namespace App\Models;

use App\Mail\ServiceCompletedAtItMail;
use Carbon\Carbon;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

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

    /**
     * Service aktif untuk badge PIC: status Barang diterima IT (4) atau Proses Service (5).
     */
    public function scopeActiveForPic(Builder $query, int $userId): Builder
    {
        return $query->where('ic_id', $userId)->whereIn('status_id', [4, 5]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!app()->runningInConsole()) {
                $kodeDepartemen = Auth::user()?->department?->code ?? 'XXX';
                $kodeRegional = Auth::user()?->regional?->code ?? 'XXX';

                $bulan = Carbon::now()->month;

                $tahun = Carbon::now()->format('y');

                // Hitung jumlah BPPB yang sudah dibuat untuk kode departemen, bulan, dan tahun yang sama
                $jumlahBppb = self::whereHas('user', function ($query) use ($kodeDepartemen) {
                    $query->whereHas('department', function ($q) use ($kodeDepartemen) {
                        $q->where('code', $kodeDepartemen);
                    });
                })
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count();

                // Tentukan nomor urut (dimulai dari 1, format 3 digit: 001, 002, ...)
                $nomorUrut = str_pad($jumlahBppb + 1, 3, '0', STR_PAD_LEFT);

                // Gabungkan menjadi format yg diinginkan
                $model->number = $nomorUrut;
                $model->noService = "{$nomorUrut}/{$kodeDepartemen}-{$kodeRegional}/SER/{$bulan}/{$tahun}";

                // Simpan user_id dari user yang sedang login
                // $model->user_id = Auth::id();

                // default status 3 menunggu konfirmasi IT
                $model->status_id = 3;
            }
        });

        static::saving(function (Service $service): void {
            $solutionId = $service->solution_id;
            if (
                $solutionId !== null
                && (int) $solutionId !== 6
                && (int) $service->status_id === 4
            ) {
                $service->status_id = 5;
            }
        });

        static::updated(function (Service $service): void {
            if (! $service->wasChanged('status_id') || (int) $service->status_id !== 6) {
                return;
            }

            $service->loadMissing(['user', 'item', 'status']);

            $email = trim((string) ($service->user?->email ?? ''));
            if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                return;
            }

            Mail::to($email)->send(new ServiceCompletedAtItMail($service));
        });
    }
}
