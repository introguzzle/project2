<?php

namespace Database\Seeders;


use App\Other\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            ProfileSeeder::class
        );

        $this->call(
            OrderSeeder::class
        );
    }
}
