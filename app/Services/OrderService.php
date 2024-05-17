<?php

namespace App\Services;

use App\DTO\PostOrderDTO;
use App\Exceptions\ServiceException;
use App\Mail\TelegramOrderNotification;
use App\Models\CartProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Status;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use Throwable;

class OrderService
{
    private CartService $cartService;
    private TelegramService $telegramService;

    /**
     * @param CartService $cartService
     * @param TelegramService $telegramService
     */
    public function __construct(
        CartService $cartService,
        TelegramService $telegramService
    )
    {
        $this->cartService = $cartService;
        $this->telegramService = $telegramService;
    }

    public function setOrderStatus(
        Order $order,
        Status $status
    ): bool
    {
        return $order->update(['status_id' => $status->getAttribute('id')]);
    }

    /**
     * @return Collection
     */

    public function acquireLatest(): Collection
    {
        return Order::query()->latest()->get();
    }

    public function acquireLatestAndSerialize(): \Illuminate\Support\Collection
    {
        $orders = $this->acquireLatest()->all();

        return collect($orders)->map(function(Order $order) {
            return [
                'order' => $order,
                'status' => $order->getRelatedStatus(),
                'profile' => $order->getRelatedProfile()
            ];
        });
    }

    /**
     * @param Profile|null $profile
     * @param PostOrderDTO $orderDTO
     * @return void
     * @throws Throwable
     * @throws ServiceException
     */
    public function order(
        ?Profile $profile,
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

        $this->telegramService->sendToAll($this->createNotification($order));
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
        Order $order
    ): void
    {
        /**
         * @var Collection<Product> $productCollection
         */
        $cart = $profile->getRelatedCart();
        $productCollection = $cart->products()->get();

        if ($productCollection->isEmpty()) {
            throw new ServiceException('Cart is actually empty');
        }

        $productCollection->each(function(Product $product) use ($order, $cart) {
            $orderProduct = new OrderProduct([
                'product_id' => $product->getId(),
                'quantity' => $product->getCartQuantity($cart)
            ]);

            $orderProduct->order()->associate($order)->save();
        });
    }

    /**
     * @param PostOrderDTO $orderDTO
     * @param Profile $profile
     * @param string|Status $status
     * @return Order
     */
    public function createOrder(
        PostOrderDTO $orderDTO,
        Profile $profile,
        string|Status $status,
    ): Order
    {
        $order = new Order([
            'name' => $orderDTO->getName(),
            'phone' => $orderDTO->getPhone(),
            'address' => $orderDTO->getAddress(),
            'total_amount' => $this->computeTotalAmount($profile),
            'total_quantity' => $this->computeTotalQuantity($profile),
            'description' => ''
        ]);

        $order->profile()->associate($profile);
        $order->status()->associate($status instanceof Status
            ? $status
            : Status::acquireByName($status)
        );

        return $order;
    }

    private function computeTotalQuantity(Profile $profile): int
    {
        return $this->cartService->computeTotalQuantityByProfile($profile);
    }

    private function computeTotalAmount(Profile $profile): float
    {
        return $this->cartService->computePriceByProfile($profile);
    }

    private function createNotification(Order $order): TelegramOrderNotification
    {
        return new TelegramOrderNotification($order);
    }
}
