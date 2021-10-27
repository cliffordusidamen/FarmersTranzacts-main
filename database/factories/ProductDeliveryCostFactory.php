<?php

namespace Database\Factories;

use App\Models\ProductDeliveryCost;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductDeliveryCostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductDeliveryCost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $testNumber = mt_rand(3, 50);
        $central = ($testNumber % 15) == 0;
        $east = ($testNumber % 5) == 0;
        $west = ($testNumber % 3) == 0;
        $even = ($testNumber % 2) == 0;

        return [
            'minimum_quantity' => 1,
            'maximum_quantity' => 10,

            'north_central_cost' => $central ? mt_rand(500, 10000) : null,
            'north_central_delivery_days' => $central ? mt_rand(1, 14) : null,

            'north_east_cost' => ($east || $even) ? mt_rand(500, 10000) : null,
            'north_east_delivery_days' => $east ? mt_rand(1, 14) : null,

            'north_west_cost' => ($west || $even) ? mt_rand(500, 10000) : null,
            'north_west_delivery_days' => $west ? mt_rand(1, 14) : null,

            'south_east_cost' => ($east || $even) ? mt_rand(500, 10000) : null,
            'south_east_delivery_days' => $east ? mt_rand(1, 14) : null,

            'south_west_cost' => ($west || $even) ? mt_rand(500, 10000) : null,
            'south_west_delivery_days' => $west ? mt_rand(1, 14) : null,

            'south_south_cost' => $central ? mt_rand(500, 10000) : null,
            'south_south_delivery_days' => $central ? mt_rand(1, 14) : null,
        ];
    }
}
