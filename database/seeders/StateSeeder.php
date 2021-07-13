<?php

namespace Database\Seeders;

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
            State::create($stateData);
        }
    }

    private function _getStatesData()
    {
        return $states = array(
            array('state_name' => 'Abia', 'state_code' => 'ABI', 'capital' => 'Umuahia'),
            array('state_name' => 'Adamawa', 'state_code' => 'ADM', 'capital' => 'Yola'),
            array('state_name' => 'Akwa Ibom', 'state_code' => 'AKI', 'capital' => 'Uyo'),
            array('state_name' => 'Anambra', 'state_code' => 'ANB', 'capital' => 'Awka'),
            array('state_name' => 'Bauchi', 'state_code' => 'BAU', 'capital' => 'Bauchi'),
            array('state_name' => 'Bayelsa', 'state_code' => 'BYS', 'capital' => 'Yenogoa'),
            array('state_name' => 'Benue', 'state_code' => 'BNU', 'capital' => 'Makurdi'),
            array('state_name' => 'Borno', 'state_code' => 'BON', 'capital' => 'Maiduguri'),
            array('state_name' => 'Cross River', 'state_code' => 'CRS', 'capital' => 'Calabar'),
            array('state_name' => 'Delta', 'state_code' => 'DLT', 'capital' => 'Asaba'),
            array('state_name' => 'Ebonyi', 'state_code' => 'EBN', 'capital' => 'Abakaliki'),
            array('state_name' => 'Edo', 'state_code' => 'EDO', 'capital' => 'Benin City'),
            array('state_name' => 'Ekiti', 'state_code' => 'EKI', 'capital' => 'Ado-Ekiti'),
            array('state_name' => 'Enugu', 'state_code' => 'ENU', 'capital' => 'Enugu'),
            array('state_name' => 'FCT', 'state_code' => 'FCT', 'capital' => 'Abuja'),
            array('state_name' => 'Gombe', 'state_code' => 'GOB', 'capital' => 'Gombe'),
            array('state_name' => 'Imo', 'state_code' => 'IMO', 'capital' => 'Owerri'),
            array('state_name' => 'Jigawa', 'state_code' => 'JIG', 'capital' => 'Dutse'),
            array('state_name' => 'Kaduna', 'state_code' => 'KAD', 'capital' => 'Kaduna'),
            array('state_name' => 'Kano', 'state_code' => 'KAN', 'capital' => 'Kano'),
            array('state_name' => 'Katsina', 'state_code' => 'KAT', 'capital' => 'Katsina'),
            array('state_name' => 'Kebbi', 'state_code' => 'KEB', 'capital' => 'Birnin Kebbi'),
            array('state_name' => 'Kogi', 'state_code' => 'KOG', 'capital' => 'Lokoja'),
            array('state_name' => 'Kwara', 'state_code' => 'KWA', 'capital' => 'Ilorin'),
            array('state_name' => 'Lagos', 'state_code' => 'LGA', 'capital' => 'Ikeja'),
            array('state_name' => 'Nassarawa', 'state_code' => 'NAS', 'capital' => 'Lafia'),
            array('state_name' => 'Niger', 'state_code' => 'NIG', 'capital' => 'Minna'),
            array('state_name' => 'Ogun', 'state_code' => 'OGN', 'capital' => 'Abeokuta'),
            array('state_name' => 'Ondo', 'state_code' => 'OND', 'capital' => 'Akure'),
            array('state_name' => 'Osun', 'state_code' => 'OSN', 'capital' => 'Oshogbo'),
            array('state_name' => 'Oyo', 'state_code' => 'OYO', 'capital' => 'Ibadan'),
            array('state_name' => 'Plateau', 'state_code' => 'PLT', 'capital' => 'Jos'),
            array('state_name' => 'Rivers', 'state_code' => 'RIV', 'capital' => 'Port Harcourt'),
            array('state_name' => 'Sokoto', 'state_code' => 'SOK', 'capital' => 'Sokoto'),
            array('state_name' => 'Taraba', 'state_code' => 'TAR', 'capital' => 'Jalingo'),
            array('state_name' => 'Yobe', 'state_code' => 'YOB', 'capital' => 'Damaturu'),
            array('state_name' => 'Zamfara', 'state_code' => 'ZAM', 'capital' => 'Gusau'),
        );
    }
}
