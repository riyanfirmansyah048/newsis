<?php

namespace Database\Seeders;

use App\Models\Category_ink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Category_inkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                kategoritinta.idKategoriT AS id,
                kategoritinta.nama AS name
            FROM kategoritinta
        ");

        foreach ($results as $row) {
            Category_ink::create([
                'id' => $row->id,
                'name' => $row->name,
            ]);
        }
    }
}
