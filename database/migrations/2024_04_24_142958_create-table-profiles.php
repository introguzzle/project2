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
        Schema::create('profiles', function(Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name')->nullable();
            $blueprint->date('birthday')->nullable();
            $blueprint->string('address')->nullable();

            $blueprint
                ->foreignId('role_id')
                ->default(1)
                ->constrained('roles')
                ->cascadeOnDelete();

            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
