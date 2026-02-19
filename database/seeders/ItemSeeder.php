<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resultsItems = DB::connection('source_db')->select("
            SELECT
                tipe.idTipe AS id,
                tipe.nama AS name,
                1 AS product_form_id,
                2 AS type_id,
                barang.idKategoriB AS category_id,
                barang.idBarang AS brand_id,
                1 AS unit_id
            FROM tipe
            JOIN barang ON tipe.idBarang = barang.idBarang
            WHERE barang.idKategoriB IS NOT NULL;
        ");

        foreach ($resultsItems as $item) {
            Item::create([
                'id' => $item->id,
                'name' => $item->name,
                'product_form_id' => $item->product_form_id,
                'type_id' => $item->type_id,
                'category_id' => $item->category_id,
                'brand_id' => $item->brand_id,
                'unit_id' => $item->unit_id,
            ]);
        }

        // barang komputer set---------------------------------------------------------
        Item::create([
            'id' => 1741, // old id 27
            'name' => 'Komputer Standard',
            'product_form_id' => 5,
            'type_id' => 2,
            'category_id' => 119,
            'brand_id' => 347,
            'unit_id' => 2
        ]);
        Item::create([
            'id' => 1742, // old id 29
            'name' => 'Komputer Core i5',
            'product_form_id' => 5,
            'type_id' => 2,
            'category_id' => 119,
            'brand_id' => 347,
            'unit_id' => 2
        ]);
        Item::create([
            'id' => 1743, // old id 30
            'name' => 'Komputer Core I3',
            'product_form_id' => 5,
            'type_id' => 2,
            'category_id' => 119,
            'brand_id' => 347,
            'unit_id' => 2
        ]);
        Item::create([
            'id' => 1744, // old id 32
            'name' => 'Komputer Xeon',
            'product_form_id' => 5,
            'type_id' => 2,
            'category_id' => 119,
            'brand_id' => 347,
            'unit_id' => 2
        ]);
        Item::create([
            'id' => 1745, // old id 33
            'name' => 'Komputer I3 Design',
            'product_form_id' => 5,
            'type_id' => 2,
            'category_id' => 119,
            'brand_id' => 347,
            'unit_id' => 2
        ]);
        Item::create([
            'id' => 1746, // old id 34
            'name' => 'Komputer I3 New 18',
            'product_form_id' => 5,
            'type_id' => 2,
            'category_id' => 119,
            'brand_id' => 347,
            'unit_id' => 2
        ]);
        Item::create([
            'id' => 1747, // old id 36
            'name' => 'Komputer Core I7',
            'product_form_id' => 5,
            'type_id' => 2,
            'category_id' => 119,
            'brand_id' => 347,
            'unit_id' => 2
        ]);
        Item::create([
            'id' => 1748, // old id 39
            'name' => 'HP',
            'product_form_id' => 5,
            'type_id' => 2,
            'category_id' => 119,
            'brand_id' => 347,
            'unit_id' => 2
        ]);
        // barang komputer set---------------------------------------------------------
    }
}
