<?php

use App\Models\Role;
use App\Other\Populate;

return new class extends Populate
{
    public function up(): void
    {
        $id = $this->insert('profiles', [
            'name'       => 'admin',
            'role_id'    => Role::findByName(Role::ADMIN)->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->insert('identities', [
            'profile_id'        => $id,
            'password'          => '$2y$12$YLvmYgn60AYj8kpwAiBxyuBbJpKvO4XBM0v0.iAmV0jn2rLKeDgty',
            'email'             => 'deimosfromsolarsystem@gmail.com',
            'email_verified_at' => now()
        ]);
    }
};
