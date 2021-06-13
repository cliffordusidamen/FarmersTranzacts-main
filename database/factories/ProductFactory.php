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
        $category = ProductCategory::all()->random(1)->first();
        $price = mt_rand(2000, 50000);
        return [
            'category_id' => $category->id,
            'subcategory_id' => $category->subcategories->count() > 0
                ? $category->subcategories->random(1)->first()->id
                : null,
            'name' => $this->faker->company(),
            'description' => $this->faker->sentences(5, true),
            'price' => $price,
            'old_price' => floor($price * 0.8),
            'in_stock' => mt_rand(0, 500),
        ];
    }
}
