<?php

namespace App\Http\Requests;

use App\Rules\PasswordMatchRule;
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
            'name' => [
                'required',
                'max:255',
            ],
            'password' => [
                'required',
                'min:4',
            ],
            'password_confirmation' => [
                'required',
                'same:password'
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'                  => 'Имя обязательно для заполнения',
            'name.max'                       => 'Имя не должно превышать 255 символов',

            'password.required'              => 'Пароль обязателен для заполнения',
            'password.min'                   => 'Пароль должен быть не менее 4 символов',

            'password_confirmation.required' => 'Подтверждение пароля обязательно для заполнения',
            'password_confirmation.same'     => 'Пароли не совпадают'
        ];
    }

    /**
     * Get the sanitized name input.
     *
     * @return string
     */
    public function getNameInput(): string
    {
        return $this->string('name')->toString();
    }

    /**
     * Get the sanitized password input.
     *
     * @return string
     */
    public function getPasswordInput(): string
    {
        return $this->string('password')->toString();
    }

    /**
     * Get the sanitized password confirmation input.
     *
     * @return string
     */
    public function getPasswordConfirmationInput(): string
    {
        return $this->string('password_confirmation')->toString();
    }
}
