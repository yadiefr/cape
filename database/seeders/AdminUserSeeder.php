<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create Super Admin
        AdminUser::create([
            'name' => 'Super Admin',
            'email' => 'admin@smk3.sch.id',
            'password' => 'admin123',
            'role' => 'admin',
            'nip' => '1234567890',
            'phone' => '081234567890',
            'address' => 'Jl. Pendidikan No. 1, Jakarta',
            'birth_date' => '1980-01-01',
            'gender' => 'L',
            'status' => 'aktif',
        ]);

        // Create sample users for each role
        $sampleUsers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'guru@smk3.sch.id',
                'password' => 'guru123',
                'role' => 'guru',
                'nip' => '1234567891',
                'phone' => '081234567891',
                'address' => 'Jl. Guru No. 1, Jakarta',
                'birth_date' => '1985-05-15',
                'gender' => 'L',
                'status' => 'aktif',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'kurikulum@smk3.sch.id',
                'password' => 'kurikulum123',
                'role' => 'kurikulum',
                'nip' => '1234567892',
                'phone' => '081234567892',
                'address' => 'Jl. Kurikulum No. 1, Jakarta',
                'birth_date' => '1982-08-20',
                'gender' => 'P',
                'status' => 'aktif',
            ],
            [
                'name' => 'Ahmad Wijaya',
                'email' => 'tata.usaha@smk3.sch.id',
                'password' => 'tatausaha123',
                'role' => 'tata_usaha',
                'nip' => '1234567893',
                'phone' => '081234567893',
                'address' => 'Jl. Tata Usaha No. 1, Jakarta',
                'birth_date' => '1983-03-10',
                'gender' => 'L',
                'status' => 'aktif',
            ],
            [
                'name' => 'Dewi Sartika',
                'email' => 'bendahara@smk3.sch.id',
                'password' => 'bendahara123',
                'role' => 'bendahara',
                'nip' => '1234567894',
                'phone' => '081234567894',
                'address' => 'Jl. Bendahara No. 1, Jakarta',
                'birth_date' => '1984-12-05',
                'gender' => 'P',
                'status' => 'aktif',
            ],
            [
                'name' => 'Rudi Hartono',
                'email' => 'hubin@smk3.sch.id',
                'password' => 'hubin123',
                'role' => 'hubin',
                'nip' => '1234567895',
                'phone' => '081234567895',
                'address' => 'Jl. Hubin No. 1, Jakarta',
                'birth_date' => '1981-07-25',
                'gender' => 'L',
                'status' => 'aktif',
            ],
            [
                'name' => 'Rina Kusuma',
                'email' => 'perpustakaan@smk3.sch.id',
                'password' => 'perpustakaan123',
                'role' => 'perpustakaan',
                'nip' => '1234567896',
                'phone' => '081234567896',
                'address' => 'Jl. Perpustakaan No. 1, Jakarta',
                'birth_date' => '1986-11-18',
                'gender' => 'P',
                'status' => 'aktif',
            ],
            [
                'name' => 'Joko Susilo',
                'email' => 'kesiswaan@smk3.sch.id',
                'password' => 'kesiswaan123',
                'role' => 'kesiswaan',
                'nip' => '1234567897',
                'phone' => '081234567897',
                'address' => 'Jl. Kesiswaan No. 1, Jakarta',
                'birth_date' => '1979-04-30',
                'gender' => 'L',
                'status' => 'aktif',
            ],
        ];

        foreach ($sampleUsers as $userData) {
            AdminUser::create($userData);
        }
    }
}
