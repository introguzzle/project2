<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;


abstract class Model extends EloquentModel
{
    use HasFactory;
    public const string DB_RECORD = '@DBRecord';

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key): mixed
    {
        if ($this->isRelation($key)) {
            return parent::__get($key);
        }

        return parent::__get(Str::snake($key));
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */

    public function __set($key, mixed $value): void
    {
        if ($this->isRelation($key)) {
            parent::__set($key, $value);
        }

        parent::__set(Str::snake($key), $value);
    }

    public static function find(int $id): ?static
    {
        $t = static fn($static): ?static => $static;
        return $t(static::query()->find($id));
    }

    public static function findOrFail(int $id): ?static
    {
        $t = static fn($static): ?static => $static;
        return $t(static::query()->findOrFail($id));
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

    public static function create(
        array $attributes
    ): static
    {
        $t = static fn($static): ?static => $static;
        return $t(static::query()->create($attributes));
    }

    /**
     * @template T
     * @param string $column
     * @param string $direction
     * @return Collection<int, static>
     */
    public static function ordered(
        string $column,
        string $direction = 'asc'
    ): Collection
    {
        return static::query()
            ->orderBy($column, $direction)
            ->get();
    }
}
