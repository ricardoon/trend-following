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
            'category' => $this->category,
            'precision' => $this->precision,
            'hilo' => new HiloResource($this->hilo),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
