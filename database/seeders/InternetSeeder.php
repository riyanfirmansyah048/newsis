<?php

namespace Database\Seeders;

use App\Models\Internet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InternetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                internet.idInternet AS id,
                internet.idKaryawan AS idUser,
                internet.kebutuhan AS description,
                internet.url,
                internet.ipAddress AS ip,
                internet.aktiv AS activeStatus
            FROM internet
        ");
        // LIMIT 30

        foreach ($results as $row) {
            Internet::create([
                'id' => $row->id,
                'idUser' => $row->idUser,
                'description' => $row->description,
                'url' => $row->url,
                'ip' => $row->ip,
                'activeStatus' => $row->activeStatus,
            ]);
        }
    }
}
