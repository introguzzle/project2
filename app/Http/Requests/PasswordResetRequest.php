<?php

namespace App\Http\Requests;

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
            'password'              => 'required',
            'password_confirmation' => 'required',
            'token'                 => 'required'
        ];
    }

    public function messages(): array
    {
        return [

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
