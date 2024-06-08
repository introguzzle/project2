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
        return 'promotions';
    }

    /**
     * @param Blueprint $blueprint
     * @return void
     */
    public function definition(Blueprint $blueprint): void
    {
        $blueprint->id();

        $blueprint->string('name');
        $blueprint->text('description')->nullable();

        $blueprint->unsignedFloat('min_sum');
        $blueprint->unsignedFloat('max_sum')->nullable();

        $blueprint
            ->foreignId('promotion_type_id')
            ->index()
            ->constrained('promotion_types')
            ->cascadeOnDelete();

        $blueprint->unsignedFloat('value');

        $blueprint->timestamps();
    }
};
