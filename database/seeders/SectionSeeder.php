<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                section.idSection AS id,
                section.idSubDepartement AS idSubDepartment,
                section.sectionName AS sectionName,
                section.code
            FROM section
        ");

        foreach ($results as $row) {
            Section::create([
                'id' => $row->id,
                'idSubDepartment' => $row->idSubDepartment,
                'sectionName' => $row->sectionName,
                'code' => $row->code,
            ]);
        }
    }
}
