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
        $data = parent::toArray($request);
        $data['id'] = $this->id;

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
            $data['deliveryCosts'] = new ProductDeliveryCostCollection($this->deliveryCosts);
        }

        if ($this->relationLoaded('subcategory')) {
            $data['subcategory'] = $this->subcategory
                ? $this->subcategory->toArray()
                : null;
        }

        return $data;
    }
}
