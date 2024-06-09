<?php

use App\Other\Populate;

return new class extends Populate
{
    public function up(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            $this->insert('categories', [
                'name' => 'Категория ' . $i
            ]);
        }
    }
};
