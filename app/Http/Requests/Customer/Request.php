<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'type' => 'required|in:trader,private_seller',
            'name' => 'required|string',
            'email' => 'required|email|'.Rule::unique('customers')->ignore($this->customer),
            'location_id' => 'required|exists:locations,id',
            'mobile_phone_number' => 'required|string',
            'is_notification_enabled' => 'boolean',
            'available_balance' => 'numeric',
            'status' => 'boolean',
        ];
    }
}
