<?php

namespace App\Services;

use App\DTO\PostOrderDTO;
use App\Exceptions\ServiceException;
use App\Models\ReceiptMethod;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Status;
use App\Models\User\Profile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderService
{
    private CartService $cartService;

    /**
     * @param CartService $cartService
     */
    public function __construct(
        CartService $cartService
    )
    {
        $this->cartService = $cartService;
    }

    /**
     * @param Profile|null $profile
     * @param PostOrderDTO $orderDTO
     * @return void
     * @throws Throwable
     * @throws ServiceException
     */
    public function order(
        ?Profile     $profile,
        PostOrderDTO $orderDTO
    ): void
    {
        if ($profile === null) {
            throw new ServiceException();
        }

        DB::beginTransaction();

        try {
            $order = $this->createOrder($orderDTO, $profile, 'Ожидание');
            $order->save();

            $this->fillOrderFromCart($profile, $order);
            $this->clearCart($profile);

        } catch (Throwable $t) {
            DB::rollBack();
            throw $t;
        }

        DB::commit();
    }

    /**
     * @param Profile $profile
     * @return void
     */
    public function clearCart(
        Profile $profile
    ): void
    {
        $this->cartService->clearCartByProfile($profile);
    }

    /**
     * @param Profile $profile
     * @param Order $order
     * @return void
     */

    public function fillOrderFromCart(
        Profile $profile,
        Order   $order
    ): void
    {
        /**
         * @var Collection<Product> $productCollection
         */
        $cart = $profile->cart;

        if ($cart === null) {
            throw new ServiceException(
                'Не получилось заполнить заказ из корзины, потому что корзина null'
            );
        }

        $productCollection = $cart->products()->get();

        if ($productCollection->isEmpty()) {
            throw new ServiceException('Cart is actually empty');
        }

        $productCollection->each(function(Product $product) use ($order, $cart) {
            $orderProduct = new OrderProduct();

            $orderProduct->quantity = $product->getCartQuantity($cart);

            $orderProduct->product()->associate($product);
            $orderProduct->order()->associate($order);

            $orderProduct->save();
        });
    }

    /**
     * @param PostOrderDTO $orderDTO
     * @param Profile $profile
     * @param string|Status $status
     * @return Order
     */
    public function createOrder(
        PostOrderDTO  $orderDTO,
        Profile       $profile,
        string|Status $status,
    ): Order
    {
        $order = new Order();

        $order->name = $orderDTO->name;
        $order->phone = $orderDTO->phone;
        $order->address = $orderDTO->address;
        $order->totalAmount = $this->computeTotalAmount($profile);
        $order->totalQuantity = $this->computeTotalQuantity($profile);
        $order->description = '';

        $order->profile()->associate($profile);
        $order->status()->associate($status instanceof Status
            ? $status
            : Status::findByName($status)
        );

        $order->paymentMethod()->associate(
            PaymentMethod::find($orderDTO->paymentMethodId)
        );

        $order->receiptMethod()->associate(
            ReceiptMethod::find($orderDTO->receiptMethodId)
        );

        return $order;
    }

    private function computeTotalQuantity(Profile $profile): int
    {
        return $this->cartService->getTotalQuantityByProfile($profile);
    }

    private function computeTotalAmount(Profile $profile): float
    {
        return $this->cartService->getTotalAmountByProfile($profile);
    }
}
