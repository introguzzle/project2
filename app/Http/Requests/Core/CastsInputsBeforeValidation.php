<?php

namespace App\Http\Requests\Core;

use Carbon\Carbon;

/**
 * Requires an FormRequest class
 */
trait CastsInputsBeforeValidation
{
    /**
     * @return array<string, string>
     */
    abstract public function getCasts(): array;
    public function cast(): void
    {
        $newInputs = [];

        foreach ($this->getCasts() as $input => $type) {
            if ($type === 'date' && $input !== null) {
                $newInputs[$input] = new Carbon($this->input($input));
                continue;
            }

            $value = $this->input($input);

            if ($value !== null) {
                settype($value, $type);

                $newInputs[$input] = $value;
                $this->request->remove($input);
            }
        }

        $this->request->add($newInputs);
    }

    public function prepareForValidation(): void
    {
        $this->cast();
    }
}
