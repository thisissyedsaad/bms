<?php

namespace App\Http\Requests\CategoryPrice;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
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
        return [
            'category_id'       => 'required|exists:categories,id',
            'type'              => 'required|in:basic,standard,premium',
            'is_free'           => 'boolean',
            'price'             => 'required_unless:is_free,true|numeric|gt:0',
            'number_of_days'    => 'nullable|integer|min:1',
            'number_of_photos'  => 'nullable|integer|min:0',
        ];
    }
}
