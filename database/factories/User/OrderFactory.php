<?php

namespace Database\Factories\User;

use App\Models\PaymentMethod;
use App\Models\ReceiptMethod;
use App\Models\Status;
use App\Models\User\Profile;
use App\Other\Factory;

class OrderFactory extends Factory
{

    public function definition(): array
    {
        return [
            'profile_id' => Profile::factory(),
            'status_id' => Status::factory(),
            'receipt_method_id' => ReceiptMethod::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'description' => $this->faker->sentence,
            'total_quantity' => $this->faker->numberBetween(1, 10),
            'total_amount' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
