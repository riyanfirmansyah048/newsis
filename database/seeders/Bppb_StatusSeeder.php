<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bppb_status;

class Bppb_StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bppb_status::create(['id' => 1, 'name' => 'Belum Diajukan', 'description' => 'Masih bisa di edit oleh user, tapi tombol print BPPB tidak muncul',]);
        Bppb_status::create(['id' => 2, 'name' => 'Rejected', 'description' => 'di tolak',]);
        Bppb_status::create(['id' => 3, 'name' => 'Menunggu Konfirmasi IT', 'description' => 'BPPB sudah di ajukan oleh user, menunggu konfirmasi dari IT (menunggu dokument di terima IT)',]);
        Bppb_status::create(['id' => 4, 'name' => 'Approved', 'description' => 'BPPB di setujui',]);
        Bppb_status::create(['id' => 5, 'name' => 'Barang Diterima di IT', 'description' => 'BPB sudah dibuat, dan barang sudah di terima di IT',]);
        Bppb_status::create(['id' => 6, 'name' => 'Selesai', 'description' => 'barang sudah di ambil oleh user pemesan atau sudah dikirim melalui expedisi',]);
        Bppb_status::create(['id' => 7, 'name' => 'Expedisi', 'description' => 'barang di kirim melalui expedisi',]);
    }
}
