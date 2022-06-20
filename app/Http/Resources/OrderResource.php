<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'external_id' => $this->external_id,
            'binance_client_order_id' => $this->binance_client_order_id,
            'side' => $this->side,
            'entry_price' => $this->entry_price,
            'quantity' => $this->quantity,
            'size' => $this->size,
            'exit_price' => $this->exit_price,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
