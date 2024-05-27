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
        Schema::create('orders', static function(Blueprint $blueprint) {
            $blueprint->id();

            $blueprint
                ->foreignId('profile_id')
                ->index()
                ->constrained('profiles')
                ->cascadeOnDelete();

            $blueprint
                ->foreignId('status_id')
                ->index()
                ->constrained('statuses')
                ->cascadeOnDelete();

            $blueprint
                ->foreignId('payment_method_id')
                ->index()
                ->constrained('payment_methods')
                ->cascadeOnDelete();

            $blueprint
                ->foreignId('delivery_method_id')
                ->index()
                ->constrained('delivery_methods')
                ->cascadeOnDelete();

            $blueprint->string('name');
            $blueprint->string('phone');
            $blueprint->string('address');
            $blueprint->text('description')->nullable();

            $blueprint->integer('total_quantity');
            $blueprint->decimal('total_amount');
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