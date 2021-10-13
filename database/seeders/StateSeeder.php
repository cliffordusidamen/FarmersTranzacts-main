<?php

namespace Database\Seeders;

use App\Models\GeopoliticalZone;
use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->_getStatesData() as $stateData)
        {
            State::upsert($stateData, ['id', 'state_name']);
        }
    }

    private function _getStatesData()
    {
        return $states = array(
            array('state_name' => 'Abia', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_EAST, 'state_code' => 'ABI', 'capital' => 'Umuahia'),
            array('state_name' => 'Adamawa', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_EAST, 'state_code' => 'ADM', 'capital' => 'Yola'),
            array('state_name' => 'Akwa Ibom', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_SOUTH, 'state_code' => 'AKI', 'capital' => 'Uyo'),
            array('state_name' => 'Anambra', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_EAST, 'state_code' => 'ANB', 'capital' => 'Awka'),
            array('state_name' => 'Bauchi', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_EAST, 'state_code' => 'BAU', 'capital' => 'Bauchi'),
            array('state_name' => 'Bayelsa', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_SOUTH, 'state_code' => 'BYS', 'capital' => 'Yenogoa'),
            array('state_name' => 'Benue', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_CENTRAL, 'state_code' => 'BNU', 'capital' => 'Makurdi'),
            array('state_name' => 'Borno', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_EAST, 'state_code' => 'BON', 'capital' => 'Maiduguri'),
            array('state_name' => 'Cross River', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_SOUTH, 'state_code' => 'CRS', 'capital' => 'Calabar'),
            array('state_name' => 'Delta', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_SOUTH, 'state_code' => 'DLT', 'capital' => 'Asaba'),
            array('state_name' => 'Ebonyi', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_EAST, 'state_code' => 'EBN', 'capital' => 'Abakaliki'),
            array('state_name' => 'Edo', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_SOUTH, 'state_code' => 'EDO', 'capital' => 'Benin City'),
            array('state_name' => 'Ekiti', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_WEST, 'state_code' => 'EKI', 'capital' => 'Ado-Ekiti'),
            array('state_name' => 'Enugu', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_EAST, 'state_code' => 'ENU', 'capital' => 'Enugu'),
            array('state_name' => 'FCT', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_CENTRAL, 'state_code' => 'FCT', 'capital' => 'Abuja'),
            array('state_name' => 'Gombe', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_EAST, 'state_code' => 'GOB', 'capital' => 'Gombe'),
            array('state_name' => 'Imo', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_EAST, 'state_code' => 'IMO', 'capital' => 'Owerri'),
            array('state_name' => 'Jigawa', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_WEST, 'state_code' => 'JIG', 'capital' => 'Dutse'),
            array('state_name' => 'Kaduna', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_WEST, 'state_code' => 'KAD', 'capital' => 'Kaduna'),
            array('state_name' => 'Kano', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_WEST, 'state_code' => 'KAN', 'capital' => 'Kano'),
            array('state_name' => 'Katsina', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_WEST, 'state_code' => 'KAT', 'capital' => 'Katsina'),
            array('state_name' => 'Kebbi', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_WEST, 'state_code' => 'KEB', 'capital' => 'Birnin Kebbi'),
            array('state_name' => 'Kogi', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_CENTRAL, 'state_code' => 'KOG', 'capital' => 'Lokoja'),
            array('state_name' => 'Kwara', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_CENTRAL, 'state_code' => 'KWA', 'capital' => 'Ilorin'),
            array('state_name' => 'Lagos', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_WEST, 'state_code' => 'LGA', 'capital' => 'Ikeja'),
            array('state_name' => 'Nassarawa', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_CENTRAL, 'state_code' => 'NAS', 'capital' => 'Lafia'),
            array('state_name' => 'Niger', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_CENTRAL, 'state_code' => 'NIG', 'capital' => 'Minna'),
            array('state_name' => 'Ogun', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_WEST, 'state_code' => 'OGN', 'capital' => 'Abeokuta'),
            array('state_name' => 'Ondo', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_WEST, 'state_code' => 'OND', 'capital' => 'Akure'),
            array('state_name' => 'Osun', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_WEST, 'state_code' => 'OSN', 'capital' => 'Oshogbo'),
            array('state_name' => 'Oyo', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_WEST, 'state_code' => 'OYO', 'capital' => 'Ibadan'),
            array('state_name' => 'Plateau', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_CENTRAL, 'state_code' => 'PLT', 'capital' => 'Jos'),
            array('state_name' => 'Rivers', 'geopolitical_zone_id' => GeopoliticalZone::SOUTH_SOUTH, 'state_code' => 'RIV', 'capital' => 'Port Harcourt'),
            array('state_name' => 'Sokoto', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_WEST, 'state_code' => 'SOK', 'capital' => 'Sokoto'),
            array('state_name' => 'Taraba', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_EAST, 'state_code' => 'TAR', 'capital' => 'Jalingo'),
            array('state_name' => 'Yobe', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_EAST, 'state_code' => 'YOB', 'capital' => 'Damaturu'),
            array('state_name' => 'Zamfara', 'geopolitical_zone_id' => GeopoliticalZone::NORTH_WEST, 'state_code' => 'ZAM', 'capital' => 'Gusau'),
        );
    }
}
