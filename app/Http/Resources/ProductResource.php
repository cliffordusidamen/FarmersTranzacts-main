<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'packaging_id' => $this->packaging_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'specifications' => $this->specifications,
            'key_features' => $this->key_features,
            'price' => $this->price,
            'old_price' => $this->old_price,
            'in_stock' => $this->in_stock,
            'disapproval_reason' => $this->disapproval_reason,
            'approval_status' => $this->approval_status,
            'submitted_at' => $this->submitted_at,
            'approved_at' => $this->approved_at,
            'feature_image' => $this->feature_image,
            'maximum_order_quantity' => $this->maximum_order_quantity,
        ];

        if ($this->relationLoaded('packaging')) {
            $data['packaging'] = $this->packaging->toArray();
        }
        
        if ($this->relationLoaded('vendor')) {
            $data['vendor'] = $this->vendor->toArray();
        }
        
        if ($this->relationLoaded('images')) {
            $data['images'] = new ProductImageCollection($this->images);
            $data['images_count'] = $this->images->count();
        } else {
            $data['images_count'] = $this->images_count ?? 0;
        }
        
        if ($this->relationLoaded('category')) {
            $data['category'] = $this->category->toArray();
        }

        if ($this->relationLoaded('deliveryCosts')) {
            $deliveryCosts = [];
            $this->deliveryCosts->each(function ($deliveryCost, $i) use (&$deliveryCosts) {
                $deliveryCosts[] = [
                    'id' =>$deliveryCost->id,
                    'product_id' =>$deliveryCost->product_id,
                    'minimum_quantity' =>$deliveryCost->minimum_quantity,
                    'maximum_quantity' =>$deliveryCost->maximum_quantity,
                    'north_central_cost' =>$deliveryCost->north_central_cost,
                    'north_central_delivery_days' =>$deliveryCost->north_central_delivery_days,
                    'north_east_cost' =>$deliveryCost->north_east_cost,
                    'north_east_delivery_days' =>$deliveryCost->north_east_delivery_days,
                    'north_west_cost' =>$deliveryCost->north_west_cost,
                    'north_west_delivery_days' =>$deliveryCost->north_west_delivery_days,
                    'south_east_cost' =>$deliveryCost->south_east_cost,
                    'south_east_delivery_days' =>$deliveryCost->south_east_delivery_days,
                    'south_west_cost' =>$deliveryCost->south_west_cost,
                    'south_west_delivery_days' =>$deliveryCost->south_west_delivery_days,
                    'south_south_cost' =>$deliveryCost->south_south_cost,
                    'south_south_delivery_days' =>$deliveryCost->south_south_delivery_days,
                ];
            });

            $data['deliveryCosts'] = $deliveryCosts;
        }

        if ($this->relationLoaded('subcategory')) {
            $data['subcategory'] = $this->subcategory
                ? $this->subcategory->toArray()
                : null;
        }

        return $data;
    }
}
