<?php

namespace App\Models;

use App\Models\User\Profile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $profileId
 * @property Profile $profile
 * @property Collection<Product> $products
 */

class Cart extends Model
{
    protected $fillable = [
        'profile_id'
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'cart_product')
            ->withPivot('quantity')
            ->using(CartProduct::class);
    }

    /**
     * @return Product[]
     */

    public function getRelatedProducts(): array
    {
        return $this->products->all();
    }

    public function getTotalAmount(): float
    {
        return (float)$this->products()->get()->sum(function(Product $product) {
            $quantity = $product->getCartQuantity($this);
            return $quantity * $product->getPrice();
        });
    }

    public static function firstOrCreate(
        array $attributes = [],
        array $values = []
    ): static
    {
        $t = static fn($o): static => $o;
        return $t(static::query()
            ->firstOrCreate($attributes, $values)
        );
    }
}
