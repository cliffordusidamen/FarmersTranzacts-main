<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Role::getList(true) as $id => $name) {
            Role::firstOrCreate(compact('id', 'name'));
        }
    }
}
