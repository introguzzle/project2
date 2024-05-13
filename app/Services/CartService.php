<?php

namespace App\Services;

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
     * @param int|string $quantityChange
     * @return void
     */
    public function update(
        Profile $profile,
        int|string $productId,
        int|string $quantityChange
    ): void
    {
        $query = Cart::query()
            ->where('profile_id', '=', $profile->getAttribute('id'));

        $cart = $query->exists()
            ? $query->first()
            : $this->createCartByProfile($profile);

        $change = (int)$quantityChange;

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
    private function createCartByProfile(Profile $profile): Cart
    {
        return Cart::query()->create(['profile_id' => $profile->getId()]);
    }

    /**
     *
     * @param Profile|null $profile
     * @return ProductView[]
     */

    public function createAllProductViewsByProfile(?Profile $profile): array
    {
        if ($profile === null) {
            return [];
        }

        // Получаем все продукты из корзины пользователя
        $cartProducts = CartProduct::query()
            ->where('cart_id', '=', $this->acquireCartIdByProfile($profile))
            ->orderBy('product_id')
            ->get()
            ->all();

        // Функция для преобразования каждого продукта корзины в представление продукта
        $toProductViewsClosure = fn(CartProduct $cartProduct) => new ProductView(
        // Получаем информацию о продукте из базы данных
            (fn($product): Product => $product)(Product::query()
                ->with('images')
                ->find($cartProduct->getAttribute('product_id'))),
            ''
        );

        // Преобразуем каждый продукт корзины в представление продукта
        $productViews = $this->appendQuantityToProductViews(
            $profile,
            array_map($toProductViewsClosure, $cartProducts)
        );

        // Функция для установки пути к изображению для каждого представления продукта
        $setPathClosure = function(ProductView $view) {
            $view->setPath(
            // Устанавливаем путь к первому изображению продукта
                $view->getProduct()->getRelation('images')->first()['path']
            );
        };

        // Устанавливаем путь к изображению для каждого представления продукта
        array_walk($productViews, $setPathClosure);
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

    public function computeTotalQuantityByProfile(?Profile $profile): int
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

    public function computePriceByProfile(Profile $profile): float
    {
        $cart = $this->acquireCartByProfile($profile);

        if ($cart === null) {
            $this->createCartByProfile($profile);
        }

        $cartProductsByCart = CartProduct::query()
            ->where('cart_id', '=', $cart->getAttribute('id'))
            ->get()
            ->toArray();

        $pricesOfCartProductsByCart = array_map(fn($cartProduct) =>
            (float)Product::find($cartProduct['product_id'])->getAttribute('price')
                * (float)$cartProduct['quantity'],

            $cartProductsByCart
        );

        $reduce = function(float $acc, float $item) {
            return $acc + $item;
        };

        return array_reduce($pricesOfCartProductsByCart, $reduce, 0.0);
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
