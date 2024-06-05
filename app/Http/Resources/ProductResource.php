<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Other\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @var Product $resource
     */
    public $resource;
    public function __construct(Product $resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $path     = ['path' => $this->resource->getPath()];
        $quantity = ['quantity' => $this->resource->getCartQuantity(Auth::getProfile()?->cart)];

        return $this->resource->toArray() + $path + $quantity;
    }
}
