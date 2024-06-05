<?php

namespace Database\Seeders;

use App\Models\User\Identity;
use App\Models\User\Profile;
use App\Other\Seeder;

class ProfileSeeder extends Seeder
{

    public function run(): void
    {
        Profile::factory()->count(100)->create()->each(function ($profile) {
            Identity::factory()->create(['profile_id' => $profile->id]);
        });
    }
}
