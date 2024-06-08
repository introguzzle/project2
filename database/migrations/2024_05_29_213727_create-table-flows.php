<?php


use App\Other\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function table(): string
    {
        return 'flows';
    }

    public function definition(Blueprint $blueprint): void
    {
        $blueprint->id();

        $blueprint
            ->foreignId('receipt_method_id')
            ->index()
            ->constrained('receipt_methods')
            ->cascadeOnDelete();

        $blueprint
            ->foreignId('payment_method_id')
            ->index()
            ->constrained('payment_methods')
            ->cascadeOnDelete();

        $blueprint->unique(['receipt_method_id', 'payment_method_id']);

        $blueprint->timestamps();
    }
};
