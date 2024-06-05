<?php

namespace App\Other;

abstract class Factory extends \Illuminate\Database\Eloquent\Factories\Factory
{
    abstract public function definition(): array;
}
