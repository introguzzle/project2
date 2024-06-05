<?php

namespace Database\Factories\User;

use App\Models\Role;
use App\Models\User\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'birthday' => $this->faker->date,
            'address' => $this->faker->address,
            'role_id' => Role::findByName('User')->id,
        ];
    }
}
