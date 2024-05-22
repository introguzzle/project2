<?php

namespace App\Rules;

use App\Models\Identity;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class IdentityExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = Identity::query()
            ->where('email', '=', $value)
            ->orWhere('phone', '=', $value)
            ->exists();

        if (!$exists) {
            $fail('Не удалось найти запись');
        }
    }
}
