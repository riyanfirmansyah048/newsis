<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\Assets_software;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Assets_softwareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // memasukan assets software ____________________________________________ Start
        DB::connection('source_db')
            ->table('softwaredetail')
            ->join('bppb', 'softwaredetail.idBppb', '=', 'bppb.idBppb')
            ->join('po', 'bppb.idBppb', '=', 'po.idBppb')
            ->join('karyawan', 'bppb.idKaryawan', '=', 'karyawan.idKaryawan')
            ->join('company', 'karyawan.idCompany', '=', 'company.idCompany')
            ->join('bpb', 'po.idBpb', '=', 'bpb.idBpb')
            ->whereNotNull('po.idBpb')
            ->select([
                'softwaredetail.urutS as number', // cek nama kolom urut
                'bppb.idKaryawan as user_id',
                'softwaredetail.idTipeS as software_id',
                'po.idBpb as bpb_id',
                'softwaredetail.idSoftwareD as bppb_software_id',
                'softwaredetail.noBppbPemohon',
                'softwaredetail.pemohonIT',
                'softwaredetail.userPemohon',
                'softwaredetail.departementPemohon',
                'softwaredetail.lokasiPemohon',
                'softwaredetail.serialNumber',
                'karyawan.idCompany',
                'karyawan.idRegion as idRegional',
                'karyawan.idBisnisUnit as idBusinessUnit',
                'karyawan.idDepartement as idDepartment',
                'karyawan.idPosisi as idPosition',
                'bpb.noBpb',
                'company.code',
            ])
            ->orderBy('softwaredetail.idSoftwareD')
            ->chunk(1000, function ($rows) {

                $data = [];

                foreach ($rows as $item) {
                    $random = strtoupper(Str::random(8)); // random 8 karakter
                    $data[] = [
                        'number' => $item->number,
                        'noAssetSoftware' => "{$item->code}/{$item->number}/SFT/{$item->noBpb}/{$random}",
                        'numberOwner' => 1,
                        'user_id' => $item->user_id,
                        'software_id' => $item->software_id,
                        'bpb_id' => $item->bpb_id,
                        'bppb_software_id' => $item->bppb_software_id,

                        // extra fields dari softwaredetail
                        // 'noBppbPemohon' => $item->noBppbPemohon,
                        // 'pemohonIT' => $item->pemohonIT,
                        // 'userPemohon' => $item->userPemohon,
                        // 'departementPemohon' => $item->departementPemohon,
                        // 'lokasiPemohon' => $item->lokasiPemohon,
                        // 'serialNumber' => $item->serialNumber,

                        'idCompany' => $item->idCompany,
                        'idRegional' => $item->idRegional,
                        'idBusinessUnit' => $item->idBusinessUnit,
                        'idDepartment' => $item->idDepartment,
                        'idPosition' => $item->idPosition,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                Assets_software::insert($data);
            });
        // memasukan assets software ____________________________________________ End

    }
}
