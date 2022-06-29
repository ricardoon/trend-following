<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PositionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $method = $this->method();
        if ($method === 'PATCH') {
            return [
                'started_at' => 'sometimes|date',
                'ended_at' => 'sometimes|date',
                'amount' => 'sometimes|numeric',
                'max_stop' => 'sometimes|numeric',
            ];
        } else {
            return [
                'asset_id' => 'required|integer',
                'strategy' => 'required|string|max:255|in:hilo',
                'granularity' => 'required|string|max:255|in:1m,5m,15m,30m,1h,2h,4h,6h,8h,12h,1d,3d,1w,1M',
                'amount' => 'required|numeric',
            ];
        }
    }
}
