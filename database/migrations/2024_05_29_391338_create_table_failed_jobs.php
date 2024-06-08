<?php

use App\Other\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function table(): string
    {
        return 'failed_jobs';
    }

    public function definition(Blueprint $blueprint): void
    {
        $blueprint->id();
        $blueprint->string('uuid')->unique();
        $blueprint->text('connection');
        $blueprint->text('queue');
        $blueprint->longText('payload');
        $blueprint->longText('exception');
        $blueprint->timestamp('failed_at')->useCurrent();
    }
};
