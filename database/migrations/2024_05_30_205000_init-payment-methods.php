<?php

use App\Other\Populate;

return new class extends Populate
{
    public function up(): void
    {
        $this->insert('payment_methods', [
            'name' => 'При получении',
        ]);

        $this->insert('payment_methods', [
            'name' => 'Предоплата',
        ]);
    }
};
