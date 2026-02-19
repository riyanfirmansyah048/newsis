<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
        SELECT
            company.idCompany,
            company.companyName,
            company.code
        FROM company
    ");

        foreach ($results as $row) {
            Company::create([
                'id' => $row->idCompany,
                'companyName' => $row->companyName,
                'code' => $row->code,
            ]);
        }
    }
}
