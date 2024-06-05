<?php

use App\Models\Status;
use App\Other\Populate;

return new class extends Populate
{
    public function up(): void
    {
        $records = $this->getRecords(Status::class);

        foreach ($records as $record) {
            $this->insert('statuses', [
                'name' => $record
            ]);
        }
    }
};
