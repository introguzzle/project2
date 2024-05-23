<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

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
            ['profile_id' => $profile->id]
        );

        $cartId = $cart->id;
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
        return $t(Cart::query()->create(['profile_id' => $profile->id]));
    }

    /**
     * @param Profile|null $profile
     * @return int
     */

    public function getTotalQuantityByProfile(?Profile $profile): int
    {
        $cart = $profile->cart;

        if ($cart === null) {
            return 0;
        }

        return CartProduct::query()
            ->where('cart_id', '=', $cart->id)
            ->sum('quantity');
    }

    public function getTotalAmount(Profile $profile): float
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

    /**
     * @param Profile $profile
     * @return void
     */

    public function group(
        Profile $profile,
    ): void
    {
        DB::beginTransaction();

        try {
            $cart = $profile->cart;
            $cartProducts = $cart->getRelatedCartProducts();

            $map = [];

            foreach ($cartProducts as $cartProduct) {
                $productId = $cartProduct->product->id;

                if (!isset($map[$productId])) {
                    $map[$productId] = 0;
                }

                $map[$productId] += $cartProduct->quantity;
            }

            foreach ($cartProducts as $cartProduct) {
                $cartProduct->delete();
            }

            foreach ($map as $productId => $quantity) {
                CartProduct::query()
                    ->create([
                        'cart_id' => $cart->id,
                        'product_id' => $productId,
                        'quantity' => $quantity
                    ]);
            }
        } catch (Throwable) {
            DB::rollBack();
        }

        DB::commit();
    }

    /**
     * @param Profile $profile
     * @return Product[]
     */

    public function groupAndGet(
        Profile $profile
    ): array
    {
        $this->group($profile);
        return $profile->cart->products->all();
    }
}
