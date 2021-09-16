<?php

namespace Database\Seeders;

use App\Models\ShippingStatus;
use Illuminate\Database\Seeder;

class ShippingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (ShippingStatus::getList(true) as $id => $name) {
            ShippingStatus::firstOrCreate(compact('id', 'name'));
        }
    }
}
