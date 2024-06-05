<?php

use App\Other\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function table(): string
    {
        return 'product_image';
    }

    public function definition(Blueprint $blueprint): void
    {
        $blueprint->primary(['product_id', 'image_id']);

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
        $blueprint->unique(['product_id', 'image_id', 'main']);
        $blueprint->timestamps();
    }
};
