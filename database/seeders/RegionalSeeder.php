<?php

namespace Database\Seeders;

use App\Models\Regional;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RegionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                region.idRegion AS id,
                region.regionName AS regionalName,
                region.idCompany AS idCompany,
                region.code
            FROM region
        ");

        foreach ($results as $row) {
            Regional::create([
                'id' => $row->id,
                'regionalName' => $row->regionalName,
                'idCompany' => $row->idCompany,
                'code' => $row->code,
            ]);
        }
    }
}
