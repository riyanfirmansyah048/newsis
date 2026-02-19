<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $results = DB::connection('source_db')->select(
            "
            SELECT
                    vendor.idVendor AS id,
                    vendor.namaVendor AS vendorName,
                    vendor.alamatVendor AS vendorAddress,
                    vendor.ketVendor AS vendorDescription
            FROM vendor"
        );

        foreach ($results as $row) {
            Vendor::create([
                'id' => $row->id,
                'vendorName' => $row->vendorName,
                'vendorAddress' => $row->vendorAddress,
                'vendorDescription' => $row->vendorDescription,
            ]);
        }
    }
}
