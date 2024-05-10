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
        Schema::create('orders', function(Blueprint $blueprint) {
            $blueprint->id();

            $blueprint
                ->foreignId('profile_id')
                ->constrained('profiles')
                ->cascadeOnDelete();

            $blueprint
                ->foreignId('status_id')
                ->constrained('statuses')
                ->cascadeOnDelete();

            $blueprint->string('phone');
            $blueprint->string('address');
            $blueprint->decimal('price');
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};