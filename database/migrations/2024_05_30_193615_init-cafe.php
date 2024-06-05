<?php

use App\Other\Populate;

return new class extends Populate
{
    public function up(): void
    {
        $this->insert('cafes', [
            'name'               => 'С огоньком!',

            'addresses'          => jsonEncode(['ул. Ленина, 74', 'ул. Ленина, 99']),
            'phones'             => jsonEncode(['123', '134']),
            'emails'             => jsonEncode(['test@mail.ru']),
            'settings'           => null,

            'description'        => 'Кафе в посёлке Агинское',
            'required_order_sum' => 500,
            'image'              => null,

        ]);
    }
};
