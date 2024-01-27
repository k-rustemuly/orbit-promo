<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GameFinishRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'userId' => [
                'required',
                'uuid',
                'exists:games,id'
            ],
            'level' => [
                'required',
                'integer',
                'min:0'
            ],
            'score' => [
                'required',
                'integer',
                'min:0'
            ],
            'time' => [
                'required',
                'integer',
                'min:0'
            ],
            'finish' => [
                'required',
                'boolean'
            ],
        ];
    }
}
