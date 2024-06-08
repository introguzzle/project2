<?php

use App\Models\PromotionType;
use App\Other\Populate;

return new class extends Populate
{

    /**
     * Run migration
     * @return void
     */
    public function up(): void
    {
        $records = $this->getRecords(PromotionType::class);

        foreach ($records as $record) {
            $this->insert('promotion_types', [
                'name' => $record
            ]);
        }
    }
};
