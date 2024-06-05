<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;
use Illuminate\Support\Str;

abstract class Pivot extends EloquentModel
{
    use AsPivot;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

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

    /**
     * @param Builder $query
     * @return Builder
     */
    public function setKeysForSaveQuery($query): Builder
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $key) {
            $query->where($key, '=', $this->getKeyForSaveQuery($key));
        }

        return $query;
    }

    /**
     * @param string|null $keyName
     * @return mixed
     */

    public function getKeyForSaveQuery(string $keyName = null): mixed
    {
        if ($keyName === null) {
            $keyName = $this->getKeyName();
        }

        return $this->original[$keyName] ?? $this->getAttribute($keyName);
    }

    public static function find(array $primaryKeys): ?static
    {
        $query = static::query();

        foreach ($primaryKeys as $key => $value) {
            $query->where($key, '=', $value);
        }

        $t = static fn($static): ?static => $static;
        return $t($query->first());
    }

    public static function whereEquals(
        string $column,
        mixed $value
    ): Builder
    {
        return static::query()->where($column, '=', $value);
    }

    public static function create(array $attributes = []): static
    {
        $t = static fn($static): ?static => $static;
        return $t(static::query()->create($attributes));
    }
}
