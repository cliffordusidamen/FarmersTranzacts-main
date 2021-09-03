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
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
        ];
    }
}
