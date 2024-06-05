<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\User\Profile;

class CartService
{
    /**
     * @param Profile $profile
     * @param int $productId
     * @param int $quantityChange
     * @return void
     */
    public function update(
        Profile $profile,
        int $productId,
        int $quantityChange
    ): void
    {
        if ($quantityChange === 0) {
            return;
        }

        $cart = Cart::firstOrCreate(
            ['profile_id' => $profile->id]
        );

        $cartId = $cart->id;
        $cartProduct = CartProduct::find(['cart_id' => $cartId, 'product_id' => $productId]);

        if ($cartProduct !== null) {
            $newQuantity = $cartProduct->quantity + $quantityChange;

            if ($newQuantity <= 0) {
                $cartProduct->delete();
            } else {
                $cartProduct->update(['quantity' => $newQuantity]);
            }

            return;
        }

        if ($quantityChange > 0) {
            CartProduct::query()->upsert(
                [
                    [
                        'cart_id'    => $cartId,
                        'product_id' => $productId,
                        'quantity'   => $quantityChange
                    ]
                ],
                ['cart_id', 'product_id'],
                ['quantity']
            );
        }
    }

    /**
     * @param Profile $profile
     * @return Cart
     */
    public function createCart(Profile $profile): Cart
    {
        $t = static fn($o): Cart => $o;
        return $t(Cart::query()->create(['profile_id' => $profile->id]));
    }

    /**
     * @param Profile $profile
     * @return int
     */

    public function getTotalQuantityByProfile(Profile $profile): int
    {
        $cart = $profile->cart;

        if ($cart === null) {
            return 0;
        }

        return CartProduct::query()
            ->where('cart_id', '=', $cart->id)
            ->sum('quantity');
    }

    public function getTotalAmountByProfile(Profile $profile): float
    {
        $cart = $profile->cart;

        if ($cart === null) {
            $this->createCart($profile);
            return 0.0;
        }

        return $cart->getTotalAmount();
    }

    public function clearCartByProfile(Profile $profile): void
    {
        $cartId = $profile->cart->id;

        CartProduct::query()
            ->where('cart_id', '=', $cartId)
            ->delete();
    }
}
