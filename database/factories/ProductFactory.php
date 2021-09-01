<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $price = mt_rand(2000, 50000);
        return [
            'name' => $this->faker->company(),
            'description' => $this->faker->sentences(5, true),
            'price' => $price,
            'old_price' => floor($price * 0.8),
            'submitted_at' => carbon()->format('Y-m-d H:i:s'),
            'approval_status' => Product::STATUS_APPROVED,
        ];
    }
}
