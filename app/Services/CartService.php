<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\Profile;
use App\ModelView\ProductView;
use Illuminate\Support\Arr;

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

    public function acquireAllByProfile(?Profile $profile): array
    {
        if ($profile === null) {
            return [];
        }

        $productViews = $this->appendQuantityToProductViews(
            $profile,
            array_map(
                fn(CartProduct $cartProduct) => new ProductView(
                    Product::query()
                        ->with('images')
                        ->find($cartProduct->getAttribute('product_id')),
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
        return CartProduct::query()
            ->where('cart_id', '=',
                $this->acquireCartByProfile($profile)->getAttribute('id'))
            ->sum('quantity');
    }

    /**
     * @param Profile $profile
     * @return Cart|null
     */
    public function acquireCartByProfile(Profile $profile): ?Cart
    {
        return Cart::query()
            ->where('profile_id', '=', $profile->getId())
            ->first();
    }

    /**
     * @param Profile $profile
     * @return mixed
     */
    public function acquireCartIdByProfile(Profile $profile): mixed
    {
        return $this->acquireCartByProfile($profile)->getAttribute('id');
    }
}
