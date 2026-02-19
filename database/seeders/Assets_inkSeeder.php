<?php

namespace Database\Seeders;

use App\Models\Assets_ink;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Assets_inkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // memasukan assets ink ____________________________________________ Start
        DB::connection('source_db')
            ->table('tintadetail')
            ->join('bppb', 'tintadetail.idBppb', '=', 'bppb.idBppb')
            ->join('po', 'bppb.idBppb', '=', 'po.idBppb')
            ->join('karyawan', 'bppb.idKaryawan', '=', 'karyawan.idKaryawan')
            ->join('company', 'karyawan.idCompany', '=', 'company.idCompany')
            ->join('bpb', 'po.idBpb', '=', 'bpb.idBpb')
            ->whereNotNull('po.idBpb')
            ->select([
                'tintadetail.urutT as number',
                'bppb.idKaryawan as user_id',
                'tintadetail.idTipeT as ink_id',
                'po.idBpb as bpb_id',
                'tintadetail.idTintaD as bppb_ink_id',
                'karyawan.idCompany',
                'karyawan.idRegion as idRegional',
                'karyawan.idBisnisUnit as idBusinessUnit',
                'karyawan.idDepartement as idDepartment',
                'karyawan.idPosisi as idPosition',
                'bpb.noBpb',
                'company.code',
            ])
            ->orderBy('tintadetail.idTintaD')
            ->chunk(1000, function ($rows) {

                $data = [];
                $random = strtoupper(Str::random(8)); // random 8 karakter
                foreach ($rows as $item) {
                    $data[] = [
                        'number' => $item->number,
                        'noAssetInk' => "{$item->code}/{$item->number}/INK/{$item->noBpb}/{$random}",
                        'numberOwner' => 1,
                        'user_id' => $item->user_id,
                        'ink_id' => $item->ink_id,
                        'bpb_id' => $item->bpb_id,
                        'bppb_ink_id' => $item->bppb_ink_id,
                        'idCompany' => $item->idCompany,
                        'idRegional' => $item->idRegional,
                        'idBusinessUnit' => $item->idBusinessUnit,
                        'idDepartment' => $item->idDepartment,
                        'idPosition' => $item->idPosition,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                Assets_ink::insert($data);
            });
        // memasukan assets ink ____________________________________________ End
    }
}
