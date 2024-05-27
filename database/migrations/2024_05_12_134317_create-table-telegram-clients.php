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
        Schema::create('telegram_clients', static function(Blueprint $blueprint) {
            $blueprint->id();

            $blueprint->text('chat_id');
            $blueprint->string('first_name')->nullable();
            $blueprint->string('username')->nullable();
            $blueprint->string('type')->nullable();

            $blueprint->boolean('has_access');
            $blueprint->boolean('banned')
                ->default(false);

            $blueprint
                ->foreignId('profile_id')
                ->index()
                ->nullable()
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
        Schema::dropIfExists('telegram_clients');
    }
};
