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
        Schema::create('order_product', function(Blueprint $blueprint) {
            $blueprint->id();

            $blueprint
                ->foreignId('order_id')
                ->index()
                ->constrained('orders')
                ->cascadeOnDelete();

            $blueprint
                ->foreignId('product_id')
                ->index()
                ->constrained('products')
                ->cascadeOnDelete();

            $blueprint->integer('quantity');
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};
