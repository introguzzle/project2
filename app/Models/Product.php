<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory, FindById;

    protected $fillable = [
        'name',
        'price',
        'short_description',
        'full_description',
        'weight',
        'availability',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): BelongsToMany
    {
        return $this
            ->belongsToMany(Image::class, 'product_image')
            ->using(ProductImage::class);
    }

    public function carts(): BelongsToMany
    {
        return $this
            ->belongsToMany(Cart::class, 'cart_product')
            ->using(CartProduct::class);
    }

    public function getName(): string
    {
        return $this->getAttribute('name');
    }

    public function getPrice(): float
    {
        return $this->getAttribute('price');
    }

    public function getCategoryId(): int
    {
        return $this->getAttribute('category_id');
    }
}
