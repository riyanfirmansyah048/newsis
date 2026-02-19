<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // -------------------------------
        // 1️⃣ Admin & Default User
        // -------------------------------
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'username' => 'admin',
                'NIK' => '12345678',
                'address' => 'Bandung',
                'gender' => 1,
                'password' => bcrypt('12345678'),
                'idCompany' => 1,
                'idRegional' => 1,
                'idBusinessUnit' => 10,
                'idDepartment' => 30,
                'idSubDepartment' => 102,
                'idPosition' => 7,
                'ext' => '1335',
            ]
        );
        $admin->syncRoles([$adminRole]);

        $defaultUser = User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'user',
                'username' => 'user',
                'NIK' => '1234567',
                'address' => 'Cimahi',
                'gender' => 1,
                'password' => bcrypt('12345678'),
                'idCompany' => 1,
                'idRegional' => 1,
                'idBusinessUnit' => 10,
                'idDepartment' => 30,
                'idSubDepartment' => 102,
                'idPosition' => 7,
                'ext' => '1335',
            ]
        );
        $defaultUser->syncRoles([$userRole]);

        // -------------------------------
        // 2️⃣ Import dari DB lama (chunk 500)
        // -------------------------------
        DB::connection('source_db')->table('karyawan')
            // ->limit(10)
            ->orderBy('idKaryawan')
            ->chunk(500, function ($rows) use ($userRole) {

                $insertData = [];

                foreach ($rows as $row) {
                    $tanggalResign = ($row->tanggalResign === '0000-00-00' || empty($row->tanggalResign))
                        ? null
                        : $row->tanggalResign;

                    // Password aman
                    $password = !empty($row->passwordLogin)
                        ? bcrypt($row->passwordLogin)
                        : bcrypt('12345678');

                    $insertData[] = [
                        'id' => $row->idKaryawan, // Hapus kalau auto increment
                        'name' => $row->namaKaryawan,
                        'username' => $row->username,
                        'NIK' => $row->NIK,
                        'address' => $row->alamat,
                        'gender' => $row->gender,
                        'idCompany' => $row->idCompany,
                        'idRegional' => $row->idRegion,
                        'idBusinessUnit' => $row->idBisnisUnit,
                        'idDepartment' => $row->idDepartement,
                        'idSubDepartment' => $row->idSubDepartement,
                        'idSection' => $row->idSection,
                        'idPosition' => $row->idJabatan,
                        'ext' => $row->ext,
                        'hp' => $row->hp,
                        // 'image' => '',
                        'resign' => $row->resign,
                        'tanggalResign' => $tanggalResign,
                        'email' => $row->email ?? $row->username . '@sanbe.local',
                        'password' => $password,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Bulk insert users
                DB::table('users')->insert($insertData);

                // Ambil users baru
                $userEmails = collect($insertData)->pluck('email')->toArray();
                $users = User::whereIn('email', $userEmails)->get();

                // Assign role 'user' aman tanpa duplikat
                foreach ($users as $user) {
                    $user->syncRoles([$userRole]);
                }
            });

        $this->command->info('✅ Users import completed successfully!');
    }
}
