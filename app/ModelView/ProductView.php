<?php

namespace App\ModelView;

use App\Models\Product;
use JsonSerializable;

class ProductView implements JsonSerializable
{
    private Product $product;
    private string $path;

    private int $quantity;

    /**
     * @param Product $product
     * @param string $path
     * @param int $quantity
     */
    public function __construct(
        Product $product,
        string $path,
        int $quantity = 0
    )
    {
        $this->product = $product;
        $this->path = $path;
        $this->quantity = $quantity;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): ProductView
    {
        $this->product = $product;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): ProductView
    {
        $this->path = $path;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): ProductView
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'product' => $this->product->toArray(),
            'path' => $this->path,
            'quantity' => $this->quantity
        ];
    }
}
