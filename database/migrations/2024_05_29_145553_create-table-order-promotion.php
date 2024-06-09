<?php

use App\Other\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function table(): string
    {
        return 'order_promotion';
    }

    public function definition(Blueprint $blueprint): void
    {
        $blueprint->id();

        $blueprint
            ->foreignId('order_id')
            ->index()
            ->constrained('orders')
            ->cascadeOnDelete();

        $blueprint
            ->foreignId('promotion_id')
            ->index()
            ->constrained('promotions')
            ->cascadeOnDelete();

        $blueprint->unique(['order_id', 'promotion_id']);

        $blueprint->timestamps();
    }
};
