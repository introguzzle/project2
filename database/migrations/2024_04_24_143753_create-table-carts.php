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
        Schema::create('carts', static function(Blueprint $blueprint) {
            $blueprint->id();
            $blueprint
                ->foreignId('profile_id')
                ->index()
                ->constrained('profiles')
                ->cascadeOnDelete();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
