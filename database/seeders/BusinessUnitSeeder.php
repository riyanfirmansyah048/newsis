<?php

namespace Database\Seeders;

use App\Models\BusinessUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BusinessUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
        SELECT
            bisnisunit.idBisnisUnit AS id,
            bisnisunit.idRegion AS idRegional,
            bisnisunit.bisnisUnitName AS businessUnitName,
            bisnisunit.code
        FROM bisnisunit
    ");

        foreach ($results as $row) {
            BusinessUnit::create([
                'id' => $row->id,
                'idRegional' => $row->idRegional,
                'businessUnitName' => $row->businessUnitName,
                'code' => $row->code,
            ]);
        }
    }
}
