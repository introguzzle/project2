<?php

namespace App\Other\Contracts;

interface Migration
{
    /**
     * Run migration
     * @return void
     */
    public function up(): void;

    /**
     * Reverse migration
     * @return void
     */
    public function down(): void;
}
