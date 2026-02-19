<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bppb_type;

class Bppb_typeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bppb_type::create(['id' => 1, 'name' => 'BPPB']);
        Bppb_type::create(['id' => 2, 'name' => 'BPPB Manual']);
        Bppb_type::create(['id' => 3, 'name' => 'BPPB Service']);
        Bppb_type::create(['id' => 4, 'name' => 'BPPB Maintenance External']);
    }
}
