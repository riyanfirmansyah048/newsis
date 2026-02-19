<?php

namespace Database\Seeders;

use App\Models\Bppb_software;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Bppb_softwareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                softwaredetail.idSoftwareD AS id,
                softwaredetail.idBppb AS bppb_id,
                softwaredetail.idTipeS AS software_id,
                softwaredetail.idPo AS purchase_order_id,
                softwaredetail.noBppbPemohon,
                softwaredetail.pemohonIT,
                softwaredetail.userPemohon,
                softwaredetail.departementPemohon,
                softwaredetail.lokasiPemohon,
                softwaredetail.serialNumber,
                1 AS qty,
                softwaredetail.keterangan AS description
            FROM softwaredetail
        ");
        // LIMIT 30

        foreach ($results as $row) {
            Bppb_software::create([
                'id' => $row->id,
                'bppb_id' => $row->bppb_id,
                'software_id' => $row->software_id,
                'purchase_order_id' => $row->purchase_order_id,
                'qty' => $row->qty,
                'description' => $row->description,
                'noBppbPemohon' => $row->noBppbPemohon,
                'pemohonIT' => $row->pemohonIT,
                'userPemohon' => $row->userPemohon,
                'departementPemohon' => $row->departementPemohon,
                'lokasiPemohon' => $row->lokasiPemohon,
                'serialNumber' => $row->serialNumber,
            ]);
        }
    }
}
