<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
        SELECT
            jabatan.idJabatan AS id,
            jabatan.namaJabatan AS positionName,
            jabatan.keterangan AS code
        FROM jabatan
    ");

        foreach ($results as $row) {
            Position::create([
                'id' => $row->id,
                'positionName' => $row->positionName,
                'code' => $row->code,
                'description' => $row->code,
            ]);
        }
    }
}
