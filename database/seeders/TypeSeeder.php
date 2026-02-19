<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type::create([
            'name' => 'Habis Pakai',
            'description' => 'Barang yang habis digunakan dalam satu kali pemakaian.'
        ]);

        Type::create([
            'name' => 'Asset',
            'description' => 'Barang yang tidak habis digunakan dalam satu kali pemakaian.'
        ]);

        Type::create([
            'name' => 'Jasa',
            'description' => 'Layanan yang diberikan oleh seseorang atau perusahaan kepada orang lain.'
        ]);
    }
}
