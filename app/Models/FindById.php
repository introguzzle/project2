<?php

namespace App\Models;

trait FindById
{

    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    public static function find(int $id): ?static
    {
        return (fn($o): ?static => $o)(static::query()->find($id));
    }
}
