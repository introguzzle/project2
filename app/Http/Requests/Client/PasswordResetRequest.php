<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Core\FormRequest;
use App\Models\User\PasswordResetToken;
use App\Rules\PasswordMatchRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @property string $password
 * @property string $passwordConfirmation
 * @property string $token
 */
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
                'min:4',
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

    public function getMessages(): array
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

    /**
     * @throws ValidationException
     */
    public function prepareForValidation(): void
    {
        $passwordResetToken = PasswordResetToken::findByToken($this->token);

        $current = $passwordResetToken?->identity->password;

        if (Hash::check($this->password, $current)) {
            $validator = $this->getValidatorInstance();
            $validator->errors()->add('password', 'Новый пароль должен отличаться от текущего');

            throw new ValidationException($validator);
        }
    }
}
