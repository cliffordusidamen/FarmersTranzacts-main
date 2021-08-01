<?php

namespace Database\Factories;

use App\Models\DepartmentSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentSectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DepartmentSection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
        ];
    }
}
