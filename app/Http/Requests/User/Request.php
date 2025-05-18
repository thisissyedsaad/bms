<?php

namespace App\Http\Requests\User;

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
        $user_id =  $this->user;
        return [
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|'.Rule::unique('users')->ignore($user_id),
            // 'user_role'         => Rule::requiredIf(fn () => $this->has('user_role')),
            'password'          => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active'         => 'nullable',
        ];
    }
}
