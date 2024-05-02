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
        Schema::create('identities', function(Blueprint $blueprint) {
            $blueprint->id();

            $blueprint->foreignId('profile_id')
                ->constrained('profiles')
                ->cascadeOnDelete();

            $blueprint->string('login')->unique();
            $blueprint->string('password');
            $blueprint->string('remember_token')->nullable();

            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identities');
    }
};
