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
            'name' => join(' ', $this->faker->words(2)),
            'description' => $this->faker->sentence(),
        ];
    }
}
