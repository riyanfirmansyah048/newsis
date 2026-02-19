<?php

namespace Database\Seeders;

use App\Models\Software;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SoftwareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                tipesoftware.idTipeS AS id,
                tipesoftware.nama AS name,
                2 AS product_form_id,
                2 AS type_id,
                software.idKategoriS AS category_software_id,
                software.idSoftware AS brand_software_id,
                1 AS unit_id
            FROM tipesoftware
            JOIN software ON tipesoftware.idSoftware = software.idSoftware
            WHERE software.idKategoriS IS NOT NULL
            AND software.idSoftware IS NOT NULL
        ");

        foreach ($results as $row) {
            Software::create([
                'id' => $row->id,
                'name' => $row->name,
                'product_form_id' => $row->product_form_id,
                'type_id' => $row->type_id,
                'category_software_id' => $row->category_software_id,
                'brand_software_id' => $row->brand_software_id,
                'unit_id' => $row->unit_id,
            ]);
        }
    }
}
