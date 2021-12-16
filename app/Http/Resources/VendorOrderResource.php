<?php

namespace App\Http\Resources;

use App\Models\DeliveryStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $vendorOrder = [
            'id'                  => $this->id,
            'reference_number'    => $this->reference_number,
            'user_id'             => $this->user_id,
            'order_id'            => $this->order_id,
            'vendor_id'           => $this->vendor_id,
            'items_amount'        => (float) $this->items_amount,
            'delivery_amount'     => (float) $this->delivery_amount,
            'delivery_days'       => $this->delivery_days,
            'transit_note'       => $this->transit_note,
            'total_amount'        => (float) $this->total_amount,
            'delivery_status_id'  => $this->delivery_status_id,
            'status'              => DeliveryStatus::getStatusName($this->delivery_status_id),
            'created_at'          => carbon_ng($this->created_at),
            'transitable' => $this->transitable,
            'deliverable' => $this->deliverable,
            'created_at' => $this->created_at,
        ];

        if ($this->whenAppended('deliverable')) {
            $vendorOrder['deliverable'] = $this->deliverable;
        }

        if ($this->whenAppended('transitable')) {
            $vendorOrder['transitable'] = $this->transitable;
        }

        if ($this->whenLoaded('user')) {
            $vendorOrder['user'] = new UserResource($this->user);
        }

        if ($this->whenLoaded('vendor')) {
            $vendorOrder['vendor'] = $this->vendor;
        }

        if ($this->whenLoaded('order')) {
            $vendorOrder['order'] = $this->order;
        }

        if ($this->whenLoaded('items')) {
            $vendorOrder['items'] = new OrderItemCollection($this->items);
        }

        return $vendorOrder;
    }
}
