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
        Schema::create('telegram_clients', function(Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->bigInteger('chat_id');
            $blueprint->string('first_name')->nullable();
            $blueprint->string('username')->nullable();
            $blueprint->string('type')->nullable();
            $blueprint->boolean('granted_access');
//            $blueprint->foreignId('profile_id')
//                ->constrained('profiles')
//                ->cascadeOnDelete();

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
