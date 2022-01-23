<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDeliveryCost extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     * 
     * @var array
     */
    protected $fillable = [
        'product_id',
        'minimum_quantity',
        'maximum_quantity',
        'north_central_cost',
        'north_central_delivery_days',
        'north_central_pay_on_delivery',
        'north_east_cost',
        'north_east_delivery_days',
        'north_east_pay_on_delivery',
        'north_west_cost',
        'north_west_delivery_days',
        'north_west_pay_on_delivery',
        'south_east_cost',
        'south_east_delivery_days',
        'south_east_pay_on_delivery',
        'south_west_cost',
        'south_west_delivery_days',
        'south_west_pay_on_delivery',
        'south_south_cost',
        'south_south_delivery_days',
        'south_south_pay_on_delivery',
    ];

    /**
     * Relationship: Belongs to product
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Default validation rules
     * 
     * @return array
     */
    public static function getValidationRules(string $prefix = ''): array
    {
        $rules = [
            "{$prefix}minimum_quantity" => 'required|numeric|min:1|max:20',
            "{$prefix}maximum_quantity" => "required|numeric|min:1|max:20|gt:{$prefix}minimum_quantity",

            "{$prefix}north_central_cost" => [
                'sometimes',
                'nullable',
                // "required_without:{$prefix}north_east_cost,{$prefix}north_west_cost,{$prefix}south_east_cost,{$prefix}south_west_cost,{$prefix}south_south_cost",
            ],
            "{$prefix}north_central_delivery_days" => 'required_with:north_central_cost',

            "{$prefix}north_east_cost" => [
                'sometimes',
                'nullable',
                // "required_without:{$prefix}north_central_cost,{$prefix}north_west_cost,{$prefix}south_east_cost,{$prefix}south_west_cost,{$prefix}south_south_cost",
            ],
            "{$prefix}north_east_delivery_days" => 'required_with:north_east_cost',

            "{$prefix}north_west_cost" => [
                'sometimes',
                'nullable',
                // "required_without:{$prefix}north_central_cost,{$prefix}north_east_cost,{$prefix}south_east_cost,{$prefix}south_west_cost,{$prefix}south_south_cost",
            ],
            "{$prefix}north_west_delivery_days" => 'required_with:north_west_cost',

            "{$prefix}south_east_cost" => [
                'sometimes',
                'nullable',
                // "required_without:{$prefix}north_central_cost,{$prefix}north_east_cost,{$prefix}north_west_cost,{$prefix}south_west_cost,{$prefix}south_south_cost",
            ],
            "{$prefix}south_east_delivery_days" => 'required_with:south_east_cost',

            "{$prefix}south_west_cost" => [
                'sometimes',
                'nullable',
                // "required_without:{$prefix}north_central_cost,{$prefix}north_east_cost,{$prefix}north_west_cost,{$prefix}south_east_cost,{$prefix}south_south_cost",
            ],
            "{$prefix}south_west_delivery_days" => 'required_with:south_west_cost',

            "{$prefix}south_south_cost" => [
                'sometimes',
                'nullable',
                // "required_without:{$prefix}north_central_cost,{$prefix}north_east_cost,{$prefix}north_west_cost,{$prefix}south_east_cost,{$prefix}south_west_cost",
            ],
            "{$prefix}south_south_delivery_days" => 'required_with:south_south_cost',
        ];

        return $rules;
    }
}
