<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_image', static function(Blueprint $blueprint) {
            $blueprint->id();

            $blueprint
                ->foreignId('product_id')
                ->index()
                ->constrained('products')
                ->cascadeOnDelete();

            $blueprint
                ->foreignId('image_id')
                ->index()
                ->constrained('images')
                ->cascadeOnDelete();

            $blueprint->boolean('main');
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
