<?php

namespace Database\Seeders;

use App\Models\Email;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                email.idEmail AS id,
                email.idDomain AS idDomainEmail,
                email.idKaryawan AS idUser,
                email.idCompany,
                email.namaEmail AS emailName,
                'S@nbe2019' AS passwordEmail,
                email.aktif AS activeStatus,
                email.tanggalAktif AS activeDate,
                email.tanggalPengajuan AS created_at
            FROM email
            WHERE email.idKaryawan IS NOT NULL
        ");
        // LIMIT 30

        foreach ($results as $row) {
            Email::create([
                'id' => $row->id,
                'idDomainEmail' => $row->idDomainEmail,
                'idUser' => $row->idUser,
                'idCompany' => $row->idCompany,
                'emailName' => $row->emailName,
                'passwordEmail' => $row->passwordEmail,
                'activeStatus' => $row->activeStatus,
                'activeDate' => $row->activeDate != '0000-00-00' ? $row->activeDate . ' 00:00:00' : null,
                'created_at' => $row->created_at != '0000-00-00' ? $row->created_at . ' 00:00:00' : null,
            ]);
        }
    }
}
