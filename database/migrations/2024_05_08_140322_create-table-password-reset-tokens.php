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
        Schema::create('password_reset_tokens', static function(Blueprint $blueprint) {
            $blueprint->id();
            $blueprint
                ->foreignId('identity_id')
                ->index()
                ->constrained('identities')
                ->cascadeOnDelete();

            $blueprint->string('token');
            $blueprint->boolean('active')->default('true');
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
