<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'yahoo_code' => $this->yahoo_code,
            'category' => $this->category,
            'image' => asset('storage/' . $this->image),
            'price_precision' => $this->price_precision,
            'quantity_precision' => $this->quantity_precision,
            'quote_precision' => $this->quote_precision,
            'hilos' => HiloResource::collection($this->hilos),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
