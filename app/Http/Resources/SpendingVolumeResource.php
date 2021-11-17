<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpendingVolumeResource extends JsonResource
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
        $data['minimum_amount'] = (int) $this->minimum_amount;
        $data['maximum_amount'] = is_null($this->maximum_amount)
            ? null : (int) $this->maximum_amount;

        return $data;
    }
}
