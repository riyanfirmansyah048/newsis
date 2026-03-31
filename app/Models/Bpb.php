<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bpb extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [];

    protected $table = 'bpbs';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function purchase_order()
    {
        return $this->belongsTo(Purchase_order::class, 'po_id');
    }

    public function asset_items()
    {
        return $this->hasMany(Assets_item::class, 'bpb_id');
    }

    public function asset_inks()
    {
        return $this->hasMany(Assets_ink::class, 'bpb_id');
    }

    public function asset_softwares()
    {
        return $this->hasMany(Assets_software::class, 'bpb_id');
    }

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            static::creating(function ($model) {

                $last = self::withTrashed()
                    ->whereYear('dateBpb', now()->year)
                    ->whereMonth('dateBpb', now()->month)
                    ->max('number');

                $number = ($last ?? 0) + 1;

                $model->number = $number;

                $month = now()->format('m');
                $year  = now()->format('y');

                $model->noBpb = str_pad($number, 3, '0', STR_PAD_LEFT) . "/IT-SANBE/$month/$year";
            });

            static::created(function ($bpb) {
                // Ambil po_id dari URL
                $purchaseOrderId = request('po_id');
                if ($purchaseOrderId) {
                    // Cari Purchase_order berdasarkan ID
                    $purchaseOrder = Purchase_order::find($purchaseOrderId);

                    // Jika ditemukan, update kolom bpb_id
                    if ($purchaseOrder) {
                        $purchaseOrder->update(['bpb_id' => $bpb->id]);
                    }
                }
                //start untuk insert assets setelah bpb dibuat-------------------------
                $bppbId = optional($bpb->purchase_order)->bppb_id;
                $poId = $bpb->po_id;

                $items = Bppb_item::with(['bppb', 'item'])
                    ->where('purchase_order_id', $poId)
                    ->where('bppb_id', $bppbId)
                    ->whereHas('item', function ($q) {
                        $q->where('type_id', 2); //2 artinya assets
                    })
                    ->get();


                $inks = Bppb_ink::with(['bppb', 'ink'])
                    ->where('purchase_order_id', $poId)
                    ->where('bppb_id', $bppbId)
                    ->whereHas('ink', function ($q) {
                        $q->where('type_id', 2); //2 artinya assets
                    })
                    ->get();

                $softwares = Bppb_software::with(['bppb', 'software'])
                    ->where('purchase_order_id', $poId)
                    ->where('bppb_id', $bppbId)
                    ->whereHas('software', function ($q) {
                        $q->where('type_id', 2); //2 artinya assets
                    })
                    ->get();

                // masukan data ke tabel
                if ($items->isNotEmpty()) {
                    $no = 0;
                    foreach ($items as $item) {
                        $no++;
                        $random = strtoupper(Str::random(8)); // random 8 karakter
                        Assets_item::create([
                            'number' => $no,
                            'noAssetItem' => $bpb->user?->company?->code . '/' . $no . '/' . 'ITM' . '/' . $bpb->noBpb . '/' . $random,
                            'numberOwner' => 1,

                            'user_id' => $bpb->user_id,
                            'item_id' => $item->item_id,
                            'bpb_id' => $bpb->id,
                            'bppb_item_id' => $item->id,
                            'idCompany' => $bpb->user?->company?->id,
                            'idRegional' => $bpb->user?->regional?->id,
                            'idBusinessUnit' => $bpb->user?->businessunit?->id,
                            'idDepartment' => $bpb->user?->department?->id,
                            'idPosition' => $bpb->user?->position?->id,
                        ]);
                    }
                }
                if ($inks->isNotEmpty()) {
                    $no = 0;
                    foreach ($inks as $ink) {
                        $no++;
                        Assets_ink::create([
                            'number' => $no,
                            'noAssetInk' => $bpb->user?->company?->code . '/' . $no . '/' . 'INK' . '/' . $bpb->noBpb,

                            'numberOwner' => 1,

                            'user_id' => $bpb->user_id,
                            'ink_id' => $ink->ink_id,
                            'bpb_id' => $bpb->id,
                            'bppb_ink_id' => $ink->id,
                            'idCompany' => $bpb->user?->company?->id,
                            'idRegional' => $bpb->user?->regional?->id,
                            'idBusinessUnit' => $bpb->user?->businessunit?->id,
                            'idDepartment' => $bpb->user?->department?->id,
                            'idPosition' => $bpb->user?->position?->id,
                        ]);
                    }
                }
                if ($softwares->isNotEmpty()) {
                    $no = 0;
                    foreach ($softwares as $software) {
                        $no++;
                        Assets_software::create([
                            'number' => $no,
                            'noAssetSoftware' => $bpb->user?->company?->code . '/' . $no . '/' . 'SWR' . '/' . $bpb->noBpb,
                            'numberOwner' => 1,

                            'user_id' => $bpb->user_id,
                            'software_id' => $software->software_id,
                            'bpb_id' => $bpb->id,
                            'bppb_software_id' => $software->id,
                            'idCompany' => $bpb->user?->company?->id,
                            'idRegional' => $bpb->user?->regional?->id,
                            'idBusinessUnit' => $bpb->user?->businessunit?->id,
                            'idDepartment' => $bpb->user?->department?->id,
                            'idPosition' => $bpb->user?->position?->id,
                        ]);
                    }
                }
                //end untuk insert assets setelah bpb dibuat-------------------------
            });

            static::deleting(function ($bpb) {

                if ($bpb->isForceDeleting()) {
                    // Hapus permanen
                    $bpb->asset_items()->forceDelete();
                    $bpb->asset_inks()->forceDelete();
                    $bpb->asset_softwares()->forceDelete();
                } else {
                    // Soft delete
                    $bpb->asset_items()->delete();
                    $bpb->asset_inks()->delete();
                    $bpb->asset_softwares()->delete();
                }
            });

            static::restoring(function ($bpb) {
                $bpb->asset_items()->withTrashed()->restore();
                $bpb->asset_inks()->withTrashed()->restore();
                $bpb->asset_softwares()->withTrashed()->restore();
            });
        }
    }
}
