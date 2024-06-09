<?php

namespace App\Http\Requests\Core;

use App\Models\User\Identity;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use LogicException;
use RuntimeException;

abstract class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    abstract public function authorize(): bool;
    abstract public function rules(): array;

    abstract public function getMessages(): array;
    public function messages(): array
    {
        return parent::messages() + $this->getMessages();
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key): mixed
    {
        if (str_contains($key, '.')) {
            return parent::__get($key);
        }

        return parent::__get(Str::snake($key));
    }

    public function __set($key, $value)
    {
        throw new RuntimeException('Unsupported method: __set');
    }

    /**
     * @param string $guard
     * @return ?Identity
     */

    public function user($guard = null): ?Identity
    {
        return parent::user($guard);
    }

    public function append(
        string $key,
        string $message
    ): Validator
    {
        $validator = $this->validator ?? $this->getValidatorInstance();
        $validator->errors()->add($key, $message);

        return $validator;
    }

    /**
     * @throws ValidationException
     */
    public function throw(): never
    {
        throw new ValidationException($this->validator ?? $this->getValidatorInstance());
    }
}
