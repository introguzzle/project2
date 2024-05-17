<?php

namespace App\Models;

use Closure;
use Illuminate\Database\Eloquent\Builder;

trait ModelTrait
{

    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    public static function find(int $id): ?static
    {
        return (static::hint())(static::query()->find($id));
    }

    public static function hint(): Closure
    {
        return fn($o): ?static => $o;
    }

    public static function whereEquals(
        string $column,
        mixed $value
    ): Builder
    {
        return static::query()->where($column, '=', $value);
    }
}
