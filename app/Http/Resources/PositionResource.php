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
            'started_at' => $this->start_datetime,
            'ended_at' => $this->end_datetime,
            'max_stop' => $this->max_stop,
        ];
    }
}
