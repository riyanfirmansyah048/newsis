<?php

namespace Database\Seeders;

use App\Models\SubDepartment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                subdepartement.idSubDepartement AS id,
                subdepartement.idDepartement AS idDepartment,
                subdepartement.subDepartementName AS subDepartmentName,
                subdepartement.code
            FROM subdepartement
        ");

        foreach ($results as $row) {
            SubDepartment::create([
                'id' => $row->id,
                'idDepartment' => $row->idDepartment,
                'subDepartmentName' => $row->subDepartmentName,
                'code' => $row->code,
            ]);
        }
    }
}
