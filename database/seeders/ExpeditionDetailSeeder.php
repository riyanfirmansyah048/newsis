<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpeditionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExpeditionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                expedisidetail.idExpedisiD AS id,
                expedisidetail.idExpedisi AS expedition_id,
                expedisidetail.idPo AS po_id,
                CASE
                    WHEN expedisidetail.jenis = 'barang' THEN 1
                    WHEN expedisidetail.jenis = 'komputer' THEN 5
                    WHEN expedisidetail.jenis = 'tinta' THEN 3
                    WHEN expedisidetail.jenis = 'software' THEN 2
                    ELSE NULL
                END AS product_form_id,
                expedisidetail.idJenis AS type_id
            FROM expedisidetail
        ");

        foreach ($results as $row) {
            ExpeditionDetail::create([
                'id' => $row->id,
                'expedition_id' => $row->expedition_id,
                'po_id' => $row->po_id,
                'product_form_id' => $row->product_form_id,
                'type_id' => $row->type_id,
            ]);
        }
    }
}
