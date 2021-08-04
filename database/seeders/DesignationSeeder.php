<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Designation::getList(true) as $id => $name) {
            Designation::firstOrCreate(compact('id', 'name'));
        }
    }
}
