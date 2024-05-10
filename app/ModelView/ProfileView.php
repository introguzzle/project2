<?php

namespace App\ModelView;

use App\Models\Identity;
use App\Models\Order;
use App\Models\Profile;
use JsonSerializable;

class ProfileView implements JsonSerializable
{
    private Profile $profile;
    private Identity $identity;

    /**
     * @var Order[]
     */
    private array $orders;

    /**
     * @param Profile $profile
     * @param Identity $identity
     * @param Order[] $orders
     */
    public function __construct(
        Profile  $profile,
        Identity $identity,
        array    $orders = []
    )
    {
        $this->profile = $profile;
        $this->identity = $identity;
        $this->orders = $orders;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): ProfileView
    {
        $this->profile = $profile;
        return $this;
    }

    public function getIdentity(): Identity
    {
        return $this->identity;
    }

    public function setIdentity(Identity $identity): ProfileView
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * @return Order[]
     */

    public function getOrders(): array
    {
        return $this->orders;
    }

    public function setOrders(array $orders): ProfileView
    {
        $this->orders = $orders;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */

    public function jsonSerialize(): array
    {
        return [
            'profile' => $this->profile->toArray(),
            'identity' => $this->identity->toArray(),
            'orders' => $this->orders
        ];
    }
}
