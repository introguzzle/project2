<?php

namespace App\Services;

use App\Exceptions\ServiceException;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\Profile;
use App\ModelView\ProductView;

class CartService
{
    /**
     * @param Profile $profile
     * @param int|string $productId
     * @param int|string $gain
     * @return void
     */
    public function update(
        Profile $profile,
        int|string $productId,
        int|string $gain
    ): void
    {
        $cart = Cart::query()
            ->where('profile_id', '=', $profile->getAttribute('id'))
            ->first();

        if (!$this->profileOwnsCart($profile)) {
            $this->create($profile);
        }

        $cartId = $cart->getAttribute('id');

        if ($this->cartProductAlreadyExists($cartId, $productId)) {

            $cartProduct = CartProduct::query()
                ->where('cart_id', '=', $cartId)
                ->where('product_id', '=', $productId)
                ->first();

            $newQuantity = $cartProduct->getAttribute('quantity') + (int)$gain;

            if ($newQuantity <= 0) {
                $cartProduct->delete();
            } else {
                $cartProduct->update(['quantity' => $newQuantity]);
            }

        } else if ((int)$gain > 0) {
            $cartProduct = new CartProduct([
                'quantity' => $gain
            ]);

            $cartProduct->cart()->associate($cart);
            $cartProduct->product()->associate(Product::query()
                ->where('id', '=', $productId)
                ->first()
            );

            $cartProduct->save();
        }
    }

    /**
     * @param int|string $cartId
     * @param int|string $productId
     * @return bool
     */

    public function cartProductAlreadyExists(
        int|string $cartId,
        int|string $productId
    ): bool
    {
        return CartProduct::query()
            ->where('cart_id', '=', $cartId)
            ->where('product_id', '=', $productId)
            ->exists();
    }

    /**
     * @param Profile $profile
     * @return bool
     */
    private function profileOwnsCart(Profile $profile): bool
    {
        return Cart::query()
            ->where('profile_id', '=', $profile->getId())
            ->exists();
    }

    /**
     * @param Profile $profile
     * @return void
     */
    private function create(Profile $profile): void
    {
        Cart::query()->create([
            'profile_id' => $profile->getId()
        ]);
    }

    /**
     * @param Profile|null $profile
     * @return ProductView[]
     */

    public function createAllProductViewsByProfile(?Profile $profile): array
    {
        if ($profile === null) {
            return [];
        }

        $productViews = $this->appendQuantityToProductViews(
            $profile,
            array_map(
                fn(CartProduct $cartProduct) => new ProductView(
                    (fn($product): Product => $product)(Product::query()
                        ->with('images')
                        ->find($cartProduct->getAttribute('product_id'))),
                    ''
                ),
                CartProduct::query()
                    ->where(
                        'cart_id',
                        '=',
                        $this->acquireCartIdByProfile($profile))
                    ->get()
                    ->all()
            )
        );

        array_walk($productViews, function(ProductView $view) {
            $view->setPath(
                $view->getProduct()->getRelation('images')->first()['path']
            );
        });

        return $productViews;
    }

    /**
     * @param ProductView[] $productViews
     * @return ProductView[]
     */

    public function appendQuantityToProductViews(
        ?Profile $profile,
        array $productViews
    ): array
    {
        if ($profile === null) {
            return $productViews;
        }

        $cartId = $this->acquireCartByProfile($profile)
            ?->getAttribute('id');

        if (!$cartId) {
            return $productViews;
        }

        array_walk($productViews, function(ProductView $view) use ($cartId) {
            $view->setQuantity(CartProduct::query()
                ->where('cart_id', '=', $cartId)
                ->where(
                    'product_id',
                    '=',
                    $view->getProduct()->getAttribute('id'))
                ->first()
                ?->getAttribute('quantity') ?? 0);
        });

        return $productViews;
    }

    /**
     * @param Profile|null $profile
     * @param ProductView $productView
     * @return ProductView
     */
    public function appendQuantityToProductView(
        ?Profile $profile,
        ProductView $productView
    ): ProductView
    {
        if ($profile === null) {
            return $productView;
        }

        $views[] = $productView;
        return $this->appendQuantityToProductViews($profile, $views)[0];
    }

    /**
     * @param Profile|null $profile
     * @return int
     */

    public function acquireTotalQuantityByProfile(?Profile $profile): int
    {
        $cart = $this->acquireCartByProfile($profile);

        if ($cart === null) {
            return 0;
        }

        return CartProduct::query()
            ->where('cart_id', '=',
                $cart->getAttribute('id'))
            ->sum('quantity');
    }

    public function acquirePriceByProfile(Profile $profile): float
    {
        $cart = $this->acquireCartByProfile($profile);

        if ($cart === null) {
            throw new ServiceException();
        }

        return array_reduce(array_map(fn($cartProduct) =>
            (float)Product::query()->find($cartProduct['product_id'])
                ->getAttribute('price') * (float)$cartProduct['quantity'],

            CartProduct::query()
                ->where('cart_id', '=', $cart->getAttribute('id'))
                ->get()
                ->toArray()
        ), function(float $acc, float $item) {
                return $acc + $item;
        }, 0.0);
    }

    /**
     * @param Profile $profile
     * @return Cart|null
     */
    public function acquireCartByProfile(Profile $profile): ?Cart
    {
        return (fn($o): ?Cart => $o)($profile->cart()->first());
    }

    /**
     * @param Profile $profile
     * @return mixed|null
     */
    public function acquireCartIdByProfile(Profile $profile): mixed
    {
        return $this->acquireCartByProfile($profile)?->getAttribute('id');
    }

    public function clearCartByProfile(Profile $profile): void
    {
        $cartId = $this->acquireCartIdByProfile($profile);

        CartProduct::query()
            ->where('cart_id', '=', $cartId)
            ->delete();
    }

    /**
     * @param Profile $profile
     * @return ProductView[]
     */

    public function createAllProductViewsAndAppendQuantity(
        Profile $profile
    ): array
    {
        return $this->appendQuantityToProductViews(
            $profile,
            $this->createAllProductViewsByProfile($profile)
        );
    }
}
