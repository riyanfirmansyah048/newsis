<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service_solusi;

class Service_solusiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service_solusi::create([
            'id' => 1,
            'name' => 'Maintenance Internal',
        ]);
        Service_solusi::create([
            'id' => 2,
            'name' => 'Maintenance External',
        ]);
        Service_solusi::create([
            'id' => 3,
            'name' => 'Request MR (BPPB)',
        ]);
        Service_solusi::create([
            'id' => 4,
            'name' => 'Change Part (Taken From Stock)',
        ]);
        Service_solusi::create([
            'id' => 5,
            'name' => 'New Part (Taken From Stock)',
        ]);
        Service_solusi::create([
            'id' => 6,
            'name' => 'Reject Request',
        ]);
    }
}
