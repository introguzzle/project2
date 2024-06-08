<?php

use App\Other\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function table(): string
    {
        return 'promotion_flow';
    }

    public function definition(Blueprint $blueprint): void
    {
        $blueprint->primary(['promotion_id', 'flow_id']);

        $blueprint
            ->foreignId('promotion_id')
            ->index()
            ->constrained('promotions')
            ->cascadeOnDelete();

        $blueprint
            ->foreignId('flow_id')
            ->index()
            ->constrained('flows')
            ->cascadeOnDelete();


        $blueprint->timestamps();
    }
};
