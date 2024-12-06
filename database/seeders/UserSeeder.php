<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $data = [
            [
                'name' => 'Azhar Material',
                'username' => 'superadmin',
                'email' => 'super@azhar.com',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Rina Andriani',
                'username' => 'admin_gudang',
                'email' => 'gudang@azhar.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Budi Santoso',
                'username' => 'admin_penjualan',
                'email' => 'penjualan@azhar.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'username' => 'customer_sumatra',
                'email' => 'sumatra_customer@azhar.com',
                'role' => 'customer',
            ],
            [
                'name' => 'Eka Prasetyo',
                'username' => 'admin_keuangan',
                'email' => 'keuangan@azhar.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Andi Wijaya',
                'username' => 'customer_kalimantan',
                'email' => 'kalimantan_customer@azhar.com',
                'role' => 'customer',
            ],
            [
                'name' => 'Maya Rahmawati',
                'username' => 'admin_it',
                'email' => 'it@azhar.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Nurul Aini',
                'username' => 'customer_sulawesi',
                'email' => 'sulawesi_customer@azhar.com',
                'role' => 'customer',
            ],
            [
                'name' => 'Doni Setiawan',
                'username' => 'admin_operasional',
                'email' => 'operasional@azhar.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Fitri Amalia',
                'username' => 'customer_papua',
                'email' => 'papua_customer@azhar.com',
                'role' => 'customer',
            ],
            [
                'name' => 'Joko Prabowo',
                'username' => 'admin_hrd',
                'email' => 'hrd@azhar.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Dewi Sartika',
                'username' => 'customer_bali',
                'email' => 'bali_customer@azhar.com',
                'role' => 'customer',
            ],
        ];

        foreach ($data as $user) {
            $address = '';

            if ($user['role'] == 'customer') {
                $address = $faker->address;
            }

            DB::table('users')->updateOrInsert([
                'name' => $user['name'],
                'username' => $user['username'],
                'address' => $address,
                'email' => $user['email'],
                'role' => $user['role'],
                'phone' => $this->generateRandomPhoneNumber(),
                'password' => Hash::make('password'),
                'created_at' => $this->generateRandomDate(),
                'updated_at' => now(),
            ]);
        }
    }

    private function generateRandomDate()
    {

        $start = Carbon::now()->subYears(1);
        $end = Carbon::now();

        return Carbon::createFromTimestamp(rand($start->timestamp, $end->timestamp));
    }

    private function generateRandomPhoneNumber()
    {
        // Membuat nomor telepon dengan awalan 08
        $phoneNumber = '08';

        // Menghasilkan 10 digit angka acak
        for ($i = 0; $i < 10; $i++) {
            $phoneNumber .= rand(0, 9);
        }

        return $phoneNumber;
    }
}
