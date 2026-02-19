<?php

namespace Database\Seeders;

use App\Models\Product_form;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Product_formSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product_form::create([
            'name' => 'Hardware',
            'description' => 'untuk jenis barang-barang hardware',
        ]);
        Product_form::create([
            'name' => 'Software',
            'description' => 'untuk jenis barang-barang software',
        ]);
        Product_form::create([
            'name' => 'Tinta',
            'description' => 'untuk jenis barang-barang Tinta',
        ]);
        Product_form::create([
            'name' => 'Maintenance External',
            'description' => 'Untuk Maintenance External',
        ]);
        Product_form::create([
            'name' => 'Komputer',
            'description' => 'Untuk barang komputer set',
        ]);
    }
}
