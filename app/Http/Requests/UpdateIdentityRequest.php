<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIdentityRequest extends FormRequest
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
            'current_password'          => 'required',
            'new_password'              => 'required',
            'new_password_confirmation' => 'required'
        ];
    }

    /**
     * @return array<string, string>
     */

    public function messages(): array
    {
        return [
            'current_password.required'          => 'Текущий пароль обязателен для заполнения',
            'new_password.required'              => 'Новый пароль обязателен для заполнения',
            'new_password_confirmation.required' => 'Подтверждение нового пароля обязательно для заполнения',
        ];
    }

    public function getCurrentPasswordInput()
    {
        return $this->input('current_password');
    }

    public function getNewPasswordInput()
    {
        return $this->input('new_password');
    }

    public function getNewPasswordConfirmationInput()
    {
        return $this->input('new_password_confirmation');
    }
}
