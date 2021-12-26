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
            'hotline' => '+2348032345678',
            'email' => 'info@fructuo.com',
            'address' => '1, FarmerXpress Close',
            AppSetting::VENDOR_STORAGE_LIMIT => '25',
            AppSetting::CANCEL_ORDER_HOURS => '3',
            'social_facebook' => 'farmerxpress',
            'social_twitter' => '@farmerxpress',
            'social_youtube' => 'farmerxpress',
            'social_linkedin' => 'https://linkedin.com/in/farmerxpress',
            'social_instagram' => 'farmerxpress',
            'social_whatsapp' => 'farmerxpress',
            AppSetting::REFERENCE_NUMBER_PREFIX => 'FTA', 
        ]], ['id']);
    }
}
