<?php

namespace Database\Seeders;

use App\Models\Ink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                tipetinta.idTipeT AS id,
                tipetinta.nama AS name,
                3 AS product_form_id,
                1 AS type_id,
                tinta.idKategoriT AS category_ink_id,
                tinta.idTinta AS brand_ink_id,
                1 AS unit_id
            FROM tipetinta
            JOIN tinta ON tipetinta.idTinta = tinta.idTinta
        ");
        foreach ($results as $row) {
            Ink::create([
                'id' => $row->id,
                'name' => $row->name,
                'product_form_id' => $row->product_form_id,
                'type_id' => $row->type_id,
                'category_ink_id' => $row->category_ink_id,
                'brand_ink_id' => $row->brand_ink_id,
                'unit_id' => $row->unit_id,
            ]);
        }
    }
}
