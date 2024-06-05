<?php

namespace App\Http\Requests\Core;

use App\Models\User\Identity;
use Illuminate\Support\Str;
use LogicException;

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
        throw new LogicException('Trying to set invalid property: ' . $key);
    }

    /**
     * @param $guard
     * @return ?Identity
     */

    public function user($guard = null): ?Identity
    {
        return parent::user($guard);
    }
}
