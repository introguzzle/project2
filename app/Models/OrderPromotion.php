<?php

namespace App\Models;

use App\Models\Core\Pivot;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DateTimeInterface as DateTime;

/**
 * @property int $id
 * @property int $quantity
 *
 * @property Order $order
 * @property Promotion $promotion
 *
 * @property int $orderId
 * @property int $promotionId
 *
 * @property ?CarbonInterface $createdAt;
 * @property ?CarbonInterface $updatedAt
 */
class OrderPromotion extends Pivot
{
    protected $table = 'order_promotion';
    protected $primaryKey = [
        'order_id',
        'promotion_id'
    ];

    protected $fillable = [
        'order_id',
        'promotion_id',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }
}
