<?php

use App\Other\Populate;

return new class extends Populate
{
    public function up(): void
    {
        $this->insert('receipt_methods', [
            'name' => 'Самовывоз',
        ]);

        $this->insert('receipt_methods', [
            'name' => 'Доставка',
        ]);
    }
};
