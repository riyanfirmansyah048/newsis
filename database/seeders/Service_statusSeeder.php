<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service_status;

class Service_statusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service_status::create([
            'id' => 1,
            'name' => 'Belum Diajukan',
        ]);
        Service_status::create([
            'id' => 2,
            'name' => 'Rejected',
        ]);
        Service_status::create([
            'id' => 3,
            'name' => 'Menunggu Konfirmasi IT',
        ]);
        Service_status::create([
            'id' => 4,
            'name' => 'Barang Diterima di IT',
        ]);
        Service_status::create([
            'id' => 5,
            'name' => 'Proses Service',
        ]);
        Service_status::create([
            'id' => 6,
            'name' => 'Selesai (Barang Di IT)',
        ]);
        Service_status::create([
            'id' => 7,
            'name' => 'Selesai (Barang Sudah Diserahkan)',
        ]);
        Service_status::create([
            'id' => 8,
            'name' => 'Pending',
        ]);
    }
}
