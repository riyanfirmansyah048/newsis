<?php

namespace Database\Seeders;

use App\Models\Bppb;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BppbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                bppb.idBppb AS id,
                bppb.noBppb,
                bppb.idKaryawan AS user_id,
                bppb.idService AS service_id,
                bppb.urut AS number,
                CASE 
                    WHEN bppb.complete = 1 THEN 6
                    WHEN bppb.approve = 1 THEN 4
                    WHEN bppb.reject = 1 THEN 2
                    ELSE 3
                END AS status_id,
                    CASE
                        WHEN bppb.jenis = 0 THEN 1
                        WHEN bppb.jenis = 1 THEN 2
                    END AS bppb_type_id,
                    bppb.keterangan AS description,
                    bppb.tanggalTerima AS received_date,
                    bppb.tanggalBppb AS created_at
            FROM bppb
        ");
        // LIMIT 30

        foreach ($results as $row) {
            Bppb::create([
                'id' => $row->id,
                'noBppb' => $row->noBppb,
                'user_id' => $row->user_id,
                'number' => $row->number,
                'status_id' => $row->status_id,
                'bppb_type_id' => $row->bppb_type_id,
                'description' => $row->description,
                'received_date' => $row->received_date != '0000-00-00' ? $row->received_date . ' 00:00:00' : null,
                'created_at' => $row->created_at,
            ]);
        }
    }
}
