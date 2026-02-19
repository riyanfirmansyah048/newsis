<?php

namespace Database\Seeders;

use App\Models\Bppb_ink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Bppb_inkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                tintadetail.idTintaD AS id,
                tintadetail.idBppb AS bppb_id,
                tintadetail.idTipeT AS ink_id,
                tintadetail.idPo AS purchase_order_id,
                1 AS qty,
                tintadetail.keterangan AS description
            FROM tintadetail
        ");
        // LIMIT 30

        foreach ($results as $row) {
            Bppb_ink::create([
                'id' => $row->id,
                'bppb_id' => $row->bppb_id,
                'ink_id' => $row->ink_id,
                'purchase_order_id' => $row->purchase_order_id,
                'qty' => $row->qty,
                'description' => $row->description,
            ]);
        }
    }
}
