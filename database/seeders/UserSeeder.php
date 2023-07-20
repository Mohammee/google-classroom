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
     */
    public function run(): void
    {
        // Query Builder

        DB::table('users')->insert([
            'name' => 'Mohammad abusultan',
            'email' => 'moh@gmail.com',
            'password' => Hash::make('password'),//md5, sha, rsa
        ]);
    }
}
