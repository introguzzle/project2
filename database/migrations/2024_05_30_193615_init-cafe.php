<?php

use App\Other\Populate;

return new class extends Populate
{
    public function up(): void
    {
        $this->insert('cafes', [
            'name'               => 'С огоньком!',

            'address'            => 'ул. Ленина, 74',
            'phone'              => '79149999999',
            'email'              => '',
            'settings'           => jsonEncode([
                'show_logo' => false
            ]),

            'description'        => 'Кафе в посёлке Агинское',
            'required_order_sum' => 500,
            'image'              => null,
        ]);
    }
};
