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
        $this->insert('promotions', [
            'name'              => '',
            'min_sum'           => 1500,
            'max_sum'           => 100000000,
            'promotion_type_id' => PromotionType::fixed()->id,
            'value'             => 150
        ]);
    }
};

