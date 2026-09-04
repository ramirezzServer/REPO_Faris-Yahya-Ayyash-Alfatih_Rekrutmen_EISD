<?php

namespace Database\Seeders;

use App\Models\Kawasan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $kawasan = Kawasan::pluck('id', 'kode_kawasan');

        $users = [
            [
                'name' => 'Administrator SINERKA',
                'email' => 'admin@sinerka.test',
                'no_hp' => '081200000001',
                'role' => 'admin',
                'kawasan_id' => null,
            ],
            [
                'name' => 'Operator Cihapit',
                'email' => 'operator1@sinerka.test',
                'no_hp' => '081200000011',
                'role' => 'operator',
                'kawasan_id' => $kawasan['KWS-001'],
            ],
            [
                'name' => 'Operator Sukamiskin',
                'email' => 'operator2@sinerka.test',
                'no_hp' => '081200000012',
                'role' => 'operator',
                'kawasan_id' => $kawasan['KWS-002'],
            ],
            [
                'name' => 'Operator Antapani Tengah',
                'email' => 'operator3@sinerka.test',
                'no_hp' => '081200000013',
                'role' => 'operator',
                'kawasan_id' => $kawasan['KWS-003'],
            ],
            [
                'name' => 'Warga Cihapit',
                'email' => 'warga1@sinerka.test',
                'no_hp' => '081200000021',
                'role' => 'warga',
                'kawasan_id' => $kawasan['KWS-001'],
            ],
            [
                'name' => 'Warga Turangga',
                'email' => 'warga2@sinerka.test',
                'no_hp' => '081200000022',
                'role' => 'warga',
                'kawasan_id' => $kawasan['KWS-004'],
            ],
        ];

        foreach ($users as $user) {
            User::create([
                ...$user,
                'password' => Hash::make('password'),
            ]);
        }
    }
}
