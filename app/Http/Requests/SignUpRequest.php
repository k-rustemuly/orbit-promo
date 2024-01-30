<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class SignUpRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3'
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                // 'unique:users'
            ],
            'phone_number' => [
                'required',
                'regex:/^7[0-9]{10}$|^998[0-9]{9}$/',
                // 'unique:users'
            ]
        ];
    }

    public function messages()
    {
        return [
            'before_or_equal' => __('validation.min_age')
        ];
    }
}
