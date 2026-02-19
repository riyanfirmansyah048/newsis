<?php

namespace Database\Seeders;

use App\Models\Bpb;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BpbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                bpb.idBpb AS id,
                bpb.urut AS number,
                bpb.noBpb,
                po.idPo AS po_id,
                bppb.idKaryawan AS user_id,
                bpb.tanggalBpb AS dateBpb,
                bpb.keterangan AS description
            FROM bpb
            JOIN po ON bpb.idBpb = po.idBpb
            JOIN bppb ON po.idBppb = bppb.idBppb
        ");
        // LIMIT 30

        foreach ($results as $row) {
            Bpb::create([
                'id' => $row->id,
                'number' => $row->number,
                'noBpb' => $row->noBpb,
                'po_id' => $row->po_id,
                'user_id' => $row->user_id,
                'dateBpb' => $row->dateBpb != '0000-00-00' ? $row->dateBpb . ' 00:00:00' : null,
                'description' => $row->description,
            ]);
        }
    }
}
