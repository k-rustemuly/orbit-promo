<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class ReSendSmsRequest extends BaseFormRequest
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
            'phone_number' => [
                'required',
                'regex:/^7[0-9]{10}$|^998[0-9]{9}$/',
                'exists:users'
            ],
        ];
    }
}
