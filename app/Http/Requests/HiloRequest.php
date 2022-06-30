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
        $rules = [
            'length' => 'required|integer',
            'last_check_at' => 'required|date',
        ];

        return $rules;
    }
}
