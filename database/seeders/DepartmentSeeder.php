<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                departement.idDepartement AS id,
                departement.idBisnisUnit AS idBusinessUnit,
                departement.departementName AS departmentName,
                departement.code
            FROM departement
        ");

        foreach ($results as $row) {
            Department::create([
                'id' => $row->id,
                'idBusinessUnit' => $row->idBusinessUnit,
                'departmentName' => $row->departmentName,
                'code' => $row->code,
            ]);
        }
    }
}
