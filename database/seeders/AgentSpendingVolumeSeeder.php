<?php

namespace Database\Seeders;

use App\Models\AgentSpendingVolume;
use Illuminate\Database\Seeder;

class AgentSpendingVolumeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AgentSpendingVolume::upsert($this->_getData(), ['commission_percentage']);
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
            'commission_percentage' => 1,
        ];

        $data[] = [
            'minimum_amount' => 500_001,
            'maximum_amount' => 1_000_000,
            'commission_percentage' => 1.2,
        ];

        $data[] = [
            'minimum_amount' => 1_000_001,
            'maximum_amount' => null,
            'commission_percentage' => 2.2,
        ];

        return $data;
    }
}
