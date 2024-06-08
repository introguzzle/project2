<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \App\Other\Migration
{
    public function table(): string
    {
        return 'orders';
    }

    public function definition(Blueprint $blueprint): void
    {
        $blueprint->id();

        $blueprint
            ->foreignId('profile_id')
            ->index()
            ->constrained('profiles')
            ->cascadeOnDelete();

        $blueprint
            ->foreignId('status_id')
            ->index()
            ->constrained('statuses')
            ->cascadeOnDelete();

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

        $blueprint->string('name');
        $blueprint->string('phone');
        $blueprint->string('address');
        $blueprint->text('description')->nullable();

        $blueprint->unsignedInteger('total_quantity');
        $blueprint->unsignedDecimal('total_amount');
        $blueprint->unsignedDecimal('after_amount');
        $blueprint->timestamps();
    }
};
