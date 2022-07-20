<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HiloResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'asset_id' => $this->asset_id,
            'length' => $this->length,
            'granularity' => $this->granularity,
            'last_check_at' => $this->last_check_at,
            'last_action' => $this->last_action,
        ];
    }
}
