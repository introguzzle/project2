<?php

use App\Models\Role;
use App\Other\Populate;

return new class extends Populate
{
    public function up(): void
    {
        $records = $this->getRecords(Role::class);

        foreach ($records as $record) {
            $this->insert('roles', [
                'name' => $record
            ]);
        }
    }
};
