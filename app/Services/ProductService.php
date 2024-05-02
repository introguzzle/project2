<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductImage;
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
     * @return ProductView[]
     */
    public function acquireAllProductViews(): array
    {
//        return array_map(
//            fn(Product $product) => new ProductView($product,
//                Image::query()
//                    ->where('id', '=', ProductImage::query()
//                        ->where('product_id', '=',
//                            $product->getAttribute('id'))
//                        ->first()
//                        ->getAttribute('image_id')
//                    )
//
//                    ->first()->getAttribute('path')
//            ), $this->acquireAll()
//        );

        return array_map(fn(Product $product) => new ProductView(
            $product,
            $product->getRelation('images')->all()[0]['path']

        ), Product::with('images')->get()->all());
    }

    /**
     * @return ProductView[]
     */

    public function acquireProductViewsByCategory(
        int|string $categoryId
    ): array
    {
        return array_values(array_filter($this->acquireAllProductViews(),
            function(ProductView $productView) use ($categoryId) {
                return $productView->getProduct()->getAttribute('category_id') == $categoryId;
            }
        ));
    }

    /**
     * @param int|string $id
     * @return ProductView|null
     */
    public function acquireProductViewById(int|string $id): ?ProductView
    {
        foreach ($this->acquireAllProductViews() as $productView) {
            if ($productView->getProduct()->getAttribute('id') == $id)
                return $productView;
        }

        return null;
    }
}
