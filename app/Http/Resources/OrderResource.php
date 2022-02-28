<?php

namespace App\Http\Resources;

use App\Models\DeliveryStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
        $data['status']  = DeliveryStatus::getStatusName($this->delivery_status_id);
        $data['status_class']  = $this->status_class;

        if ($this->whenLoaded('vendorOrders')) {
            $data['vendorOrders'] = new VendorOrderCollection($this->vendorOrders);
        }

        if ($this->whenLoaded('user')) {
            $data['user'] = new UserResource($this->user);
        }

        if ($this->whenLoaded('address')) {
            $data['address'] = new AddressResource($this->address);
        }

        if ($this->whenAppended('total_quantity')) {
            $vendorOrder['total_quantity'] = $this->total_quantity;
        }

        return $data;
    }
}
