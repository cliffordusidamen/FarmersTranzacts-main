<?php

namespace Database\Seeders;

use App\Models\GeopoliticalZone;
use Illuminate\Database\Seeder;

class GeopoliticalZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GeopoliticalZone::upsert($this->_getData(), ['id']);
    }

    /**
     * Get list of data to be seeded
     * 
     * @return array
     */
    public function _getData(): array
    {
        $data = [];
        foreach(GeopoliticalZone::getList(true) as $id => $name) {
            $data[] = compact('id', 'name');
        }

        return $data;
    }
}
