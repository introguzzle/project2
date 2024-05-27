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
        Schema::create('promotions', static function (Blueprint $blueprint) {
            $blueprint->id();

            $blueprint->string('name');
            $blueprint->text('description');

            $blueprint->float('min_sum');
            $blueprint->float('max_sum');
            $blueprint->string('type');
            $blueprint->float('value');

            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
