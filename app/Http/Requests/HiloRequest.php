<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HiloRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->method() === 'PATCH') {
            $rules = [
                'length' => 'sometimes|integer',
                'last_check_at' => 'sometimes|date',
            ];
        } else {
            $rules = [
                'length' => 'required|integer',
                'granularity' => 'required|string|in:1m,5m,15m,30m,1h,2h,4h,6h,8h,12h,1d,3d,1w,1M',
                'last_check_at' => 'required|date',
            ];
        }

        return $rules;
    }
}
