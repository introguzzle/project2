<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductImage;
use App\ModelView\ProductView;

class ProductService
{
    /**
     * @return Product[]
     */
    public
    function acquireAll(): array
    {
        return Product::all()->all();
    }


    /**
     * @return ProductView[]
     */
    public
    function createAllProductViews(): array
    {
        return array_map(fn(Product $product) => new ProductView(
            $product,
            $product->getRelation('images')->all()[0]['path']

        ), Product::with('images')->get()->all());
    }

    /**
     * @param ProductView[] $productViews
     * @return ProductView[]
     */

    public
    function appendImagesToProductViews(
        array $productViews
    ): array
    {
        array_walk($productViews, function(ProductView $productView) {
            $productView->setPath(Image::query()
                ->where('id', '=', ProductImage::query()
                    ->where('product_id', '=',
                        $productView->getProduct()->getAttribute('id'))
                    ->first()
                    ->getAttribute('image_id')
                )
                ->first()->getAttribute('path')
            );
        });

        return $productViews;
    }

    /**
     * @param ProductView $productView
     * @return ProductView
     */

    public
    function appendImageToProductView(
        ProductView $productView
    ): ProductView
    {
        $views[] = $productView;
        return $this->appendImagesToProductViews($views)[0];
    }

    /**
     * @return ProductView[]
     */

    public
    function createProductViewsByCategory(
        int|string $categoryId
    ): array
    {
        return array_values(array_filter($this->createAllProductViews(),
            function(ProductView $productView) use ($categoryId) {
                return $productView->getProduct()->getAttribute('category_id') == $categoryId;
            }
        ));
    }

    /**
     * @param int|string $id
     * @return ProductView|null
     */
    public
    function createProductViewById(
        int|string $id
    ): ?ProductView
    {
        foreach ($this->createAllProductViews() as $productView) {
            if ($productView->getProduct()->getAttribute('id') == $id)
                return $productView;
        }

        return null;
    }
}
