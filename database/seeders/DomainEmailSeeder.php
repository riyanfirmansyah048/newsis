<?php

namespace Database\Seeders;

use App\Models\DomainEmail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DomainEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                domainemail.idDomain AS id,
                domainemail.idCompany AS idCompany,
                domainemail.namaDomain AS domainName,
                domainemail.judulDomain AS titleName,
                domainemail.imap,
                domainemail.pop3,
                domainemail.smtp
            FROM domainemail
        ");

        foreach ($results as $row) {
            DomainEmail::create([
                'id' => $row->id,
                'idCompany' => $row->idCompany,
                'domainName' => $row->domainName,
                'titleName' => $row->titleName,
                'imap' => $row->imap,
                'pop3' => $row->pop3,
                'smtp' => $row->smtp,
                'description' => '',
            ]);
        }
    }
}
