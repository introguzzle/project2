<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Core\FormRequest;
use App\Rules\IdentityExistsRule;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @property string $login
 * @property string $password
 */
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

    public function getMessages(): array
    {
        return [
            'login.required' => 'Поле логина обязательно для заполнения'
        ];
    }
}
