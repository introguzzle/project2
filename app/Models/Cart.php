<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
    use HasFactory;

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

    public function getTotalAmount(): float
    {
        return (float)$this->products()->get()->sum(function(Product $product) {
            $quantity = $product->getCartQuantity($this);
            return $quantity * $product->getPrice();
        });
    }
}
