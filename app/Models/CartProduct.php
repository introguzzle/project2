<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property Cart $cart;
 * @property Product $product
 * @property int $quantity
 */
class CartProduct extends Pivot
{
    protected $table = 'cart_product';
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity'
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function unique(
        int $cartId,
        int $productId
    ): ?static
    {
        $t = static fn($static): ?static => $static;

        return $t(static::query()
            ->where('cart_id', '=', $cartId)
            ->where('product_id', '=', $productId)
            ->first()
        );
    }
}
