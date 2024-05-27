<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', static function(Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name');

            $blueprint
                ->foreignId('category_id')
                ->index()
                ->constrained('categories')
                ->cascadeOnDelete();

            $blueprint->text('short_description')->nullable();
            $blueprint->text('full_description')->nullable();
            $blueprint->decimal('price');
            $blueprint->decimal('weight');
            $blueprint->boolean('availability');

            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
