<?php

use App\Models\Flow;
use App\Models\Promotion;
use App\Other\Populate;

return new class extends Populate
{
    /**
     * Run migration
     * @return void
     */
    public function up(): void
    {
        foreach (Promotion::all() as $promotion) {
            foreach (Flow::all() as $flow) {
                $attributes = [
                    'promotion_id'      => $promotion->id,
                    'flow_id'           => $flow->id
                ];

                $this->table('promotion_flow')->insert($attributes + $this->timestamps());
            }
        }
    }
};

