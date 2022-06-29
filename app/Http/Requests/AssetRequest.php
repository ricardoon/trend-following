<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
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
                'name' => 'sometimes|string|max:255',
                'code' => 'sometimes|string|max:255',
                'yahoo_code' => 'sometimes|string|max:255',
                'category' => 'sometimes|string|max:255',
                'price_precision' => 'sometimes|integer',
                'quantity_precision' => 'sometimes|integer',
                'quote_precision' => 'sometimes|integer',
            ];
        } else {
            $bulk = strpos($this->path(), 'bulk') !== false ? '*.' : '';
            return [
                $bulk . 'name' => 'required|string|max:255',
                $bulk . 'code' => 'required|string|max:255',
                $bulk . 'yahoo_code' => 'required|string|max:255',
                $bulk . 'category' => 'required|string|max:255|in:crypto,option,stock',
                $bulk . 'price_precision' => 'sometimes|integer',
                $bulk . 'quantity_precision' => 'sometimes|integer',
                $bulk . 'quote_precision' => 'sometimes|integer',
            ];
        }
    }
}
