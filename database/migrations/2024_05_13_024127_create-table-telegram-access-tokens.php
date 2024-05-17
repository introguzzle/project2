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
        Schema::create('telegram_access_tokens', function(Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->unsignedInteger('token');

            $blueprint
                ->foreignId('profile_id')
                ->index()
                ->constrained('profiles')
                ->cascadeOnDelete();

            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_access_tokens');
    }
};
