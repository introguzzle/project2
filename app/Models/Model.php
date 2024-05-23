<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;


abstract class Model extends EloquentModel
{
    public const string DB_RECORD = '@DBRecord';
    public function getId(): int
    {
        return (int)$this->getKey();
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key): mixed
    {
        return parent::__get(Str::snake($key));
    }

    public function __set($key, mixed $value): void
    {
        parent::__set(Str::snake($key), $value);
    }

    public static function find(int $id): ?static
    {
        return (fn($o): ?static => $o)(
            static::query()->find($id)
        );
    }

    public static function findUnique(
        string $column,
        mixed $value
    ): ?static
    {
        return (fn($o): ?static => $o)(
            static::whereEquals($column, $value)->first()
        );
    }

    public static function whereEquals(
        string $column,
        mixed $value
    ): Builder
    {
        return static::query()->where($column, '=', $value);
    }
}
