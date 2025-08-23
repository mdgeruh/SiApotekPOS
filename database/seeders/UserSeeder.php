<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data lama jika ada
        DB::table('users')->delete();

        // Reset auto increment
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');

        // Get role IDs
        $adminRoleId = DB::table('roles')->where('name', 'admin')->first()->id;
        $kasirRoleId = DB::table('roles')->where('name', 'kasir')->first()->id;
        $manajemenRoleId = DB::table('roles')->where('name', 'manajemen_obat')->first()->id;

        DB::table('users')->insert([
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@apotek.com',
                'phone' => '081234567890',
                'profile_photo_path' => null,
                'birth_date' => '1990-01-01',
                'gender' => 'male',
                'address' => 'Jl. Admin No. 1, Jakarta',
                'status' => 'active',
                'password' => Hash::make('password'),
                'role_id' => $adminRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kasir',
                'username' => 'kasir',
                'email' => 'kasir@apotek.com',
                'phone' => '081234567891',
                'profile_photo_path' => null,
                'birth_date' => '1992-05-15',
                'gender' => 'female',
                'address' => 'Jl. Kasir No. 2, Jakarta',
                'status' => 'active',
                'password' => Hash::make('password'),
                'role_id' => $kasirRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manajemen Obat',
                'username' => 'manajemen',
                'email' => 'manajemen@apotek.com',
                'phone' => '081234567892',
                'profile_photo_path' => null,
                'birth_date' => '1988-12-20',
                'gender' => 'male',
                'address' => 'Jl. Manajemen No. 3, Jakarta',
                'status' => 'active',
                'password' => Hash::make('password'),
                'role_id' => $manajemenRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('User seeder berhasil dibuat!');
    }
}
