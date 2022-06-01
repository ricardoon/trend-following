<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
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
                'name' => 'sometimes|required|string|max:255',
                'code' => 'sometimes|required|string|max:255',
                'category' => 'sometimes|required|string|max:255',
            ];
        } else {
            $bulk = strpos($this->path(), 'bulk') !== false ? '*.' : '';
            return [
                $bulk . 'name' => 'required|string|max:255',
                $bulk . 'code' => 'required|string|max:255',
                $bulk . 'category' => 'required|string|max:255|in:crypto,option,stock',
            ];
        }
    }
}
