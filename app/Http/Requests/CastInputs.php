<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use InvalidArgumentException;

/**
 * Requires an FormRequest class
 */
trait CastInputs
{
    /**
     * @return array<string, string>
     */
    abstract public function getCasts(): array;
    public function cast(): void
    {
        if (!$this instanceof FormRequest) {
            throw new InvalidArgumentException();
        }

        $newInputs = [];

        foreach ($this->getCasts() as $input => $type) {
            $value = $this->input($input);

            settype($value, $type);

            $newInputs[$input] = $value;
            $this->request->remove($input);
        }

        $this->request->add($newInputs);
    }

    public function prepareForValidation(): void
    {
        $this->cast();
    }
}
