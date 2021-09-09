<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'path' => Storage::disk()->url($this->path),
            'is_featured' => (bool) $this->is_featured,
            'size' => $this->size ?? 0,
        ];
    }
}
