<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class PasswordMatchRule implements ValidationRule
{
    private string $other;

    public function __construct(string $other)
    {
        $this->other = $other;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     * @return void
     */

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ((string)request()->input($this->other) !== (string)$value) {
            $fail('Пароли должны совпадать');
        }
    }
}
