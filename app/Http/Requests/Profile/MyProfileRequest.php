<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class MyProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $customer = auth()->guard('customers')->user();
        
        $rules = [
            'type' => 'required|in:private_seller,trader',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'mobile_phone_number' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
            'additional_address' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:20',
            'city_id' => 'required|exists:cities,id',
            'area' => 'required|string|max:255',
            'is_notification_enabled' => 'required',
        ];

        // Add business validation rules if type is trader
        if ($this->type === 'trader') {
            $rules = array_merge($rules, [
                'business_name' => 'required|string|max:255',
                'business_number' => 'required|string|max:50',
                'business_email' => 'required|email|max:255',
                'business_registration_number' => 'required|string|max:50',
                'year_established' => 'required|integer|min:1900|max:' . date('Y'),
            ]);
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.required' => 'User type is required',
            'type.in' => 'Invalid user type selected',
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already in use',
            'mobile_phone_number.required' => 'Phone number is required',
            'street_address.required' => 'Street address is required',
            'postal_code.required' => 'Postal code is required',
            'city_id.required' => 'City is required',
            'city_id.exists' => 'Selected city is invalid',
            'area.required' => 'Area is required',
            
            // Business validation messages
            'business_name.required' => 'Business name is required',
            'business_number.required' => 'Business number is required',
            'business_email.required' => 'Business email is required',
            'business_email.email' => 'Please enter a valid business email address',
            'business_registration_number.required' => 'Business registration number is required',
            'year_established.required' => 'Year established is required',
            'year_established.integer' => 'Year established must be a valid year',
            'year_established.min' => 'Year established must be after 1900',
            'year_established.max' => 'Year established cannot be in the future',
        ];
    }
} 