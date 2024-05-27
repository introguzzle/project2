<?php

namespace App\Rules;

use App\Models\User\Identity;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IdentityExistsRule implements ValidationRule
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(
        string  $attribute,
        mixed   $value,
        Closure $fail
    ): void
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
