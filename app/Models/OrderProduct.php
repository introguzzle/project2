<?php

namespace App\Models;

use App\Models\Core\Pivot;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $quantity
 *
 * @property Order $order
 * @property Product $product
 *
 * @property int $orderId
 * @property int $productId
 *
 * @property ?CarbonInterface $createdAt
 * @property ?CarbonInterface $updatedAt
 */
class OrderProduct extends Pivot
{
    protected $table = 'order_product';
    protected $primaryKey = [
        'order_id',
        'product_id'
    ];

    protected $fillable = [
        'quantity',
        'order_id',
        'product_id'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
