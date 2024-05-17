<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Profile;
use App\ModelView\ProductView;

class ProductService
{
    /**
     * @return Product[]
     */

    public function acquireAll(): array
    {
        return Product::all()->all();
    }

    /**
     * @param int|string $categoryId
     * @return Product[]
     */
    public function acquireAllByCategory(int|string $categoryId): array
    {
        return Product::query()
            ->where('category_id', '=', $categoryId)
            ->get()
            ->all();
    }

    /**
     * @param int|string $productId
     * @return Product|null
     */

    public function acquireById(int|string $productId): ?Product
    {
        return Product::find((int)$productId);
    }
}
