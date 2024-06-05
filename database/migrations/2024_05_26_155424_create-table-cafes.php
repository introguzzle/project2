<?php

use App\Other\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function table(): string
    {
        return 'cafes';
    }

    public function definition(Blueprint $blueprint): void
    {
        $blueprint->id();
        $blueprint->string('name');
        $blueprint->unsignedDecimal('required_order_sum');

        $blueprint->string('address');
        $blueprint->string('phone');
        $blueprint->string('email');
        $blueprint->jsonb('settings')->nullable();

        $blueprint->text('description')->nullable();
        $blueprint->string('image')->nullable();

        $blueprint->timestamps();
    }
};
