<?php

namespace Database\Seeders;

use App\Models\Bppb_item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Bppb_itemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resultsitems = DB::connection('source_db')->select("
            SELECT
                barangdetail.idBarangD AS id,
                barangdetail.idBppb AS bppb_id,
                barangdetail.idTipe AS item_id,
                barangdetail.idPo AS purchase_order_id,
                1 AS qty,
                barangdetail.keterangan AS description
            FROM barangdetail
        ");
        // LIMIT 30

        $resultsKomputers = DB::connection('source_db')->select("
            SELECT 
                komputerdetail.idKomputerD AS id,
                komputerdetail.idBppb AS bppb_id,
                CASE 
                    WHEN komputerdetail.idKomputer = 27 THEN 1741
                    WHEN komputerdetail.idKomputer = 29 THEN 1742
                    WHEN komputerdetail.idKomputer = 30 THEN 1743
                    WHEN komputerdetail.idKomputer = 32 THEN 1744
                    WHEN komputerdetail.idKomputer = 33 THEN 1745
                    WHEN komputerdetail.idKomputer = 34 THEN 1746
                    WHEN komputerdetail.idKomputer = 36 THEN 1747
                    WHEN komputerdetail.idKomputer = 39 THEN 1748
                END AS item_id,
                komputerdetail.idPo AS purchase_order_id,
                1 AS qty,
                komputerdetail.keterangan AS description
            FROM komputerdetail;
        ");

        foreach ($resultsitems as $item) {
            Bppb_item::create([
                // 'id' => $item->id,
                'bppb_id' => $item->bppb_id,
                'item_id' => $item->item_id,
                'purchase_order_id' => $item->purchase_order_id,
                'qty' => $item->qty,
                'description' => $item->description,
            ]);
        }

        foreach ($resultsKomputers as $komputer) {
            Bppb_item::create([
                // 'id' => $komputer->id,
                'bppb_id' => $komputer->bppb_id,
                'item_id' => $komputer->item_id,
                'purchase_order_id' => $komputer->purchase_order_id,
                'qty' => $komputer->qty,
                'description' => $komputer->description,
            ]);
        }
    }
}
