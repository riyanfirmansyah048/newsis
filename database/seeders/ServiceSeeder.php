<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select("
            SELECT
                service.idService AS id,
                service.idKaryawan AS user_id,
                service.IC AS ic_id,
                service.urut AS number,
                service.kodeService AS noService,
                service.idTipe AS item_id,
                service.idVendor AS vendor_id,
                service.tipePengajuan AS type_service_id,
                service.typeService AS solution_id,
                service.problem,
                service.estimasi AS estimation,
                service.analisa,
                service.alasanReject AS analisa_reject,
                service.tanggalTerima AS received_date,
                service.tanggalPengerjaan AS work_date,
                service.tanggalFinish AS finish_date,
                service.tanggalStart AS created_at,
                CASE 
                    WHEN service.completeSD = 1 THEN 7
                    WHEN service.completeSD = 0 THEN 1
                    ELSE service.completeSD
                END AS status_id
            FROM service
        ");

        foreach ($results as $row) {
            Service::create([
                'id' => $row->id,
                'user_id' => $row->user_id,
                'ic_id' => $row->ic_id,
                'number' => $row->number,
                'noService' => $row->noService,
                'item_id' => $row->item_id,
                'vendor_id' => $row->vendor_id,
                'type_service_id' => $row->type_service_id,
                'solution_id' => $row->solution_id,
                'problem' => $row->problem,
                'estimation' => $row->estimation,
                'analisa' => $row->analisa,
                'analisa_reject' => $row->analisa_reject,
                'received_date' => ($row->received_date && $row->received_date != '0000-00-00')
                    ? $row->received_date . ' 00:00:00'
                    : null,
                'work_date' => ($row->work_date && $row->work_date != '0000-00-00')
                    ? $row->work_date . ' 00:00:00'
                    : null,
                'finish_date' => ($row->finish_date && $row->finish_date != '0000-00-00')
                    ? $row->finish_date . ' 00:00:00'
                    : null,
                'created_at' => ($row->created_at && $row->created_at != '0000-00-00')
                    ? $row->created_at . ' 00:00:00'
                    : null,
                'status_id' => $row->status_id,
            ]);
        }
    }
}
