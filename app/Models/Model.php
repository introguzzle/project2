<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;


abstract class Model extends EloquentModel
{
    use HasFactory;
    public const string DB_RECORD = '@DBRecord';
    public function getId(): int
    {
        return (int) $this->getKey();
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key): mixed
    {
        return parent::__get(Str::snake($key));
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */

    public function __set($key, mixed $value): void
    {
        parent::__set(Str::snake($key), $value);
    }

    public static function find(int $id): ?static
    {
        $t = static fn($static): ?static => $static;
        return $t(static::query()->find($id));
    }

    public static function findUnique(
        string $column,
        mixed $value
    ): ?static
    {
        $t = static fn($static): ?static => $static;
        return $t(static::whereEquals($column, $value)->first());
    }

    public static function whereEquals(
        string $column,
        mixed $value
    ): Builder
    {
        return static::query()->where($column, '=', $value);
    }

    public static function create(array $attributes): static
    {
        $t = static fn($static): ?static => $static;
        return $t(static::query()->create($attributes));
    }
}
