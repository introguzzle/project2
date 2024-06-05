<?php

namespace App\Http\Requests\Admin\Dashboard;

use App\Http\Requests\Core\FormRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @property string $newPassword
 */
class UpdateIdentityRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password'          => 'required|string',
            'new_password'              => [
                'required',
                'string',
                'different:current_password'
            ],
            'new_password_confirmation' => 'required|string|same:new_password'
        ];
    }

    public function getMessages(): array
    {
        return [
            'current_password.required'          => 'Текущий пароль обязателен для ввода.',
            'current_password.string'            => 'Текущий пароль должен быть строкой.',
            'new_password.required'              => 'Новый пароль обязателен для ввода.',
            'new_password.string'                => 'Новый пароль должен быть строкой.',
            'new_password.different'             => 'Новый пароль должен отличаться от текущего.',
            'new_password_confirmation.required' => 'Подтверждение нового пароля обязательно для ввода.',
            'new_password_confirmation.string'   => 'Подтверждение нового пароля должно быть строкой.',
            'new_password_confirmation.same'     => 'Пароли не совпадают',
        ];
    }

    /**
     * @throws ValidationException
     * @throws AuthenticationException
     */
    public function prepareForValidation(): void
    {
        if ($this->user() === null) {
            throw new AuthenticationException();
        }

        $actual = $this->user()->getAuthPassword();

        if (!Hash::check($this->input('current_password'), $actual)) {
            $validator = $this->getValidatorInstance();
            $validator->errors()->add('new_password', 'Введенный пароль неверен');

            throw new ValidationException($validator);
        }
    }
}
