<?php

namespace App\Other;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration as LaravelMigration;
use App\Other\Contracts\Migration as MigrationContract;

abstract class Migration extends LaravelMigration implements MigrationContract
{
    public function up(): void
    {
        Schema::create($this->table(), function(Blueprint $blueprint) {
            $this->definition($blueprint);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists($this->table());
    }

    abstract public function table(): string;

    /**
     * @param Blueprint $blueprint
     * @return void
     */
    abstract public function definition(Blueprint $blueprint): void;
}
