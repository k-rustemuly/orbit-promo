<?php

namespace App\Http\Requests;

use MoonShine\Http\Requests\MoonShineFormRequest;
use MoonShine\MoonShineAuth;

class BalanceInstantPrizeStoreRequest extends MoonShineFormRequest
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
            'name_ru' => 'nullable',
            'name_kk' => 'nullable',
            'name_uz' => 'nullable',
            'codes' => 'required|array'
        ];
    }
}
