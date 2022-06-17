<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $method = $this->method();
        if ($method === 'PATCH') {
            return [
                'ended_at' => 'sometimes|date_format:Y-m-d H:i:s',
                'exit_price' => 'sometimes|numeric',
            ];
        } else {
            return [
                'external_id' => 'required|string|max:255',
                'binance_client_order_id' => 'required|string|max:255',
                'side' => 'required|string|max:255',
                'entry_price' => 'required|numeric',
                'started_at' => 'required|date_format:Y-m-d H:i:s',
            ];
        }
    }
}
