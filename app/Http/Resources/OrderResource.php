<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class OrderResource extends JsonResource
{
    /**
     * @var Order $resource
     */
    public $resource;
    public function __construct(Order $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array|JsonSerializable|Arrayable
    {
        return $this->resource->toArray()
            + ['status_name' => $this->resource->getRelatedStatus()->getName()]
            + ['profile_name' => $this->resource->getRelatedProfile()->getName()];
    }
}
