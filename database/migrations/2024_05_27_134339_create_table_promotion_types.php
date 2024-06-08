<?php

use App\Other\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function table(): string
    {
        return 'promotion_types';
    }

    public function definition(Blueprint $blueprint): void
    {
        $blueprint->id();
        $blueprint->string('name');
        $blueprint->text('description')->nullable();

        $blueprint->timestamps();
    }
};
