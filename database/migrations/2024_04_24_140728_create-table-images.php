<?php

use App\Other\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function table(): string
    {
        return 'images';
    }

    public function definition(Blueprint $blueprint): void
    {
        $blueprint->id();
        $blueprint->string('path');

        $blueprint
            ->string('name')
            ->nullable();
        $blueprint
            ->text('description')
            ->nullable();

        $blueprint->string('type');
        $blueprint->string('file_size');
        $blueprint->float('bytes');
        $blueprint->string('image_size');

        $blueprint->timestamps();
    }
};
