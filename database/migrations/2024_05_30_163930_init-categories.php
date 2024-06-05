<?php

use App\Other\Populate;

return new class extends Populate
{
    public function up(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->insert('categories', [
                'name' => 'Категория ' . $i
            ]);
        }
    }
};
