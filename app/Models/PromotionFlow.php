<?php

namespace App\Models;

use App\Models\Core\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $promotionId
 * @property int $flowId
 *
 * @property Promotion $promotion
 * @property Flow $flow
 */
class PromotionFlow extends Pivot
{
    protected $table = 'promotion_flow';
    protected $fillable = [
        'promotion_id',
        'flow_id',
    ];

    public function flow(): BelongsTo
    {
        return $this->belongsTo(Flow::class, 'flow_id');
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }
}
