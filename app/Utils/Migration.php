<?php

namespace App\Utils;

use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

abstract class Migration extends \Illuminate\Database\Migrations\Migration
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
