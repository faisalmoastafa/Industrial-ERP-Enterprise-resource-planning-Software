<?php

namespace Modules\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Setting\Entities\Setting;

class SettingDatabaseSeeder extends Seeder
{
    public function run()
    {
        Setting::create([
            'company_name' => 'Industrial ERP System',
            'app_title' => 'Industrial ERP',
            'app_tagline' => 'Industrial ERP System',
            'company_email' => 'company@example.com',
            'company_phone' => '000000000000',
            'notification_email' => 'notification@example.com',
            'default_currency_id' => 1,
            'default_currency_position' => 'prefix',
            'footer_text' => 'Industrial ERP System &copy; 2026',
            'company_address' => '',
        ]);
    }
}
