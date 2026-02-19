<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service_type;

class Service_typeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service_type::create([
            'id' => 1,
            'name' => 'Material/Part',
        ]);
        Service_type::create([
            'id' => 2,
            'name' => 'Service',
        ]);
        Service_type::create([
            'id' => 3,
            'name' => 'Software',
        ]);
    }
}
