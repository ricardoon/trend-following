<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PositionRequest extends FormRequest
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
                'started_at' => 'sometimes|date',
                'ended_at' => 'sometimes|date',
                'amount' => 'sometimes|numeric',
                'max_stop' => 'sometimes|numeric',
            ];
        } else {
            return [
                'asset_id' => 'required|integer',
                'strategy' => 'required|string|max:255|in:hilo',
                'amount' => 'required|numeric',
            ];
        }
    }
}
