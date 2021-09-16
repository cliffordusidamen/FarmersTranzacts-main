<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppSetting::upsert([[
            'id' => 1,
            'hotline' => '(234) 0801 234 5678',
            'email' => 'info@fructuo.com',
            'address' => '1, FarmerXpress Close',
            'vendor_storage_limit' => '25',
            'social_facebook' => 'farmerxpress',
            'social_twitter' => '@farmerxpress',
            'social_youtube' => 'farmerxpress',
            'social_linkedin' => 'https://linkedin.com/in/farmerxpress',
            'social_instagram' => 'farmerxpress',
            'social_whatsapp' => 'farmerxpress',
        ]], ['id']);
    }
}
