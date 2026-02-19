<?php

namespace Database\Seeders;

use App\Models\Purchase_order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Purchase_orderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                po.idPo AS id,
                po.noPo,
                po.idVendor AS vendor_id,
                po.idBppb AS bppb_id,
                po.idKaryawan AS user_id,
                po.tanggalPo AS datePo
            FROM po
        ");
        // LIMIT 30

        foreach ($results as $row) {
            Purchase_order::create([
                'id' => $row->id,
                'noPo' => $row->noPo,
                'vendor_id' => $row->vendor_id,
                'bppb_id' => $row->bppb_id,
                'user_id' => $row->user_id,
                'datePo' => ($row->datePo != '0000-00-00' && $row->datePo != null) ? $row->datePo . ' 00:00:00' : null,
            ]);
        }
    }
}
