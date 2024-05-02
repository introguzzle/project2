<?php

namespace App\ModelView;

use App\Models\Identity;
use App\Models\Order;
use App\Models\Profile;
use Illuminate\Contracts\Auth\Authenticatable;
use JsonSerializable;

class ProfileView implements JsonSerializable
{
    private Profile $profile;
    private Authenticatable $authenticatable;

    /**
     * @var Order[]
     */
    private array $orders;

    /**
     * @param Profile $profile
     * @param Authenticatable $authenticatable
     * @param Order[] $orders
     */
    public function __construct(
        Profile  $profile,
        Authenticatable $authenticatable,
        array    $orders = []
    )
    {
        $this->profile = $profile;
        $this->authenticatable = $authenticatable;
        $this->orders = $orders;
    }

    /**
     * @return Profile
     */

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    /**
     * @return Authenticatable
     */

    public function getAuthenticatable(): Authenticatable
    {
        return $this->authenticatable;
    }

    /**
     * @return Order[]
     */

    public function getOrders(): array
    {
        return $this->orders;
    }

    /**
     * @return array<string, mixed>
     */

    public function jsonSerialize(): array
    {
        return [
            'profile' => $this->profile->toArray(),
            'identity' => $this->authenticatable->toArray(),
            'orders' => $this->orders
        ];
    }
}
