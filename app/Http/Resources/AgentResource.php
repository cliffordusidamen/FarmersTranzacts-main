<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        if ($this->relationLoaded('spendingVolume')) {
            $data['spendingVolume'] = new SpendingVolumeResource($this->spendingVolume);
        }

        if ($this->relationLoaded('productCategories')) {
            $data['productCategories'] = new ProductCategoryCollection($this->productCategories);
        }

        return $data;
    }
}
