<?php

use App\Other\Populate;

return new class extends Populate
{
    public function up(): void
    {
        $faker = $this->faker();

        for ($i = 1; $i <= 40; $i++) {
            $this->insert('products', [
                'name'              => 'Товар ' . $i,
                'category_id'       => random_int(1, 8),
                'short_description' => $faker->realText(40),
                'full_description'  => $faker->realText(400),
                'price'             => $i * 100,
                'weight'            => random_int(0, 30) * 0.666,
                'availability'      => true
            ]);
        }
    }
};
