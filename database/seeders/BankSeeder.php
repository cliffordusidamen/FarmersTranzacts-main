<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->_getBanks() as $bankName) {
            Bank::firstOrCreate([
                'name' => $bankName,
            ]);
        }
    }

    protected function _getBanks()
    {
        return [
            'Access Bank',
            'CitiBank Nigeria Limited',
            'EcoBank Nigeria PLC',
            'Fidelity Bank Nigeria PLC',
            'First Bank of Nigeria PLC',
            'First City Monument Bank PLC',
            'Globus Bank Limited',
            'Guaranty Trust Bank PLC',
            'Heritage Bank Plc',
            'Jaiz Bank Plc',
            'Keystone Bank',
            'Polaris Bank Limited',
            'Providus Bank Limited',
            'Stanbic IBTC Bank PLC',
            'Standard Chartered Bank Limited',
            'Sterling Bank PLC',
            'SunTrust Bank Nigeria Limited',
            'TAJBank Limited',
            'Titan Trust Bank Limited',
            'Union Bank of Nigeria PLC',
            'United Bank for Africa',
            'Unity Bank PLC',
            'Wema Bank PLC',
            'Zenith Bank PLC',
        ];
    }
}
