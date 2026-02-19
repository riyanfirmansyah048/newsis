<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand_software;

class Brand_softwareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                software.idSoftware AS id,
                software.namaSoftware AS name,
                software.idKategoriS AS category_software_id
            FROM software
        ");

        foreach ($results as $row) {
            Brand_software::create([
                'id' => $row->id,
                'name' => $row->name,
                'category_software_id' => $row->category_software_id,
            ]);
        }
    }
}
