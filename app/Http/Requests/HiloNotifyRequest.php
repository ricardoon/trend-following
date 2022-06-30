<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HiloNotifyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'action' => 'required|string|in:buy,sell',
            'hilo_price' => 'required|numeric',
            'granularity' => 'required|string|in:1m,5m,15m,30m,1h,2h,4h,6h,8h,12h,1d,3d,1w,1M',
        ];
    }
}
