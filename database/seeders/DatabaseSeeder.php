<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            CompanySeeder::class,
            RegionalSeeder::class,
            BusinessUnitSeeder::class,
            DepartmentSeeder::class,
            SubDepartmentSeeder::class,
            SectionSeeder::class,
            PositionSeeder::class,
            Product_formSeeder::class,
            TypeSeeder::class,
            UnitSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ItemSeeder::class,
            Category_softwareSeeder::class,
            Brand_softwareSeeder::class,
            SoftwareSeeder::class,
            Category_inkSeeder::class,
            Brand_inkSeeder::class,
            InkSeeder::class,
            VendorSeeder::class,
            DomainEmailSeeder::class,
            EmailSeeder::class,
            InternetSeeder::class,
            BpbSeeder::class,
            Bppb_inkSeeder::class,
            Bppb_itemSeeder::class,
            Bppb_softwareSeeder::class,
            Bppb_StatusSeeder::class,
            Bppb_typeSeeder::class,
            BppbSeeder::class,
            ExpeditionSeeder::class,
            ExpeditionDetailSeeder::class,
            Purchase_orderSeeder::class,
            Service_solusiSeeder::class,
            Service_statusSeeder::class,
            Service_typeSeeder::class,
            ServiceSeeder::class,
            Assets_itemSeeder::class,
            Assets_inkSeeder::class,
            Assets_softwareSeeder::class,
        ]);
    }
}
