<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name"                  => "required",
            "password"              => "required|confirmed",
            "password_confirmation" => "required"
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                  => 'Это поле обязательно для заполнения',
            'password.required'              => 'Это поле обязательно для заполнения',
            'password_confirmation.required' => 'Это поле обязательно для заполнения',
            'password.confirmed'             => 'Пароли должны совпадать'
        ];
    }
}
