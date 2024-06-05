<?php

namespace App\Other;

use Faker\Generator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use ReflectionException;

abstract class Populate extends Migration implements Contracts\Migration
{
    public function down(): void
    {

    }

    public function table(string $table): Builder
    {
        return DB::table($table);
    }

    public function insert(string $table, array $data): int
    {
        return $this
            ->table($table)
            ->insertGetId($data + $this->timestamps());
    }

    public function timestamps(): array
    {
        return [
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    /**
     * @throws ReflectionException
     */
    public function getRecords(string $class): array
    {
        return ModelRecordResolver::getRecords($class);
    }

    public function faker(): Generator
    {
        return fake('ru_RU');
    }
}
