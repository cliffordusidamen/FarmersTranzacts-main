<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Country;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Vendor;
use App\Models\VendorPackaging;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VendorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vendor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shop_name' => $this->faker->company(),
            'business_name' => $this->faker->company(),
            'tin' => strtoupper(Str::random(8)),
            'is_vat_registered' => (bool) mt_rand(0, 1),
            // 'slug' => Str::slug($this->faker->company()),
            'phone' => $this->faker->phoneNumber(),
            'logo_image_path' => null,
            'ownership_type' => mt_rand(0, 1)
                ? Vendor::OWNERSHIP_COMPANY : Vendor::OWNERSHIP_INDIVIDUAL,
            'approval_status' => Vendor::STATUS_APPROVED,
            'reject_reason' => null,
            'entity_country_id' => Country::NIGERIA,
        ];
    }
    
    /**
     * Setup related data
     * 
     * @param  int  $productCount
     * @return static
     */
    public function withFullSetup(int $productCount = 1)
    {
        return $this->withAddress()
            ->withPackagings()
            ->withProductCategories()
            ->withProducts($productCount);
    }

    /**
     * Create an address for the vendor
     * 
     * @return $this
     */
    public function withAddress()
    {
        return $this->has(
            Address::factory(),
            'address'
        );
    }

    /**
     * Attach an product categories to the vendor
     * 
     * @return $this
     */
    public function withProductCategories()
    {
        $productCategories = ProductCategory::inRandomOrder()->take(mt_rand(1, 3))->get();
        return $this->hasAttached(
            $productCategories ,
            [],
            'productCategories'
        );
    }

    /**
     * Create packagings for the vendor
     * 
     * @param  int  $count
     * @return $this
     */
    public function withPackagings(int $count = 1)
    {
        return $this->has(
            VendorPackaging::factory()->count($count),
            'packagings'
        );
    }

    /**
     * Create products for the vendor
     * 
     * @param  int  $count
     * @param  array  $productCategoryIds
     * @param  array  $packagingIds
     * @return $this
     */
    public function withProducts(int $count = 1)
    {
        return $this->has(
            Product::factory()
            ->withDeliveryCosts()
            ->state(function(array $attributes, Vendor $vendor) {
                $category = $vendor->productCategories()->inRandomOrder()->first();
                $hasSubcategories = $category->subcategories()->count();
                return [
                    'packaging_id' => $vendor->packagings()->inRandomOrder()->first()->id,
                    'category_id' => $category->id,
                    'subcategory_id' => !$hasSubcategories
                        ?  null
                        : $category->subcategories()->inRandomOrder()->first()->id,
                ];
            })
            ->count($count),
            'products'
        );
    }
}
