<?php

use App\Other\Populate;

return new class extends Populate
{
    public function up(): void
    {
        $this->insert('cafes', [
            'name'               => 'С огоньком!',

            'address'            => 'ул. Ленина',
            'phone'              => '',
            'email'              => '',
            'settings'           => null,

            'description'        => 'Кафе в посёлке Агинское',
            'required_order_sum' => 500,
            'image'              => null,

        ]);
    }
};
