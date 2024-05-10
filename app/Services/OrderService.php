<?php

namespace App\Services;

use App\DTO\PostOrderDTO;
use App\Exceptions\ServiceException;
use App\Models\CartProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Status;
use App\ModelView\OrderView;
use App\ModelView\ProductView;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderService
{
    /**
     * @var CartService
     */
    private CartService $cartService;
    private ProductService $productService;

    /**
     * @param CartService $cartService
     * @param ProductService $productService
     */
    public function __construct(
        CartService $cartService,
        ProductService $productService
    )
    {
        $this->cartService = $cartService;
        $this->productService = $productService;
    }

    public function setOrderStatus(
        Order $order,
        Status $status
    ): bool
    {
        return $order->update(['status_id' => $status->getAttribute('id')]);
    }

    /**
     * @return Order[]
     */

    public function acquireAllActiveOrders(): array
    {
        return Order::query()
            ->where('status_id',
                '=',
                Status::acquireAwaiting()->getAttribute('id')
            )
            ->get()
            ->all();
    }

    /**
     * @return Collection
     */

    public function createLatestOrderViewsAndSerialize(): Collection
    {
        $data = $this->createOrderViews(
            Order::query()->latest()->get()->all()
        );

        return collect($data)->map(function(OrderView $item) {
            return [
                'order' => $item->getOrder(),
                'status' => $item->getStatus(),
                'profile' => $item->getProfile(),
                'productsViews' => $item->getProductViews(),
            ];
        });
    }

    /**
     * @return OrderView[]
     */

    public function createAllActiveOrderViews(): array
    {
        return $this->createOrderViews($this->acquireAllActiveOrders());
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
            $order = $this->newOrder($orderDTO, $profile);
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
        Order $order
    ): void
    {
        $cartProducts = CartProduct::query()
            ->where('cart_id', '=',
                $this->cartService->acquireCartIdByProfile($profile))
            ->get()
            ->all();

        if (empty($cartProducts)) {
            throw new ServiceException('Cart is actually empty');
        }

        array_walk($cartProducts, function($cartProduct) use ($order) {
            (new OrderProduct([
                'product_id' => $cartProduct['product_id'],
                'quantity' => $cartProduct['quantity']
            ]))->order()
                ->associate($order)
                ->save();
        });
    }

    /**
     * @return Order[]
     */

    public function acquireAll(int $limit = 100): array
    {
        return Order::query()->limit($limit)->get()->all();
    }

    /**
     * @return object[]
     */

    public function acquireAllAndJoinStatus(): array
    {
        return DB::table('orders')
            ->select(['orders.*', 'statuses.name'])
            ->join('statuses', function(JoinClause $join) {
                  $join->on('orders.status_id', '=', 'statuses.id');
            })->get()->all();
    }

    /**
     * @param Order[] $orders
     * @return OrderView[]
     */

    public function createOrderViews(array $orders): array
    {
        return array_map(fn(Order $order) => $this->createOrderView($order), $orders);
    }

    /**
     * @param int $limit
     * @return OrderView[]
     */

    public function createAllOrderViews(int $limit = 100): array
    {
        return $this->createOrderViews($this->acquireAll($limit));
    }

    /**
     * @param OrderView[] $orderViews
     * @return OrderView[]
     */

    public function appendImagesToOrderViews(
        array $orderViews
    ): array
    {
        array_walk($orderViews, function(OrderView $orderView) {
            $this->productService->appendImagesToProductViews(
                $orderView->getProductViews()
            );
        });

        return $orderViews;
    }

    /**
     * @param int $limit
     * @return OrderView[]
     */

    public function createAllOrderViewsAndAppendImages(int $limit = 100): array
    {
        return $this->appendImagesToOrderViews($this->createAllOrderViews($limit));
    }

    /**
     * @param PostOrderDTO $orderDTO
     * @param Profile $profile
     * @return Order
     */
    public function newOrder(
        PostOrderDTO $orderDTO,
        Profile $profile
    ): Order
    {
        $order = new Order([
            'name' => $orderDTO->getName(),
            'phone' => $orderDTO->getPhone(),
            'address' => $orderDTO->getAddress(),
            'price' => $orderDTO->getPrice()
        ]);

        $order->profile()->associate($profile);
        $order->status()->associate(Status::acquireAwaiting());

        return $order;
    }

    /**
     * @param Order $order
     * @return OrderView
     */

    public function createOrderView(
        Order $order
    ): OrderView
    {
        return new OrderView(
            $order,
            Status::find($order->getStatusId()),
            Profile::find($order->getProfileId()),
            array_map(fn(OrderProduct $orderProduct) => new ProductView(
                (fn($object): Product => $object)(Product::query()
                    ->find($orderProduct->getAttribute('product_id'))
                ),
                '',
                $orderProduct->getAttribute('quantity')
            ),

                OrderProduct::query()
                    ->where('order_id', '=', $order->getAttribute('id'))
                    ->get()
                    ->all()
            )
        );
    }

    /**
     * @param int $id
     * @return OrderView
     */

    public function createOrderViewById(
        int $id
    ): OrderView
    {
        return $this->createOrderView(Order::find($id));
    }
}
