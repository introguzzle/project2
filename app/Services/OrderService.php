<?php

namespace App\Services;

use App\DTO\PostOrderDTO;
use App\Exceptions\ServiceException;
use App\Models\OrderPromotion;
use App\Models\Promotion;
use App\Models\PromotionType;
use App\Models\ReceiptMethod;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Status;
use App\Models\User\Profile;
use App\Services\Core\UsesTransaction;

use Illuminate\Database\Eloquent\Collection;

use Throwable;

class OrderService
{
    use UsesTransaction;
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

        $this->beginTransaction();

        try {
            $order = $this->createOrder($orderDTO, $profile, Status::pending());
            $order->afterAmount = $order->totalAmount;
            $order->save();

            $order->afterAmount = $this->amountAfterPromotions($order);
            $order->save();

            $this->fillOrderFromCart($profile, $order);
            $this->clearCart($profile);

        } catch (Throwable $t) {
            $this->rollbackTransaction();
            throw $t;
        }

        $this->commitTransaction();
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
         * @var Collection<Product> $products
         */
        $cart = $profile->cart;

        if ($cart === null) {
            throw new ServiceException(
                'Не получилось заполнить заказ из корзины, потому что корзины не существует'
            );
        }

        $products = $cart->products;

        if ($products->isEmpty()) {
            throw new ServiceException('Корзина пустая, заказ невозможен');
        }

        $products->each(static function (Product $product) use ($order, $cart) {
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

    /**
     * @param Profile $profile - Профиль
     * @return float
     */
    private function computeTotalAmount(
        Profile $profile
    ): float
    {
        return $this->cartService->getTotalAmountByProfile($profile);
    }

    /**
     * @param Order $order - НЕОБХОДИМО, ЧТОБЫ У НЕГО БЫЛ ID, ТО ЕСТЬ ЭТО ЗАКАЗ ИЗ БД
     * @return float
     */
    private function amountAfterPromotions(Order $order): float
    {
        $totalAmount = $order->totalAmount;

        $saveOrderPromotion = static function (Promotion $promotion) use ($order) {
            $orderPromotion = new OrderPromotion();
            $orderPromotion->promotion()->associate($promotion);
            $orderPromotion->order()->associate($order);

            $orderPromotion->save();
        };

        // TODO: Решить проблему стакающихся/нестакающихся процентов
        //
        //
        //

        foreach (Promotion::all() as $promotion) {
            $flowMatch = false;

            foreach ($promotion->flows as $flow) {
                if ($flow->paymentMethod->id === $order->paymentMethod->id
                    && $flow->receiptMethod->id === $order->receiptMethod->id) {

                    $flowMatch = true;
                    break;
                }
            }

            if ($flowMatch === false) {
                continue;
            }

            if ($totalAmount >= $promotion->minSum && $totalAmount <= $promotion->maxSum) {
                if ($promotion->promotionType->equals(PromotionType::fixed())) {
                    $totalAmount -= $promotion->value;
                    $saveOrderPromotion($promotion);

                } else if ($promotion->promotionType->equals(PromotionType::percentage())) {
                    $totalAmount -= $totalAmount * ($promotion->value / 100);
                    $saveOrderPromotion($promotion);
                }
            }
        }

        return $totalAmount;
    }
}
