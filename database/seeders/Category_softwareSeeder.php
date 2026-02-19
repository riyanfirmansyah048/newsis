<?php

namespace Database\Seeders;

use App\Models\Category_software;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Category_softwareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                kategorisoftware.idKategoriS AS id,
                kategorisoftware.nama AS name
            FROM kategorisoftware
        ");

        foreach ($results as $row) {
            Category_software::create([
                'id' => $row->id,
                'name' => $row->name,
            ]);
        }
    }
}
