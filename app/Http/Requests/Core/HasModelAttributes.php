<?php

namespace App\Http\Requests\Core;

use Illuminate\Support\Arr;

trait HasModelAttributes
{
    abstract public function getExcept(): array;

    public function getModelAttributes(): array
    {
        return Arr::except($this->all(), $this->getExcept());
    }
}
