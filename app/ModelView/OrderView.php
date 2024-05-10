<?php

namespace App\ModelView;

use App\Models\Order;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Status;
use JsonSerializable;

class OrderView implements JsonSerializable
{
    private Order $order;
    private Status $status;
    private Profile $profile;

    /**
     * @var ProductView[]
     */
    private array $productViews;

    /**
     * @param Order $order
     * @param Status $status
     * @param Profile $profile
     * @param ProductView[] $productViews
     */
    public function __construct(
        Order $order,
        Status $status,
        Profile $profile,
        array $productViews
    )
    {
        $this->order = $order;
        $this->status = $status;
        $this->profile = $profile;
        $this->productViews = $productViews;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): static
    {
        $this->order = $order;
        return $this;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): static
    {
        $this->profile = $profile;
        return $this;
    }

    /**
     * @return ProductView[]
     */

    public function getProductViews(): array
    {
        return $this->productViews;
    }

    public function setProductViews(array $productViews): static
    {
        $this->productViews = $productViews;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'order' => $this->order->jsonSerialize(),
            'status' => $this->status->jsonSerialize(),
            'profile' => $this->profile->jsonSerialize(),
            'products' => $this->productViews
        ];
    }
}
