<?php

namespace App\Http\Requests\Advertisement;

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
            'title'                     => 'required|string',
            'home_id'                   => 'required|exists:homes,id',
            'status'                    => 'required|in:0,1',
            'description'               => 'nullable|string|max:10000',
            'is_block'                  => 'required|in:0,1',
            // 'message_center_enabled'    => 'in:0,1',
            // 'is_sold'                   => 'in:0,1',
            // 'phone_text_enabled'        => 'in:0,1',
            'is_sponsored'              => 'nullable|in:0,1',
            'sponsored_till'            => 'nullable|required_if:is_sponsored,1|after_or_equal:today',
        ];
    }
}
