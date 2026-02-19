<?php

namespace Database\Seeders;

use App\Models\Expedition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExpeditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                expedisi.idExpedisi AS id,
                expedisi.noUrut AS number,
                expedisi.noExpedisi AS noExpedition,
                expedisi.expeditor,
                expedisi.idBppb AS bppb_id,
                expedisi.tanggalInput AS dateInput,
                expedisi.tanggalStart AS dateStart,
                expedisi.tanggalFinish AS dateFinish,
                expedisi.tanggalPrint AS datePrint,
                expedisi.idKaryawan AS user_id,
                expedisi.keterangan AS description
            FROM expedisi
        ");

        foreach ($results as $row) {
            Expedition::create([
                'id' => $row->id,
                'number' => $row->number,
                'noExpedition' => $row->noExpedition,
                'expeditor' => $row->expeditor,
                'bppb_id' => $row->bppb_id,
                'dateInput' => $row->dateInput != '0000-00-00 00:00:00' ? $row->dateInput : null,
                'dateStart' => $row->dateStart != '0000-00-00' ? $row->dateStart : null,
                'dateFinish' => $row->dateFinish != '0000-00-00' ? $row->dateFinish : null,
                'datePrint' => $row->datePrint != '0000-00-00' ? $row->datePrint : null,
                'user_id' => $row->user_id,
                'description' => $row->description,
            ]);
        }
    }
}
