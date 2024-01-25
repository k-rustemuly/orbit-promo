<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use MoonShine\MoonShineAuth;

class SettingsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return MoonShineAuth::guard()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'game_max_coins' => ['required', 'integer', 'min:0'],
            'receipt_life' => ['required', 'integer', 'min:0'],
            'referal_life' => ['required', 'integer', 'min:0'],
            'promotion' => ['required', 'array']
        ];
    }
}
