<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Currency\Database\Seeders\CurrencyDatabaseSeeder;
use Modules\Product\Database\Seeders\ProductDatabaseSeeder;
use Modules\Setting\Database\Seeders\SettingDatabaseSeeder;
use Modules\User\Database\Seeders\PermissionsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // =========================================================================
        // STEP 1: INITIALIZE SECURITY CORE PERMISSION SCHEMAS
        // =========================================================================
        // This triggers the User Module's table seeder file to write the permission keys.
        // NOTE: Make sure to add 'access_backup' and 'access_restore' inside that 
        // PermissionsTableSeeder.php file array so they generate first!
        $this->call(PermissionsTableSeeder::class);

        // =========================================================================
        // STEP 2: BUILD DEVELOPER AND ADMINISTRATIVE USER ACCOUNTS
        // =========================================================================
        // This builds your master profile role records right after the permission maps exist.
        // Matches your updated SuperUserSeeder config details.
        $this->call(SuperUserSeeder::class);

        // =========================================================================
        // STEP 3: CONFIGURE SYSTEM CONSTANTS AND PLATFORM DEFAULTS
        // =========================================================================
        // Seeds system localized baseline structures (Currencies, Setup Layouts, Products)
        $this->call(CurrencyDatabaseSeeder::class);
        $this->call(SettingDatabaseSeeder::class);
        $this->call(ProductDatabaseSeeder::class);
    }
}