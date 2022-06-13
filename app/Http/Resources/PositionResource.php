<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'asset' => new AssetResource($this->asset),
            'strategy' => $this->strategy,
            'side' => $this->side,
            'type' => $this->type,
            'price' => $this->price,
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'max_stop' => $this->max_stop,
        ];
    }
}
