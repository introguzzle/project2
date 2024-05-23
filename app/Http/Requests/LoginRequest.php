<?php

namespace App\Http\Requests;

use App\Rules\IdentityExistsRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'login'    => [
                'required',
                new IdentityExistsRule()
            ],
            'password' => [
                'required'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Поле логина обязательно для заполнения'
        ];
    }

    public function getLoginInput(): string
    {
        return $this->string('login')->toString();
    }

    public function getPasswordInput(): string
    {
        return $this->string('password')->toString();
    }
}
