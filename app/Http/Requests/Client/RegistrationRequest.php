<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Core\FormRequest;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

/**
 * @property string $name
 * @property string $password
 * @property string $passwordConfirmation
 */

class RegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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

    public function getMessages(): array
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

    protected function failedValidation(ValidatorContract|Validator $validator): void
    {
        /**
         * @var ValidationException $exception
         */
        $exception = new ($validator->getException())($this->validator);

        $redirect = back()->withInput();

        foreach ($validator->errors()->messages() as $key => $errors) {
            $redirect->with($key, array_values($errors)[0]);
        }

        $url = $redirect->getTargetUrl();

        $session = request()?->session();

        $session->setPreviousUrl($url);
        $session->setRequestOnHandler($this);

        throw $exception->redirectTo($url);
    }
}
