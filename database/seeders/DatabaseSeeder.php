<?php

namespace Database\Seeders;

use App\Models\AgentSpendingVolume;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountrySeeder::class,
            ProductCategorySeeder::class,
            DesignationSeeder::class,
            GeopoliticalZoneSeeder::class,
            StateSeeder::class,
            ModelPermissionsSeeder::class,
            DeliveryStatusSeeder::class,
            AppSettingSeeder::class,
            PageSeeder::class,
            AgentSpendingVolumeSeeder::class,
            HelpPageSeeder::class,
        ]);

        \App\Models\User::factory([
            'email' => 'admin@farmerxpress.com',
            'password' => 'admin111',
        ])->isSuperAdmin()->create();
    }
}
