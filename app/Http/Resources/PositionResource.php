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
            'inital_amount' => $this->initial_amount,
            'leverage' => $this->leverage,
            'amount' => $this->amount,
            'exchange' => $this->exchange,
            'strategy' => $this->strategy,
            'granularity' => $this->granularity,
            'started_at' => $this->start_datetime,
            'ended_at' => $this->end_datetime,
            'max_stop' => $this->max_stop,
        ];
    }
}
