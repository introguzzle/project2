<?php

namespace App\Http\Requests;

use App\Rules\PasswordMatchRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
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
            'password'  => [
                'required',
                'string',
                'min:4'
            ],

            'password_confirmation' => [
                'required',
                'string',
                'min:4',
                new PasswordMatchRule('password')
            ],

            'token' => [
                'required',
                'string'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required'              => 'Пароль обязателен для заполнения.',
            'password.min'                   => 'Пароль должен содержать не менее 4 символов.',
            'password.confirmed'             => 'Подтверждение пароля не совпадает.',
            'password_confirmation.required' => 'Подтверждение пароля обязательно для заполнения.',
            'password_confirmation.min'      => 'Подтверждение пароля должно содержать не менее 4 символов.',
            'token.required'                 => 'Токен обязателен для заполнения.',
        ];
    }

    public function getPasswordInput(): string
    {
        return $this->string('password')->toString();
    }

    public function getPasswordConfirmationInput(): string
    {
        return $this->string('password_confirmation')->toString();
    }

    public function getTokenInput(): string
    {
        return $this->string('token')->toString();
    }
}
