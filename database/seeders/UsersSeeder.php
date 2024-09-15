<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            DB::table('users')->insert([
                'email' => 'su@gmail.com',
                'password' => bcrypt('qweqwe--')
            ]);

            DB::table('users')->insert([
                'email' => 'qwe@gmail.com',
                'password' => bcrypt('qweqwe--')
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());
        }
    }
}
