<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::create([
            'name' => 'Pcs',
        ]);
        Unit::create([
            'name' => 'Set',
        ]);
        Unit::create([
            'name' => 'Botol',
        ]);
        Unit::create([
            'name' => '-',
        ]);
    }
}
