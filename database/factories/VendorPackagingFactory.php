<?php

namespace Database\Factories;

use App\Models\VendorPackaging;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorPackagingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VendorPackaging::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $packages = [
            'litre',
            'kilogram',
            'gram',
            'cl',
            'box',
            'carton',
            'cup',
            'ton',
            'bowl',
            'wrap',
            'pack',
            'bottle',
            'yard',
            'roll',
        ];
        return [
            'name' => $packages[mt_rand(0, 13)],
            'description' => $this->faker->sentence(),
        ];
    }
}
