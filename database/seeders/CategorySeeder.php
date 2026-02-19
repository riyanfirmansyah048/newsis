<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                kategoribarang.idKategoriB AS id,
                kategoribarang.namaKategoriB AS name,
                kategoribarang.kodeAsset AS codeAsset
            FROM kategoribarang
            WHERE kategoribarang.namaKategoriB IS NOT NULL 
                AND kategoribarang.namaKategoriB != '';
        ");

        foreach ($results as $row) {
            Category::create([
                'id' => $row->id,
                'name' => $row->name,
                'codeAsset' => $row->codeAsset,
            ]);
        }
    }
}
