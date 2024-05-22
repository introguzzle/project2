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
                'string'
            ],

            'password' => [
                'required',
                'string',
                'min:4'
            ],

            'password_confirmation' => [
                'required',
                'min:4',
                'string',
                new PasswordMatchRule('password')
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                  => 'Это поле обязательно для заполнения',
            'password.required'              => 'Это поле обязательно для заполнения',
            'password_confirmation.required' => 'Это поле обязательно для заполнения',
        ];
    }

    public function getNameInput(): string
    {
        return $this->string('name')->toString();
    }

    public function getPasswordInput(): string
    {
        return $this->string('password')->toString();
    }

    public function getPasswordConfirmationInput(): string
    {
        return $this->string('password_confirmation')->toString();
    }
}
