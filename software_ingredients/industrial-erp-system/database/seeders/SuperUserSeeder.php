<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // CREATE FUTURE DEVELOPER ACCOUNT WITH UPDATED CREDENTIALS
        $user = User::create([
            'name' => 'superadmin',
            'email' => 'superadmin@erp.com',
            'password' => Hash::make('superadmin'),
            'is_active' => 1
        ]);

        // VERIFY SYSTEM ROLE PERSISTENCE 
        // Note: Using firstOrCreate avoids duplication errors if running multiple times
        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin'
        ]);

        $user->assignRole($superAdmin);
    }
}