<?php

namespace Database\Factories\User;


use App\Models\User\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class IdentityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'profile_id'        => Profile::factory(),
            'phone'             => $this->faker->phoneNumber,
            'email'             => $this->faker->unique()->safeEmail,
            'password'          => Hash::make('111'),
            'remember_token'    => Str::random(10),
            'email_verified_at' => now(),
        ];
    }
}
