<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->_getCategoriesData() as $categoryData) {
            $category = ProductCategory::firstOrCreate([
                'name' => $categoryData['name'],
            ]);

            if (isset($categoryData['subcategories'])) {
                foreach ($categoryData['subcategories'] as $subcategory) {
                    $category->subcategories()->firstOrCreate($subcategory);
                }
            }
        }
    }

    /**
     * Get data to seed
     * 
     * @return array
     */
    public function _getCategoriesData(): array
    {
        $categories = [
            ['name' => 'Organic'],
            ['name' => 'Livestock'],
            ['name' => 'Poultry'],
            [
                'name' => 'Tools & Equipment',
                'subcategories' => [
                    ['name' => 'Heavy Equipment'],
                    ['name' => 'Irrigation Equipment'],
                    ['name' => 'Livestock Equipment'],
                    ['name' => 'Planting Equipment & Materials'],
                ],
            ],
            ['name' => 'Agro-services'],
            ['name' => 'Verterinary'],
            ['name' => 'Agro-chemicals'],
            ['name' => 'Fertilizers'],
            ['name' => 'Seeds'],
            ['name' => 'Foods'],
        ];
        return $categories;
    }
}
