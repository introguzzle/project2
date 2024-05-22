<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\Profile;

class CartService
{
    /**
     * @param Profile $profile
     * @param int|string $productId
     * @param int|string $quantityChange
     * @return void
     */
    public function update(
        Profile $profile,
        int|string $productId,
        int|string $quantityChange
    ): void
    {
        $change = (int)$quantityChange;

        if ($change === 0) {
            return;
        }

        $cart = Cart::query()->firstOrCreate(
            ['profile_id' => $profile->getAttribute('id')]
        );

        $cartId = $cart->getAttribute('id');
        $where  = CartProduct::query()
            ->where('cart_id', '=', $cartId)
            ->where('product_id', '=', $productId);

        if ($where->exists()) {
            $cartProduct = $where->first();
            $newQuantity = ((int)$cartProduct->getAttribute('quantity')) + $change;

            if ($newQuantity <= 0) {
                $cartProduct->delete();
            } else {
                $cartProduct->update(['quantity' => $newQuantity]);
            }

        } else if ($change > 0) {
            $cartProduct = new CartProduct(['quantity' => $change]);

            $cartProduct->cart()->associate($cart);
            $cartProduct->product()->associate(Product::find((int)$productId));

            $cartProduct->save();
        }
    }

    /**
     * @param Profile $profile
     * @return Cart
     */
    public function createCart(Profile $profile): Cart
    {
        $t = fn($o): Cart => $o;
        return $t(Cart::query()->create(['profile_id' => $profile->getId()]));
    }

    /**
     * @param Profile|null $profile
     * @return int
     */

    public function getTotalQuantityByProfile(?Profile $profile): int
    {
        $cart = $profile->getRelatedCart();

        if ($cart === null) {
            return 0;
        }

        return CartProduct::query()
            ->where('cart_id', '=', $cart->getAttribute('id'))
            ->sum('quantity');
    }

    public function getTotalAmount(Profile $profile): float
    {
        $cart = $profile->getRelatedCart();

        if ($cart === null) {
            $this->createCart($profile);
            return 0.0;
        }

        return $cart->getTotalAmount();
    }

    public function clearCartByProfile(Profile $profile): void
    {
        $cartId = $profile->getRelatedCart()->getAttribute('id');

        CartProduct::query()
            ->where('cart_id', '=', $cartId)
            ->delete();
    }
}
