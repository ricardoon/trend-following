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
                'asset_id' => 'required|integer|unique:hilos,asset_id',
                'length' => 'required|integer',
                'last_check_at' => 'required|date',
            ];
        }

        return $rules;
    }
}
