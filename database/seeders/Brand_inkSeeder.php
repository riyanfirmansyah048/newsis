<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand_ink;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Brand_inkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                tinta.idTinta AS id,
                tinta.namaTinta AS name,
                tinta.idKategoriT AS category_ink_id
            FROM tinta
        ");

        foreach ($results as $row) {
            Brand_ink::create([
                'id' => $row->id,
                'name' => $row->name,
                'category_ink_id' => $row->category_ink_id,
            ]);
        }
    }
}
