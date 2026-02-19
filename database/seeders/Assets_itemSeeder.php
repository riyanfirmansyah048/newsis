<?php

namespace Database\Seeders;

use App\Models\Assets_item;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Assets_itemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // memasukan assets item__________________________________________________Start
        DB::connection('source_db')
            ->table('barangdetail')
            ->join('bppb', 'barangdetail.idBppb', '=', 'bppb.idBppb')
            ->join('po', 'bppb.idBppb', '=', 'po.idBppb')
            ->join('karyawan', 'bppb.idKaryawan', '=', 'karyawan.idKaryawan')
            ->join('company', 'karyawan.idCompany', '=', 'company.idCompany')
            ->join('bpb', 'po.idBpb', '=', 'bpb.idBpb')
            ->whereNotNull('po.idBpb')
            ->select([
                'barangdetail.urutB as number',
                'bppb.idKaryawan as user_id',
                'barangdetail.idTipe as item_id',
                'po.idBpb as bpb_id',
                'barangdetail.idBarangD as bppb_item_id',
                'karyawan.idCompany',
                'karyawan.idRegion as idRegional',
                'karyawan.idBisnisUnit as idBusinessUnit',
                'karyawan.idDepartement as idDepartment',
                'karyawan.idPosisi as idPosition',
                'bpb.noBpb',
                'company.code',
            ])
            ->orderBy('barangdetail.idBarangD')
            ->chunk(1000, function ($rows) {
                $data = [];
                foreach ($rows as $item) {
                    $random = strtoupper(Str::random(8)); // random 8 karakter
                    $data[] = [
                        'number' => $item->number,
                        'noAssetItem' => "{$item->code}/{$item->number}/ITM/{$item->noBpb}/{$random}",
                        'numberOwner' => 1,
                        'user_id' => $item->user_id,
                        'item_id' => $item->item_id,
                        'bpb_id' => $item->bpb_id,
                        'bppb_item_id' => $item->bppb_item_id,
                        'idCompany' => $item->idCompany,
                        'idRegional' => $item->idRegional,
                        'idBusinessUnit' => $item->idBusinessUnit,
                        'idDepartment' => $item->idDepartment,
                        'idPosition' => $item->idPosition,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                Assets_item::insert($data);
            });
        // memasukan assets item__________________________________________________End

        //memasaukan assets komputer______________________________________________Start
        DB::connection('source_db')
            ->table('komputerdetail')
            ->join('bppb', 'komputerdetail.idBppb', '=', 'bppb.idBppb')
            ->join('po', 'bppb.idBppb', '=', 'po.idBppb')
            ->join('karyawan', 'bppb.idKaryawan', '=', 'karyawan.idKaryawan')
            ->join('company', 'karyawan.idCompany', '=', 'company.idCompany')
            ->join('bpb', 'po.idBpb', '=', 'bpb.idBpb')
            ->whereNotNull('po.idBpb')
            ->selectRaw("
                komputerdetail.urutK as number,
                bppb.idKaryawan as user_id,
                CASE 
                    WHEN komputerdetail.idKomputer = 27 THEN 1741
                    WHEN komputerdetail.idKomputer = 29 THEN 1742
                    WHEN komputerdetail.idKomputer = 30 THEN 1743
                    WHEN komputerdetail.idKomputer = 32 THEN 1744
                    WHEN komputerdetail.idKomputer = 33 THEN 1745
                    WHEN komputerdetail.idKomputer = 34 THEN 1746
                    WHEN komputerdetail.idKomputer = 36 THEN 1747
                    WHEN komputerdetail.idKomputer = 39 THEN 1748
                END AS item_id,
                po.idBpb as bpb_id,
                komputerdetail.idKomputerD as bppb_item_id,
                karyawan.idCompany,
                karyawan.idRegion as idRegional,
                karyawan.idBisnisUnit as idBusinessUnit,
                karyawan.idDepartement as idDepartment,
                karyawan.idPosisi as idPosition,
                bpb.noBpb,
                company.code
            ")
            ->orderBy('komputerdetail.idKomputerD')
            ->chunk(1000, function ($rows) {

                $data = [];

                foreach ($rows as $item) {
                    $random = strtoupper(Str::random(8)); // random 8 karakter
                    // skip kalau mapping CASE null
                    if (!$item->item_id) continue;

                    $data[] = [
                        'number' => $item->number,
                        'noAssetItem' => "{$item->code}/{$item->number}/ITM/{$item->noBpb}/{$random}",
                        'numberOwner' => 1,
                        'user_id' => $item->user_id,
                        'item_id' => $item->item_id,
                        'bpb_id' => $item->bpb_id,
                        'bppb_item_id' => $item->bppb_item_id,
                        'idCompany' => $item->idCompany,
                        'idRegional' => $item->idRegional,
                        'idBusinessUnit' => $item->idBusinessUnit,
                        'idDepartment' => $item->idDepartment,
                        'idPosition' => $item->idPosition,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                Assets_item::insert($data);
            });
        //memasaukan assets komputer______________________________________________End
    }
}
