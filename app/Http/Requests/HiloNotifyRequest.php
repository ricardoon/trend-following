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
            'hilo' => 'required|numeric',
        ];
    }
}
