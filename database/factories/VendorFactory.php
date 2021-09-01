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
        $bizName =  $this->faker->company();
        return [
            'shop_name' => $bizName,
            'business_name' => $bizName,
            'tin' => strtoupper(Str::random(8)),
            'is_vat_registered' => (bool) mt_rand(0, 1),
            'slug' => Str::slug($bizName),
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
            Product::factory()->count($count)->state(function(array $attributes, Vendor $vendor) {
                return [
                    'packaging_id' => $vendor->packagings()->inRandomOrder()->first()->id,
                    'category_id' => $vendor->productCategories()->inRandomOrder()->first()->id,
                ];
            }),
            'products'
        );
    }
}
