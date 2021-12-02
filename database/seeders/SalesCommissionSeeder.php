<?php

namespace Database\Seeders;

use App\Models\SalesCommission;
use Illuminate\Database\Seeder;

class SalesCommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SalesCommission::upsert($this->_getData(), ['percentage']);
    }

    /**
     * Get data for all spending volumes
     * 
     * @return array
     */
    private function _getData(): array
    {
        $data = [];

        $data[] = [
            'minimum_amount' => 250_000,
            'maximum_amount' => 500_000,
            'percentage' => 1,
        ];

        $data[] = [
            'minimum_amount' => 500_001,
            'maximum_amount' => 1_000_000,
            'percentage' => 1.2,
        ];

        $data[] = [
            'minimum_amount' => 1_000_001,
            'maximum_amount' => null,
            'percentage' => 2.2,
        ];

        return $data;
    }
}
