<?php

namespace App\Http\Requests\Core;

use Illuminate\Support\Arr;

trait HasModelAttributes
{
    /**
     * @return array - Array of attributes that should be excluded from all inputs
     */
    abstract public function getExcept(): array;

    /**
     * @return array - Retrieve model attributes. Typically, post or put method
     */

    public function getModelAttributes(): array
    {
        $except = $this->getExcept();
        $except[] = '_token';

        return Arr::except(
            $this->all(),
            $except
        );
    }
}
