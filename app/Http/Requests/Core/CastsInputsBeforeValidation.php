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
            $value = $this->input($input);

            if ($type === 'date' && $value !== null) {
                $newInputs[$input] = new Carbon($value);
                continue;
            }

            if ($value !== null) {
                if (str_contains($type, '[]')) {
                    $baseType = str_replace('[]', '', $type);

                    if (is_array($value)) {
                        foreach ($value as &$item) {
                            settype($item, $baseType);
                        }

                        unset($item);
                    }

                } else {
                    settype($value, $type);
                }

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
