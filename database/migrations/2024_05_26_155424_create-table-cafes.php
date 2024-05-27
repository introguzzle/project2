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
        Schema::create('cafes', static function (Blueprint $blueprint) {
            $blueprint->id();

            $blueprint->string('name');
            $blueprint->text('address');
            $blueprint->text('description');
            $blueprint->unsignedDecimal('required_order_sum');
            $blueprint->string('image');
            $blueprint->jsonb('settings');

            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cafes');
    }
};
