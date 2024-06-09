<?php

use App\Other\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * @return string
     */
    public function table(): string
    {
        return 'order_product';
    }

    /**
     * @param Blueprint $blueprint
     * @return void
     */
    public function definition(Blueprint $blueprint): void
    {
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
        $blueprint->unique(['order_id', 'product_id']);
        $blueprint->timestamps();
    }
};
