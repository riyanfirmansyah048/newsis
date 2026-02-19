<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                barang.idBarang AS id,
                barang.namaBarang AS name,
                barang.idKategoriB AS category_id
            FROM barang
            WHERE barang.namaBarang IS NOT NULL 
                AND barang.namaBarang != ''
                AND barang.idKategoriB IS NOT NULL;
        ");

        foreach ($results as $row) {
            Brand::create([
                'id' => $row->id,
                'name' => $row->name,
                'category_id' => $row->category_id,
            ]);
        }
    }
}
