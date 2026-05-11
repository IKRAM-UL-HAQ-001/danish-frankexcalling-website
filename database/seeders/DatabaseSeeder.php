<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'vQapttK1zNq3BELO6MiasA==',
                'email' => 'NsRoqbf7mFkWqpt3PNALmsrJM7Uxa1cbOEu17Ro7caI=',
                'password' => Hash::make('acR9EaNKAAh8PdK0uSAgaA=='),
                'role' => 'admin',
                'status' => 'active',
                'exchange_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}