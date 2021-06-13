<?php

namespace Database\Factories;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductImage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'path' => $this->faker->image(),
        ];
    }

    /**
     * Indicate that the model is a super admin.
     *
     * @param  bool $isFeatured
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function isFeatured(bool $isFeatured = true)
    {
        return $this->state(function (array $attributes) use ($isFeatured) {
            return [
                'is_featured' => $isFeatured,
            ];
        });
    }
}
