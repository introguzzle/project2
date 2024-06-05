<?php

use App\Other\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function table(): string
    {
        return 'receipt_methods';
    }

    public function definition(Blueprint $blueprint): void
    {
        $blueprint->id();
        $blueprint->string('name');
        $blueprint->timestamps();
    }
};
