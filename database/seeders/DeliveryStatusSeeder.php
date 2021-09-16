<?php

namespace Database\Seeders;

use App\Models\DeliveryStatus;
use Illuminate\Database\Seeder;

class DeliveryStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (DeliveryStatus::getList(true) as $id => $name) {
            DeliveryStatus::firstOrCreate(compact('id', 'name'));
        }
    }
}
