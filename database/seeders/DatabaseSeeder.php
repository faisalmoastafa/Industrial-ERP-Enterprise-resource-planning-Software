<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Only seed demo user when APP_DEMO=true (Railway deployment)
        if (env('APP_DEMO') !== 'true') {
            return;
        }

        if (!DB::table('users')->where('email', 'demo@neci-erp.com')->exists()) {
            DB::table('users')->insert([
                'name'              => 'Demo Admin',
                'email'             => 'demo@neci-erp.com',
                'password'          => Hash::make('demo1234'),
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}
