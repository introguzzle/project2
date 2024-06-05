<?php

namespace App\Models;

use App\Models\Core\Model;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property float $price
 * @property string $shortDescription
 * @property string $fullDescription
 * @property float $weight
 * @property bool $availability
 *
 * @property Category $category
 * @property Collection<Cart> $carts
 *
 * @property ?CarbonInterface $createdAt;
 * @property ?CarbonInterface $updatedAt
 *
 */

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'short_description',
        'full_description',
        'weight',
        'availability',
        'category_id'
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

    public function getMainImage(): ?Image
    {
        return $this->hasOneThrough(
            Image::class,
            ProductImage::class,
            'product_id',
            'id',
            'id',
            'image_id'
        )->where('main', '=', true)->first();
    }

    public function getPath(): ?string
    {
        return $this->getMainImage()?->url();
    }

    public function carts(): BelongsToMany
    {
        return $this
            ->belongsToMany(Cart::class, 'cart_product')
            ->withPivot('quantity')
            ->using(CartProduct::class);
    }

    public function cartProduct(Cart $cart): HasOne
    {
        return $this
            ->hasOne(CartProduct::class)
            ->where('cart_id', '=', $cart->id);
    }

    public function orderProduct(Order $order): HasOne
    {
        return $this
            ->hasOne(OrderProduct::class)
            ->where('order_id', '=', $order->id);
    }

    public function getCartQuantity(?Cart $cart): int
    {
        if ($cart === null) {
            return 0;
        }

        return $this->cartProduct($cart)->get()
            ?->first()
            ?->quantity
            ?? 0;
    }

    public function getOrderQuantity(?Order $order): int
    {
        if ($order === null) {
            return 0;
        }

        return $this->orderProduct($order)->get()
            ?->first()
            ?->quantity
            ?? 0;
    }

    /**
     * @param Category|int $category
     * @return static[]
     */

    public static function findAllByCategory(Category|int $category): array
    {
        return static::query()
                ->where('category_id', '=', $category instanceof Category
                    ? $category->id
                    : (int) $category
                )
                ->get()
                ->all();
    }
}
